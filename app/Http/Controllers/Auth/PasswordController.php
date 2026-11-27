<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\KodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Lupa password 3 langkah: (1) cari email/no WA, (2) masukkan kode 6 digit, (3) password baru.
 * Kode dikirim via email (WhatsApp butuh layanan pihak ketiga, lihat README).
 */
class PasswordController extends Controller
{
    public function showRequest()
    {
        return view('auth.forgot');
    }

    public function sendCode(Request $request)
    {
        $request->validate(['akun' => 'required|string|max:100'], [], ['akun' => 'email / no. WhatsApp']);
        $user = User::where('email', $request->akun)->orWhere('phone', $request->akun)->first();

        if (! $user) {
            return back()->withErrors(['akun' => 'Akun dengan email / nomor tersebut tidak ditemukan.'])->onlyInput('akun');
        }

        $this->issueCode($user);
        $request->session()->put('reset_email', $user->email);

        return redirect()->route('password.code')->with('success', 'Kode verifikasi sudah dikirim ke ' . $this->mask($user->email));
    }

    public function resend(Request $request)
    {
        $user = User::where('email', $request->session()->get('reset_email'))->first();
        if (! $user) {
            return redirect()->route('password.request');
        }
        $this->issueCode($user);

        return back()->with('success', 'Kode baru sudah dikirim.');
    }

    public function showCode(Request $request)
    {
        if (! $request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.forgot-code', ['email' => $this->mask($request->session()->get('reset_email'))]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['kode' => 'required|digits:6'], [], ['kode' => 'kode']);
        $email = $request->session()->get('reset_email');
        $row = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $row || now()->diffInMinutes($row->created_at, true) > 15 || ! Hash::check($request->kode, $row->token)) {
            return back()->withErrors(['kode' => 'Kode salah atau sudah kedaluwarsa.']);
        }
        $request->session()->put('reset_verified', true);

        return redirect()->route('password.reset');
    }

    public function showReset(Request $request)
    {
        if (! $request->session()->get('reset_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset');
    }

    public function reset(Request $request)
    {
        if (! $request->session()->get('reset_verified')) {
            return redirect()->route('password.request');
        }
        $request->validate(['password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()->symbols()]], [], ['password' => 'password baru']);

        $email = $request->session()->get('reset_email');
        User::where('email', $email)->first()?->update(['password' => $request->password]);
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        $request->session()->forget(['reset_email', 'reset_verified']);

        return redirect()->route('login')->with('success', 'Kata sandi berhasil diperbarui. Silakan login.');
    }

    private function issueCode(User $user): void
    {
        $kode = (string) random_int(100000, 999999);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($kode), 'created_at' => now()]
        );
        try {
            Mail::to($user->email)->send(new KodeMail($kode, 'Kode Reset Kata Sandi', $user->name));
        } catch (\Throwable $e) {
            report($e); // SMTP belum diatur: kode tetap tersimpan, bisa dikirim ulang
            session()->flash('error', 'Email kode gagal dikirim. Periksa pengaturan MAIL di .env lalu klik Kirim Ulang.');
        }
        if (config('app.debug') && config('mail.default') === 'log') {
            session()->flash('dev_code', $kode);
        }
    }

    /** wiranatha@student.unud.ac.id -> wi*****@student.unud.ac.id */
    private function mask(string $email): string
    {
        [$name, $domain] = explode('@', $email) + [1 => ''];

        return substr($name, 0, 2) . str_repeat('*', max(strlen($name) - 2, 3)) . '@' . $domain;
    }
}
