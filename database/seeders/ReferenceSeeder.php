<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\Organisasi;
use App\Models\Skp;
use Illuminate\Database\Seeder;

/** Data referensi sesuai SKPL: prodi FMIPA, ormawa, kategori event, kategori SKP. */
class ReferenceSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Matematika', 'Fisika', 'Kimia', 'Biologi', 'Farmasi', 'Informatika'] as $nama) {
            Jurusan::firstOrCreate(['nama' => $nama]);
        }

        $orgs = [
            ['FMIPA Universitas Udayana', 'FMIPA', true],
            ['Badan Eksekutif Mahasiswa FMIPA', 'BEM FMIPA', false],
            ['Dewan Perwakilan Mahasiswa FMIPA', 'DPM FMIPA', false],
            ['Himpunan Mahasiswa Fisika', 'HIMAFI', false],
            ['Himpunan Mahasiswa Kimia', 'HMK', false],
            ['Himpunan Mahasiswa Biologi', 'HIMABIO', false],
            ['Himpunan Mahasiswa Farmasi', 'HMF', false],
            ['Himpunan Mahasiswa Matematika', 'HIMATIKA', false],
            ['Himpunan Mahasiswa Informatika', 'HMIF', false],
            ['Student Innovation Centre', 'SIC', false],
        ];
        foreach ($orgs as [$nama, $singkatan, $fakultas]) {
            Organisasi::firstOrCreate(['nama' => $nama], [
                'singkatan' => $singkatan,
                'is_fakultas' => $fakultas,
                'tahun_kepengurusan' => '2025/2026',
                'kampus' => 'Kampus Bukit Jimbaran, Badung',
            ]);
        }

        foreach (['Seminar', 'Pengabdian', 'Perlombaan', 'PKKMB', 'Lainnya'] as $nama) {
            Kategori::firstOrCreate(['nama' => $nama]);
        }

        $skp = [
            ['Kegiatan Bidang Penalaran dan Keilmuan', 3],
            ['Kegiatan Bidang Pengabdian Pada Masyarakat', 3],
            ['Kegiatan Bidang Minat dan Bakat', 2],
            ['Kegiatan Bidang Organisasi dan Kepemimpinan', 4],
            ['Kegiatan Wajib Universitas/Fakultas', 5],
            ['Kegiatan Bidang Kewirausahaan', 3],
        ];
        foreach ($skp as [$jenis, $poin]) {
            Skp::firstOrCreate(['jenis_skp' => $jenis], ['poin_skp' => $poin]);
        }
    }
}
