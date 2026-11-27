<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel referensi (jurusan, organisasi) + tabel users.
 * Tabel referensi dibuat lebih dulu karena users punya foreign key ke sana.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Referensi program studi / jurusan di FMIPA
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        // Referensi organisasi penyelenggara (BEM, DPM, HMJ, SIC, Fakultas)
        Schema::create('organisasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->string('singkatan', 30)->nullable();
            $table->boolean('is_fakultas')->default(false); // true = unit fakultas (dipakai Admin FMIPA)
            $table->string('nama_ketua', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nomor_telpon', 15)->nullable();
            $table->string('akun_sosmed', 100)->nullable();   // Instagram
            $table->string('logo')->nullable();
            $table->string('sk_ormawa')->nullable();          // file SK kepengurusan
            $table->string('tahun_kepengurusan', 20)->nullable();
            $table->string('kampus', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // mahasiswa | admin_ormawa | admin_fmipa
            $table->string('role', 20)->default('mahasiswa')->index();
            $table->string('nim', 15)->nullable()->unique();      // login mahasiswa
            $table->string('username', 50)->nullable()->unique(); // "ID Ormawa"/ID admin untuk login admin
            $table->string('email')->unique();
            $table->string('phone', 15)->nullable();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->nullOnDelete();
            $table->unsignedSmallInteger('angkatan')->nullable();
            $table->foreignId('organisasi_id')->nullable()->constrained('organisasi')->nullOnDelete();
            $table->string('avatar')->nullable();
            // Berkas sertifikat milik mahasiswa (dipakai otomatis saat daftar event)
            $table->string('sertifikat_lkmm')->nullable();
            $table->string('sertifikat_pkkmb_jurusan')->nullable();   // ospek / kaderisasi prodi
            $table->string('sertifikat_pkkmb_fakultas')->nullable();
            $table->string('sertifikat_pengalaman')->nullable();
            // Kode verifikasi email (6 digit) untuk registrasi
            $table->string('verification_code')->nullable();
            $table->timestamp('verification_expires_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Dipakai fitur Lupa Password (token = hash kode OTP 6 digit)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('organisasi');
        Schema::dropIfExists('jurusan');
    }
};
