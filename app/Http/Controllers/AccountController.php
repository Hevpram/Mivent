<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\User;
use App\Support\Files;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Pengaturan Akun.
 *  - Mahasiswa    : foto profil, informasi akun, ganti password, ganti berkas sertifikat.
 *  - Admin Ormawa : logo organisasi, data pokok organisasi, SK ormawa, ganti password.
 *  - Admin FMIPA  : nama/email/telepon + ganti password.
 */
class AccountController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load('organisasi', 'jurusan');
        $view = $user->isMahasiswa() ? 'account.mahasiswa' : 'account.admin';

        return view($view, ['user' => $user, 'jurusan' => Jurusan::orderBy('nama')->get()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $phone = ['required', 'regex:/^[0-9]{10,13}$/'];
        $email = ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)];
        $msg = ['phone.regex' => 'Nomor telepon harus 10-13 digit angka.'];
        $names = ['name' => 'nama lengkap', 'phone' => 'nomor telepon', 'avatar' => 'foto profil', 'logo' => 'logo', 'sk_ormawa' => 'SK ormawa'];

        try {
            if ($user->isMahasiswa()) {
                $file = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
                $data = $request->validate([
                    'name' => 'sometimes|required|string|max:100',
                    'phone' => 'sometimes|' . implode('|', $phone),
                    'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:500',
                    'sertifikat_lkmm' => $file, 'sertifikat_pkkmb_jurusan' => $file,
                    'sertifikat_pkkmb_fakultas' => $file, 'sertifikat_pengalaman' => $file,
                ], $msg, $names);

                // Ganti berkas: simpan file baru. File lama TIDAK dihapus karena
                // mungkin masih dipakai sebagai lampiran pendaftaran event sebelumnya.
                foreach (array_keys(User::sertifikatFields()) as $field) {
                    unset($data[$field]);
                    if ($request->hasFile($field)) {
                        $data[$field] = Files::store($request->file($field), 'sertifikat');
                    }
                }
                unset($data['avatar']);
                if ($request->hasFile('avatar')) {
                    Files::delete($user->avatar);
                    $data['avatar'] = Files::store($request->file('avatar'), 'avatar');
                }
                $user->update($data);
            } elseif ($user->isOrmawa()) {
                $data = $request->validate([
                    'nama_ketua' => 'sometimes|required|string|max:100',
                    'phone' => 'sometimes|' . implode('|', $phone),
                    'email' => array_merge(['sometimes'], $email),
                    'akun_sosmed' => 'nullable|string|max:100',
                    'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:500',
                    'sk_ormawa' => 'nullable|file|mimes:pdf|max:5120',
                ], $msg, $names + ['nama_ketua' => 'nama ketua umum']);

                $org = $user->organisasi;
                $orgData = collect($data)->only(['nama_ketua', 'akun_sosmed'])->all();
                if (isset($data['phone'])) $orgData['nomor_telpon'] = $data['phone'];
                if (isset($data['email'])) $orgData['email'] = $data['email'];
                if ($request->hasFile('logo')) {
                    Files::delete($org->logo);
                    $orgData['logo'] = Files::store($request->file('logo'), 'logo');
                }
                if ($request->hasFile('sk_ormawa')) {
                    Files::delete($org->sk_ormawa);
                    $orgData['sk_ormawa'] = Files::store($request->file('sk_ormawa'), 'sk');
                }
                $org->update($orgData);
                $user->update(collect($data)->only(['phone', 'email'])->all());
            } else {
                $data = $request->validate(['name' => 'required|string|max:100', 'email' => $email, 'phone' => $phone], $msg, $names);
                $user->update($data);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Gagal menyimpan perubahan. Silakan coba lagi.');
        }

        return back()->with('success', 'Profil Berhasil Diperbarui.');
    }

    public function password(Request $request)
    {
        $request->validateWithBag('password', [
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [], ['current_password' => 'password sekarang', 'password' => 'password baru']);

        $request->user()->update(['password' => $request->password]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
