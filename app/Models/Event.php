<?php

namespace App\Models;

use App\Support\Files;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Event / kegiatan. Alur status:
 *  - Dibuat Admin Ormawa  -> status "menunggu" -> Admin FMIPA: "revisi" atau "disetujui" (tayang).
 *  - Dibuat Admin FMIPA   -> langsung "disetujui" (revisi dosen: tanpa verifikasi).
 * Tahap pelaksanaan (tahap): pendaftaran -> seleksi_berkas -> (seleksi_wawancara, panitia) -> selesai.
 */
class Event extends Model
{
    public const PERAN = ['panitia' => 'Panitia', 'peserta' => 'Peserta'];

    public const TAHAP = [
        'pendaftaran' => 'Sedang Berlangsung',
        'seleksi_berkas' => 'Seleksi Berkas',
        'seleksi_wawancara' => 'Seleksi Wawancara',
        'selesai' => 'Acara Selesai',
    ];

    public const KATEGORI_PENDAFTAR = [
        // kunci mengikuti form "Ajukan Event" asli
        'umum' => 'Umum Seluruh Masyarakat',
        'mhsFMIPA' => 'Seluruh Mahasiswa FMIPA',
        'mhsFi' => 'Seluruh Mahasiswa Fisika',
        'mhsKim' => 'Seluruh Mahasiswa Kimia',
        'mhsFa' => 'Seluruh Mahasiswa Farmasi',
        'mhsBio' => 'Seluruh Mahasiswa Biologi',
        'mhsTika' => 'Seluruh Mahasiswa Matematika',
        'mhsIf' => 'Seluruh Mahasiswa Informatika',
    ];

    protected $fillable = [
        'user_id', 'organisasi_id', 'kategori_id', 'skp_id', 'peran', 'judul', 'nama_kegiatan',
        'kategori_pendaftar', 'poster', 'narahubung_nama', 'narahubung_telp', 'narahubung2_nama',
        'narahubung2_telp', 'tanggal_mulai', 'tanggal_selesai', 'lokasi', 'deskripsi_singkat',
        'deskripsi', 'surat_izin_kegiatan', 'req_lkmm', 'req_pkkmb_fakultas', 'req_pkkmb_jurusan',
        'req_pengalaman', 'req_instagram', 'status', 'catatan_revisi', 'verified_by', 'verified_at', 'tahap',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'verified_at' => 'datetime',
        'req_lkmm' => 'boolean',
        'req_pkkmb_fakultas' => 'boolean',
        'req_pkkmb_jurusan' => 'boolean',
        'req_pengalaman' => 'boolean',
        'req_instagram' => 'boolean',
    ];

    // ---------- Relasi ----------
    public function creator() { return $this->belongsTo(User::class, 'user_id'); }
    public function organisasi() { return $this->belongsTo(Organisasi::class); }
    public function kategori() { return $this->belongsTo(Kategori::class); }
    public function skp() { return $this->belongsTo(Skp::class); }
    public function timelines() { return $this->hasMany(EventTimeline::class)->orderBy('urutan'); }
    public function syarat() { return $this->hasMany(EventSyarat::class)->orderBy('urutan'); }
    public function divisi() { return $this->hasMany(EventDivisi::class); }
    public function registrations() { return $this->hasMany(Registration::class); }
    public function sertifikat() { return $this->hasMany(Sertifikat::class); }

    // ---------- Scope ----------
    /** Hanya event yang sudah tayang publik. */
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'disetujui');
    }

    /** Event yang boleh dikelola user (ormawa: milik organisasinya, fmipa: buatan admin FMIPA). */
    public function scopeManagedBy(Builder $q, User $user): Builder
    {
        if ($user->isOrmawa()) {
            return $q->where('organisasi_id', $user->organisasi_id);
        }

        return $q->whereHas('creator', fn ($c) => $c->where('role', User::FMIPA));
    }

    public function canBeManagedBy(User $user): bool
    {
        if ($user->isOrmawa()) {
            return $this->organisasi_id === $user->organisasi_id;
        }

        return $user->isFmipa() && $this->creator?->isFmipa();
    }

    // ---------- Helper tampilan ----------
    public function isPanitia(): bool { return $this->peran === 'panitia'; }

    public function posterUrl(): string
    {
        return $this->poster ? Files::url($this->poster) : asset('mivent_assets/poster-default.jpg');
    }

    /** Pendaftaran dibuka bila tayang dan masih di tahap pendaftaran. */
    public function isOpen(): bool
    {
        return $this->status === 'disetujui' && $this->tahap === 'pendaftaran';
    }

    /** "21 - 27 Juli 2026" */
    public function rentangTanggal(): string
    {
        return self::formatRange($this->tanggal_mulai, $this->tanggal_selesai);
    }

    public static function formatRange($a, $b): string
    {
        if (! $a) {
            return '-';
        }
        if (! $b || $a->isSameDay($b)) {
            return $a->translatedFormat('j F Y');
        }
        if ($a->isSameMonth($b)) {
            return $a->format('j') . ' - ' . $b->translatedFormat('j F Y');
        }

        return $a->translatedFormat('j M Y') . ' - ' . $b->translatedFormat('j M Y');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu' => 'Belum Verifikasi',
            'revisi' => 'Dalam Perbaikan',
            default => 'Sudah Verifikasi',
        };
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'menunggu' => 'bg-blue-50 text-primary-200 border-primary-200',
            'revisi' => 'bg-yellow-50 text-yellow-100 border-yellow-100',
            default => 'bg-green-50 text-green-150 border-green-150',
        };
    }

    /** Label gabungan untuk tabel Kelola Event. */
    public function kelolaLabel(): string
    {
        if ($this->status === 'menunggu') return 'Menunggu Verifikasi';
        if ($this->status === 'revisi') return 'Verifikasi ditolak';

        return self::TAHAP[$this->tahap];
    }

    public function kelolaBadge(): string
    {
        if ($this->status === 'revisi') return 'bg-red-50 text-red-100 border-red-100';
        if ($this->status === 'menunggu') return 'bg-gray-50 text-gray-100 border-gray-100';

        return match ($this->tahap) {
            'pendaftaran' => 'bg-blue-50 text-primary-200 border-primary-200',
            'selesai' => 'bg-green-50 text-green-150 border-green-150',
            default => 'bg-yellow-50 text-yellow-100 border-yellow-100',
        };
    }

    /** Daftar dokumen wajib pendaftar panitia: [field_profil => label]. */
    public function requiredSertifikat(): array
    {
        $map = [
            'req_lkmm' => 'sertifikat_lkmm',
            'req_pkkmb_fakultas' => 'sertifikat_pkkmb_fakultas',
            'req_pkkmb_jurusan' => 'sertifikat_pkkmb_jurusan',
            'req_pengalaman' => 'sertifikat_pengalaman',
        ];
        $labels = User::sertifikatFields();
        $out = [];
        foreach ($map as $flag => $field) {
            if ($this->{$flag}) {
                $out[$field] = $labels[$field];
            }
        }

        return $out;
    }

    public function totalKuota(): int
    {
        return (int) $this->divisi->sum('kuota');
    }
}
