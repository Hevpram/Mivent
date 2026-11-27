<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\Organisasi;
use App\Models\Registration;
use App\Models\Skp;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/** Contoh event dari ormawa (berbagai status) dan event nasional buatan Admin FMIPA. */
class EventSeeder extends Seeder
{
    public function run(): void
    {
        $kat = Kategori::pluck('id', 'nama');
        $skp = Skp::pluck('id', 'jenis_skp');
        $org = Organisasi::pluck('id', 'singkatan');
        $bem = User::where('username', 'bemfmipa')->first();
        $sic = User::where('username', 'sic')->first();
        $fmipa = User::where('username', 'adminfmipa')->first();
        $fmipaVerifier = $fmipa->id;
        $d = fn (string $date) => Carbon::parse($date);

        $deskripsi = '<p>FMIPA Mengabdi 2026 merupakan program pengabdian kepada masyarakat yang diselenggarakan oleh BEM FMIPA sebagai wadah bagi civitas akademika untuk berkontribusi secara langsung kepada masyarakat.</p><p>Melalui program Open Recruitment Panitia FMIPA Mengabdi 2026 ini, BEM FMIPA membuka kesempatan bagi mahasiswa untuk menjadi bagian dari tim pelaksana.</p>';

        // 1) Event panitia BEM, sudah disetujui & membuka pendaftaran
        $mengabdi = $this->event([
            'user_id' => $bem->id, 'organisasi_id' => $org['BEM FMIPA'], 'kategori_id' => $kat['Pengabdian'],
            'skp_id' => $skp['Kegiatan Bidang Pengabdian Pada Masyarakat'], 'peran' => 'panitia',
            'judul' => 'Open Recruitment Panitia FMIPA Mengabdi 2026', 'nama_kegiatan' => 'FMIPA Mengabdi 2026',
            'tanggal_mulai' => $d('2026-12-21'), 'tanggal_selesai' => $d('2026-12-27'),
            'lokasi' => 'Desa Nyanglan, Klungkung, Provinsi Bali',
            'deskripsi_singkat' => 'Gabung Panitia FMIPA Mengabdi 2026, Berkembang, Berpengalaman, Dan Berkontribusi',
            'deskripsi' => $deskripsi, 'req_lkmm' => true, 'req_pkkmb_fakultas' => true,
            'status' => 'disetujui', 'verified_by' => $fmipaVerifier, 'verified_at' => now(),
        ], [
            ['Open Recruitment Panitia', '2026-11-01', '2026-11-30'],
            ['Pengumuman Jadwal Wawancara', '2026-12-02', '2026-12-02'],
            ['Sesi Wawancara', '2026-12-04', '2026-12-05'],
            ['Pengumuman Panitia Terpilih', '2026-12-08', '2026-12-08'],
        ], [
            ['Mahasiswa/i aktif di Fakultas Matematika dan Ilmu Pengetahuan Alam', false],
            ['Memiliki sertifikat PKKMB, LKMM-TD FMIPA, kaderisasi di tingkat Program Studi', false],
            ['Telah mengikuti akun Instagram @fmipamengabdiunud', false],
        ], [
            ['Sie Acara', 4, 'Bertanggung jawab menyusun dan mengawal seluruh rangkaian acara.'],
            ['Sie Desain, Dekorasi dan Dokumentasi', 12, 'Merancang kebutuhan visual acara dan dokumentasi.'],
            ['Sie Penggalian Dana, Konsumsi, Kerohanian dan Kesehatan', 9, 'Mengelola pendanaan, konsumsi dan kesehatan.'],
        ]);

        // 2) Seminar SIC untuk peserta, disetujui
        $this->event([
            'user_id' => $sic->id, 'organisasi_id' => $org['SIC'], 'kategori_id' => $kat['Seminar'],
            'skp_id' => $skp['Kegiatan Bidang Penalaran dan Keilmuan'], 'peran' => 'peserta',
            'judul' => 'Seminar Inovasi Sains & Teknologi 2026', 'nama_kegiatan' => 'SIC Innovation Talk 2026',
            'tanggal_mulai' => $d('2026-12-12'), 'tanggal_selesai' => $d('2026-12-12'),
            'lokasi' => 'Gedung FMIPA, Kampus Bukit Jimbaran',
            'deskripsi_singkat' => 'Seminar inovasi sains bersama praktisi dan peneliti muda FMIPA.',
            'deskripsi' => '<p>Seminar terbuka untuk seluruh mahasiswa FMIPA mengenai inovasi sains dan teknologi.</p>',
            'status' => 'disetujui', 'verified_by' => $fmipaVerifier, 'verified_at' => now(),
        ], [
            ['Pendaftaran Peserta', '2026-11-01', '2026-12-08'],
            ['Pelaksanaan Seminar', '2026-12-12', '2026-12-12'],
        ], [
            ['Mahasiswa aktif FMIPA Universitas Udayana', false],
            ['Mengunggah Kartu Tanda Mahasiswa (KTM)', true],
        ]);

        // 3) Pengajuan SIC yang masih menunggu verifikasi
        $this->event([
            'user_id' => $sic->id, 'organisasi_id' => $org['SIC'], 'kategori_id' => $kat['Perlombaan'],
            'peran' => 'panitia', 'judul' => 'Open Recruitment Panitia SIC Science Fair 2027',
            'nama_kegiatan' => 'SIC Science Fair 2027', 'tanggal_mulai' => $d('2027-02-10'), 'tanggal_selesai' => $d('2027-02-12'),
            'lokasi' => 'Auditorium Widya Sabha, Jimbaran',
            'deskripsi_singkat' => 'Jadilah bagian dari panitia pameran sains terbesar di FMIPA.',
            'deskripsi' => '<p>Science Fair adalah pameran karya ilmiah mahasiswa FMIPA.</p>',
            'req_pengalaman' => true, 'status' => 'menunggu',
        ], [
            ['Open Recruitment Panitia', '2026-12-01', '2026-12-15'],
        ], [
            ['Mahasiswa aktif FMIPA', false],
        ], [
            ['Sie Acara', 6, 'Menyusun rundown dan teknis acara.'],
        ]);

        // 4) Pengajuan BEM yang dikembalikan untuk revisi
        $this->event([
            'user_id' => $bem->id, 'organisasi_id' => $org['BEM FMIPA'], 'kategori_id' => $kat['Lainnya'],
            'peran' => 'peserta', 'judul' => 'Malam Keakraban FMIPA 2026', 'nama_kegiatan' => 'Makrab FMIPA 2026',
            'tanggal_mulai' => $d('2027-01-20'), 'tanggal_selesai' => $d('2027-01-21'),
            'lokasi' => 'Bedugul, Tabanan', 'deskripsi_singkat' => 'Malam keakraban mahasiswa baru FMIPA.',
            'deskripsi' => '<p>Malam keakraban mahasiswa FMIPA.</p>', 'status' => 'revisi',
            'catatan_revisi' => 'Mohon lampirkan SIK yang sudah ditandatangani Wakil Dekan III.',
        ], [
            ['Pendaftaran Peserta', '2026-12-01', '2027-01-10'],
        ], [
            ['Mahasiswa aktif FMIPA', false],
        ]);

        // 5) Program nasional yang diunggah langsung Admin FMIPA (tanpa verifikasi)
        $nasional = [
            ['Program Kreativitas Mahasiswa (PKM) 2027', 'PKM 2027', 'Perlombaan', 'Kegiatan Bidang Penalaran dan Keilmuan',
                'Sosialisasi & pendaftaran tim PKM tingkat fakultas menuju PIMNAS.', '2027-01-05', '2027-03-31'],
            ['Satria Data 2027', 'Satria Data 2027', 'Perlombaan', 'Kegiatan Bidang Penalaran dan Keilmuan',
                'Kompetisi statistika & sains data nasional dari Puspresnas.', '2027-02-01', '2027-06-30'],
            ['Program Pembinaan Mahasiswa Wirausaha (P2MW) 2027', 'P2MW 2027', 'Lainnya', 'Kegiatan Bidang Kewirausahaan',
                'Pendanaan dan pembinaan usaha mahasiswa dari Kemendikbudristek.', '2027-01-15', '2027-04-15'],
        ];
        foreach ($nasional as [$judul, $nama, $k, $s, $singkat, $mulai, $selesai]) {
            $this->event([
                'user_id' => $fmipa->id, 'organisasi_id' => $org['FMIPA'], 'kategori_id' => $kat[$k], 'skp_id' => $skp[$s],
                'peran' => 'peserta', 'judul' => $judul, 'nama_kegiatan' => $nama,
                'tanggal_mulai' => $d($mulai), 'tanggal_selesai' => $d($selesai), 'lokasi' => 'FMIPA Universitas Udayana',
                'deskripsi_singkat' => $singkat, 'deskripsi' => "<p>{$singkat}</p><p>Daftarkan diri kamu melalui Mivent untuk didampingi fakultas.</p>",
                'status' => 'disetujui', 'verified_by' => $fmipa->id, 'verified_at' => now(),
            ], [
                ['Pendaftaran Internal Fakultas', '2026-11-01', $mulai],
                ['Pelaksanaan Program', $mulai, $selesai],
            ], [
                ['Mahasiswa aktif FMIPA Universitas Udayana', false],
                ['Mengunggah proposal / ide singkat (PDF)', true],
            ]);
        }

        // Contoh pendaftaran mahasiswa demo ke FMIPA Mengabdi
        $mhs = User::where('nim', '2508561001')->first();
        Registration::firstOrCreate(['event_id' => $mengabdi->id, 'user_id' => $mhs->id], [
            'divisi_id' => $mengabdi->divisi()->first()->id, 'phone' => $mhs->phone,
            'alasan' => 'Saya ingin berkontribusi untuk masyarakat.', 'status' => 'diproses',
        ]);
    }

    /** Helper membuat event beserta timeline, syarat, dan divisi. */
    private function event(array $data, array $timelines, array $syarat, array $divisi = []): Event
    {
        $data += [
            'kategori_pendaftar' => 'umum', 'poster' => 'mivent_assets/poster-default.jpg',
            'narahubung_nama' => 'Hevan', 'narahubung_telp' => '089111222333',
        ];
        $event = Event::updateOrCreate(['judul' => $data['judul']], $data);
        $event->timelines()->delete();
        $event->syarat()->delete();
        $event->divisi()->delete();

        foreach ($timelines as $i => [$nama, $mulai, $selesai]) {
            $event->timelines()->create(['nama_tahapan' => $nama, 'tanggal_mulai' => $mulai, 'tanggal_selesai' => $selesai, 'urutan' => $i]);
        }
        foreach ($syarat as $i => [$desk, $berkas]) {
            $event->syarat()->create(['deskripsi' => $desk, 'butuh_berkas' => $berkas, 'urutan' => $i]);
        }
        foreach ($divisi as [$nama, $kuota, $desk]) {
            $event->divisi()->create(['nama' => $nama, 'kuota' => $kuota, 'deskripsi' => $desk]);
        }

        return $event;
    }
}
