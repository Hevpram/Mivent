<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel inti event: kategori, skp, events, timeline (tahapan), syarat, divisi (sie).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique(); // Seminar, Pengabdian, Perlombaan, PKKMB, Lainnya
            $table->timestamps();
        });

        // Kategori SKP ditetapkan Admin FMIPA saat verifikasi
        Schema::create('skp', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_skp', 100)->unique();
            $table->unsignedInteger('poin_skp')->default(0);
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();        // pembuat
            $table->foreignId('organisasi_id')->constrained('organisasi')->restrictOnDelete(); // penyelenggara
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->foreignId('skp_id')->nullable()->constrained('skp')->nullOnDelete();
            $table->string('peran', 10);                 // panitia | peserta
            $table->string('judul', 150);
            $table->string('nama_kegiatan', 150);
            $table->string('kategori_pendaftar', 50);    // umum | internal
            $table->string('poster')->nullable();
            $table->string('narahubung_nama', 100);
            $table->string('narahubung_telp', 15);
            $table->string('narahubung2_nama', 100)->nullable();
            $table->string('narahubung2_telp', 15)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('lokasi', 150);
            $table->string('deskripsi_singkat', 100);
            $table->text('deskripsi');                   // HTML yang sudah disanitasi (Purifier)
            $table->string('surat_izin_kegiatan')->nullable(); // file SIK
            // Dokumen wajib dari pendaftar panitia (checkbox di form ajukan)
            $table->boolean('req_lkmm')->default(false);
            $table->boolean('req_pkkmb_fakultas')->default(false);
            $table->boolean('req_pkkmb_jurusan')->default(false);
            $table->boolean('req_pengalaman')->default(false);
            $table->boolean('req_instagram')->default(false);
            // Status verifikasi: menunggu | revisi | disetujui
            $table->string('status', 20)->default('menunggu')->index();
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            // Tahap pelaksanaan: pendaftaran | seleksi_berkas | seleksi_wawancara | selesai
            $table->string('tahap', 20)->default('pendaftaran')->index();
            $table->timestamps();
        });

        // Timeline / tahapan event (entitas "Tahapan" di SDD)
        Schema::create('event_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('nama_tahapan', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });

        // Syarat pendaftaran. butuh_berkas = pendaftar (peserta) wajib unggah berkas untuk syarat ini
        Schema::create('event_syarat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('deskripsi', 255);
            $table->boolean('butuh_berkas')->default(false);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });

        // Kebutuhan sie / divisi (khusus event panitia)
        Schema::create('event_divisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->unsignedSmallInteger('kuota')->default(1);
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_divisi');
        Schema::dropIfExists('event_syarat');
        Schema::dropIfExists('event_timelines');
        Schema::dropIfExists('events');
        Schema::dropIfExists('skp');
        Schema::dropIfExists('kategori');
    }
};
