<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\KodeMail;
use App\Models\Jurusan;
use App\Models\User;
use App\Support\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Login (NIM / ID Ormawa), registrasi mahasiswa + verifikasi kode email, logout.
 */
class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string|max:100',
            'password' => 'required|string',
        ], [], ['identifier' => 'NIM / ID Ormawa', 'password' => 'kata sandi']);

        // Cari user berdasarkan NIM, ID (username), atau email
        $user = User::where('nim', $data['identifier'])
            ->orWhere('username', $data['identifier'])
            ->orWhere('email', $data['identifier'])
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['identifier' => 'NIM/ID atau kata sandi salah.'])->onlyInput('identifier');
        }
        if (! $user->is_active) {
            return back()->withErrors(['identifier' => 'Akun kamu dinonaktifkan. Hubungi Admin FMIPA.'])->onlyInput('identifier');
        }
        // Mahasiswa wajib verifikasi email dulu
        if (! $user->email_verified_at) {
            self::sendVerificationCode($user);
            $request->session()->put('verify_email', $user->email);

            return redirect()->route('verification.show')->with('info', 'Akun belum terverifikasi. Kode baru sudah dikirim ke email kamu.');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended($user->homeRoute())->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function showRegister()
    {
        return view('auth.register', ['jurusan' => Jurusan::orderBy('nama')->get()]);
    }

    public function register(Request $request)
    {
        $sertif = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'nim' => 'required|digits_between:8,15|unique:users,nim',
            'jurusan_id' => 'required|exists:jurusan,id',
            'angkatan' => 'required|integer|min:2015|max:' . (now()->year + 1),
            'phone' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'email' => 'required|email|max:100|unique:users,email',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
            'sertifikat_lkmm' => $sertif,
            'sertifikat_pkkmb_jurusan' => $sertif,
            'sertifikat_pkkmb_fakultas' => $sertif,
            'sertifikat_pengalaman' => $sertif,
            'setuju' => 'accepted',
        ], [
            'phone.regex' => 'Nomor telepon harus 10-13 digit angka.',
            'setuju.accepted' => 'Kamu harus menyetujui syarat & ketentuan.',
        ], [
            'name' => 'nama', 'jurusan_id' => 'jurusan', 'phone' => 'nomor telepon', 'password' => 'kata sandi',
        ]);

        $user = new User([
            'name' => $data['name'], 'nim' => $data['nim'], 'jurusan_id' => $data['jurusan_id'],
            'angkatan' => $data['angkatan'], 'phone' => $data['phone'], 'email' => $data['email'],
            'password' => $data['password'], 'role' => User::MAHASISWA,
        ]);
        foreach (array_keys(User::sertifikatFields()) as $field) {
            $user->{$field} = Files::store($request->file($field), 'sertifikat');
        }
        $user->save();

        self::sendVerificationCode($user);
        $request->session()->put('verify_email', $user->email);

        return redirect()->route('verification.show')->with('success', 'Registrasi berhasil! Masukkan kode verifikasi yang dikirim ke email kamu.');
    }

    /** Buat kode 6 digit, simpan hash-nya, kirim via email. */
    public static function sendVerificationCode(User $user): string
    {
        $kode = (string) random_int(100000, 999999);
        $user->forceFill([
            'verification_code' => Hash::make($kode),
            'verification_expires_at' => now()->addMinutes(15),
        ])->save();

        try {
            Mail::to($user->email)->send(new KodeMail($kode, 'Kode Verifikasi Akun', $user->name));
        } catch (\Throwable $e) {
            report($e); // SMTP belum diatur: kode tetap tersimpan, bisa dikirim ulang
            session()->flash('error', 'Email kode gagal dikirim. Periksa pengaturan MAIL di .env lalu klik Kirim Ulang.');
        }
        // Mode pengembangan (mail log): tampilkan kode di halaman supaya mudah dicoba
        if (config('app.debug') && config('mail.default') === 'log') {
            session()->flash('dev_code', $kode);
        }

        return $kode;
    }

    public function showVerification(Request $request)
    {
        if (! $request->session()->has('verify_email')) {
            return redirect()->route('login');
        }

        return view('auth.verify', ['email' => $request->session()->get('verify_email')]);
    }

    public function verify(Request $request)
    {
        $request->validate(['kode' => 'required|digits:6'], [], ['kode' => 'kode verifikasi']);
        $user = User::where('email', $request->session()->get('verify_email'))->first();

        if (! $user || ! $user->verification_code || now()->greaterThan($user->verification_expires_at)
            || ! Hash::check($request->kode, $user->verification_code)) {
            return back()->withErrors(['kode' => 'Kode salah atau sudah kedaluwarsa.']);
        }

        $user->forceFill(['email_verified_at' => now(), 'verification_code' => null, 'verification_expires_at' => null])->save();
        $request->session()->forget('verify_email');

        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi. Silakan login.');
    }

    public function resendVerification(Request $request)
    {
        $user = User::where('email', $request->session()->get('verify_email'))->first();
        if (! $user) {
            return redirect()->route('login');
        }
        self::sendVerificationCode($user);

        return back()->with('success', 'Kode verifikasi baru sudah dikirim.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
