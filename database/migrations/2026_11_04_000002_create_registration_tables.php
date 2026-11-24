<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pendaftaran mahasiswa ke event, berkasnya, sertifikat event, dan notifikasi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('divisi_id')->nullable()->constrained('event_divisi')->nullOnDelete();  // sie pilihan 1 (panitia)
            $table->foreignId('divisi2_id')->nullable()->constrained('event_divisi')->nullOnDelete(); // sie pilihan 2 (opsional)
            $table->foreignId('divisi_diterima_id')->nullable()->constrained('event_divisi')->nullOnDelete(); // sie tempat bertugas
            $table->string('phone', 15);
            $table->text('alasan')->nullable(); // portofolio / alasan memilih divisi
            // diproses | lolos_berkas | diterima | ditolak
            $table->string('status', 20)->default('diproses')->index();
            // Keputusan sementara pada tahap seleksi saat ini (terima | tolak), diterapkan saat "Simpan Hasil"
            $table->string('keputusan', 10)->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'user_id']); // 1 mahasiswa hanya bisa daftar sekali per event
        });

        Schema::create('registration_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->foreignId('syarat_id')->nullable()->constrained('event_syarat')->nullOnDelete();
            $table->string('label', 150); // mis. "Sertifikat LKMM-TD FMIPA"
            $table->string('path');
            $table->boolean('salah')->default(false);      // ditandai "Berkas Salah" oleh penyelenggara
            $table->string('catatan', 255)->nullable();    // deskripsi singkat kesalahan
            $table->timestamps();
        });

        // Sertifikat kegiatan yang diunggah penyelenggara (1 event : banyak sertifikat)
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            // null = sertifikat umum untuk semua yang diterima; terisi = sertifikat khusus 1 pendaftar
            $table->foreignId('registration_id')->nullable()->constrained('registrations')->cascadeOnDelete();
            $table->string('judul', 150);
            $table->string('file_sertif');
            $table->timestamps();
        });

        // Tabel notifikasi bawaan Laravel (database channel)
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('sertifikat');
        Schema::dropIfExists('registration_files');
        Schema::dropIfExists('registrations');
    }
};
