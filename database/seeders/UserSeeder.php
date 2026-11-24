<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Akun demo (password semua: Mivent@123)
 *  - Mahasiswa    : NIM 2508561093
 *  - Admin Ormawa : ID "sic" (Student Innovation Centre) dan "bemfmipa" (BEM FMIPA)
 *  - Admin FMIPA  : ID "adminfmipa"
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'Mivent@123';

        User::updateOrCreate(['nim' => '2508561093'], [
            'name' => 'I Made Andika Wiranatha',
            'role' => User::MAHASISWA,
            'email' => 'mahasiswa@mivent.test',
            'phone' => '081353679223',
            'jurusan_id' => Jurusan::where('nama', 'Informatika')->value('id'),
            'angkatan' => 2025,
            'password' => $password,
        ])->forceFill(['email_verified_at' => now()])->save();

        User::updateOrCreate(['nim' => '2508561001'], [
            'name' => 'Ni Kadek Ayu Lestari',
            'role' => User::MAHASISWA,
            'email' => 'ayu@mivent.test',
            'phone' => '081234567890',
            'jurusan_id' => Jurusan::where('nama', 'Biologi')->value('id'),
            'angkatan' => 2024,
            'password' => $password,
        ])->forceFill(['email_verified_at' => now()])->save();

        $ormawa = [
            ['sic', 'Student Innovation Centre', 'Admin SIC', 'sic@mivent.test', 'Putu Gede Pradnyana', '@sicfmipaunud'],
            ['bemfmipa', 'Badan Eksekutif Mahasiswa FMIPA', 'Admin BEM FMIPA', 'bem@mivent.test', 'I Komang Adi Saputra', '@bemfmipaunud'],
        ];
        foreach ($ormawa as [$username, $orgName, $name, $email, $ketua, $ig]) {
            $org = Organisasi::where('nama', $orgName)->first();
            $org->update(['nama_ketua' => $ketua, 'email' => $email, 'nomor_telpon' => '081999888777', 'akun_sosmed' => $ig]);

            User::updateOrCreate(['username' => $username], [
                'name' => $name,
                'role' => User::ORMAWA,
                'email' => $email,
                'phone' => '081999888777',
                'organisasi_id' => $org->id,
                'password' => $password,
            ])->forceFill(['email_verified_at' => now()])->save();
        }

        User::updateOrCreate(['username' => 'adminfmipa'], [
            'name' => 'Admin FMIPA',
            'role' => User::FMIPA,
            'email' => 'fmipa@mivent.test',
            'phone' => '0361701954',
            'organisasi_id' => Organisasi::where('is_fakultas', true)->value('id'),
            'password' => $password,
        ])->forceFill(['email_verified_at' => now()])->save();
    }
}
