<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\User;
use App\Support\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Kelola Akun Ormawa oleh Admin FMIPA.
 * Tambah Akun = membuat data organisasi baru + akun admin ormawa-nya.
 * Login ormawa memakai Email Organisasi (atau ID/username bila ada).
 */
class OrmawaAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', User::ORMAWA)->with('organisasi')->latest();
        if ($request->filled('q')) {
            $kata = '%' . $request->q . '%';
            $query->where(fn ($w) => $w->where('email', 'like', $kata)
                ->orWhereHas('organisasi', fn ($o) => $o->where('nama', 'like', $kata)->orWhere('nama_ketua', 'like', $kata)));
        }

        return view('admin.ormawa.index', ['accounts' => $query->paginate(10)->withQueryString()]);
    }

    public function store(Request $request)
    {
        $data = $request->validateWithBag('tambah', [
            'nama' => 'required|string|max:100|unique:organisasi,nama',
            'nama_ketua' => 'required|string|max:100',
            'nomor_telpon' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'email' => 'required|email|max:100|unique:users,email',
            'akun_sosmed' => 'nullable|string|max:100',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'sk_ormawa' => 'required|file|mimes:pdf|max:5120',
        ], ['nomor_telpon.regex' => 'Nomor telepon harus 10-13 digit angka.'], [
            'nama' => 'nama lengkap ormawa', 'nama_ketua' => 'nama ketua umum', 'nomor_telpon' => 'nomor telp organisasi',
            'email' => 'email organisasi', 'akun_sosmed' => 'akun Instagram', 'password' => 'password', 'sk_ormawa' => 'surat keputusan ormawa',
        ]);

        try {
            DB::transaction(function () use ($request, $data) {
                $org = Organisasi::create([
                    'nama' => $data['nama'],
                    'singkatan' => $this->singkatan($data['nama']),
                    'nama_ketua' => $data['nama_ketua'],
                    'email' => $data['email'],
                    'nomor_telpon' => $data['nomor_telpon'],
                    'akun_sosmed' => $data['akun_sosmed'] ?? null,
                    'sk_ormawa' => Files::store($request->file('sk_ormawa'), 'sk'),
                    'tahun_kepengurusan' => now()->year . '/' . (now()->year + 1),
                ]);
                $user = new User([
                    'name' => 'Admin ' . $org->singkatan,
                    'role' => User::ORMAWA,
                    'email' => $data['email'],
                    'phone' => $data['nomor_telpon'],
                    'organisasi_id' => $org->id,
                    'password' => $data['password'],
                ]);
                $user->email_verified_at = now(); // dibuat admin -> langsung aktif
                $user->save();
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Gagal menambahkan akun ormawa. Silakan coba lagi.');
        }

        return redirect()->route('fmipa.ormawa.index')->with('success', 'Akun Ormawa Berhasil Ditambahkan.');
    }

    /** Ganti password akun ormawa (modal Lihat Detail). */
    public function update(Request $request, User $account)
    {
        abort_unless($account->isOrmawa(), 404);
        $request->validateWithBag('akun' . $account->id, [
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [], ['password' => 'password baru']);

        $account->update(['password' => $request->password]);

        return redirect()->route('fmipa.ormawa.index')->with('success', 'Akun Ormawa Berhasil Diperbarui.');
    }

    /** "Himpunan Mahasiswa Data Science" -> "HMDS" */
    private function singkatan(string $nama): string
    {
        return collect(preg_split('/\s+/', trim($nama)))->map(fn ($k) => mb_strtoupper(mb_substr($k, 0, 1)))->take(6)->implode('');
    }
}
