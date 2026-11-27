# Mivent — Sistem Informasi Event Mahasiswa FMIPA Universitas Udayana

Mivent adalah aplikasi web untuk mencari, mendaftar, dan mengelola event kemahasiswaan (kepanitiaan & peserta) di lingkungan FMIPA Universitas Udayana.
Dibangun dengan **Laravel 13**, **Tailwind CSS v4**, dan JavaScript vanilla.

## Fitur Utama

| Role | Fitur |
|---|---|
| Tamu | Beranda, jelajahi & filter event, detail event, FAQ, registrasi + verifikasi email (OTP), login, lupa password (OTP) |
| Mahasiswa | Daftar sebagai Panitia (maks. 2 sie + berkas) / Peserta (berkas syarat), Event Diikuti (status, perbaikan berkas, bukti PDF, sertifikat), notifikasi, kelola akun & sertifikat profil |
| Admin Ormawa | Dashboard, ajukan event (3 langkah, Penyelenggara **dan** Peran wajib), revisi pengajuan, kelola pendaftar: seleksi berkas → wawancara (panitia) → selesai, ekspor Excel, unduh .zip berkas, upload sertifikat |
| Admin FMIPA | Dashboard, verifikasi event (setujui + SKP / revisi), **buat event sendiri yang langsung tayang**, kelola akun ormawa |

## Kebutuhan

- [Laragon](https://laragon.org/) (Windows) dengan **PHP ≥ 8.3**, **MySQL/MariaDB**, Composer
- **Node.js ≥ 20** dan npm
- Ekstensi PHP: `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `zip` (aktifkan lewat *Menu Laragon → PHP → Extensions*)

## Instalasi Lokal (Laragon)

1. Ekstrak folder `Mivent` ke `C:\laragon\www\Mivent`, lalu klik **Start All** di Laragon.
2. Buka **Terminal** Laragon di folder proyek:
   ```bash
   cd C:\laragon\www\Mivent
   composer install
   npm install
   copy .env.example .env
   php artisan key:generate
   ```
3. Buat database `mivent` (Laragon → *Database* / HeidiSQL, atau `mysql -u root -e "CREATE DATABASE mivent"`).
   Sesuaikan `DB_*` di `.env` bila user/password MySQL berbeda (default Laragon: `root` tanpa password).
4. Migrasi + data contoh, lalu tautkan folder upload:
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```
5. Build aset dan jalankan:
   ```bash
   npm run build
   php artisan serve
   ```
   Buka http://127.0.0.1:8000 (atau http://mivent.test bila memakai virtual host otomatis Laragon — samakan `APP_URL` di `.env`).
   Saat mengembangkan tampilan, gunakan `npm run dev` agar CSS/JS ter-*reload* otomatis.

## Akun Demo

Semua akun memakai password **`Mivent@123`**. Login bisa dengan NIM, ID (username), atau email.

| Role | Login |
|---|---|
| Mahasiswa | `2508561093` / `mahasiswa@mivent.test` |
| Mahasiswa 2 | `2508561001` / `ayu@mivent.test` |
| Admin Ormawa (BEM FMIPA) | `bemfmipa` / `bem@mivent.test` |
| Admin Ormawa (SIC) | `sic` / `sic@mivent.test` |
| Admin FMIPA | `fmipa@mivent.test` |

## Email (Kode OTP)

Default `MAIL_MAILER=log`: email tidak dikirim, isinya tercatat di `storage/logs/laravel.log`, dan selama `APP_DEBUG=true` kode OTP juga ditampilkan di halaman verifikasi agar mudah dicoba.
Untuk mengirim email sungguhan, isi `MAIL_MAILER=smtp` beserta `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` (mis. Gmail App Password atau Mailtrap). Jika pengiriman gagal, aplikasi tidak error — pengguna diminta menekan *Kirim Ulang*.

## Pengujian

```bash
php artisan test
```
Test memakai SQLite in-memory (lihat `phpunit.xml`), jadi tidak menyentuh database MySQL Anda. Diperlukan ekstensi `pdo_sqlite` (sudah aktif di Laragon).

## Struktur Singkat

- `app/Http/Controllers` — controller publik, auth, mahasiswa, dan `Admin/*`
- `resources/views` — Blade (`components/`, `partials/`, `admin/`, `auth/`, `errors/`, `pdf/`, `emails/`)
- `resources/js` — modul `ui.js`, `event-form.js`, `password.js`, `toast.js`, `domino.js`
- `resources/css/app.css` — tema warna & komponen Tailwind
- `tests/Feature` — feature test seluruh alur

## Pemecahan Masalah

- **Gambar upload tidak tampil** → jalankan `php artisan storage:link` dan pastikan `APP_URL` sesuai alamat yang dibuka.
- **Tampilan polos tanpa CSS** → jalankan `npm run build` (atau `npm run dev`).
- **`could not find driver`** → aktifkan ekstensi `pdo_mysql` di Laragon.
- **Halaman 419** → sesi kedaluwarsa; muat ulang halaman lalu kirim ulang formulir.
