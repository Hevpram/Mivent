<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Satu tabel users untuk 3 role: mahasiswa, admin_ormawa, admin_fmipa.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const MAHASISWA = 'mahasiswa';
    public const ORMAWA = 'admin_ormawa';
    public const FMIPA = 'admin_fmipa';

    protected $fillable = [
        'name', 'role', 'nim', 'username', 'email', 'phone', 'jurusan_id', 'angkatan',
        'organisasi_id', 'avatar', 'sertifikat_lkmm', 'sertifikat_pkkmb_jurusan',
        'sertifikat_pkkmb_fakultas', 'sertifikat_pengalaman', 'is_active', 'password',
    ];

    protected $hidden = ['password', 'remember_token', 'verification_code'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_expires_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function isMahasiswa(): bool { return $this->role === self::MAHASISWA; }
    public function isOrmawa(): bool { return $this->role === self::ORMAWA; }
    public function isFmipa(): bool { return $this->role === self::FMIPA; }

    /** Halaman utama setelah login, sesuai role. */
    public function homeRoute(): string
    {
        return $this->isMahasiswa() ? route('home') : route('dashboard');
    }

    /** Label role untuk ditampilkan di UI. */
    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ORMAWA => 'Admin ' . ($this->organisasi->singkatan ?? 'Ormawa'),
            self::FMIPA => 'Admin FMIPA',
            default => 'Mahasiswa',
        };
    }

    /** Peta field sertifikat profil => label (dipakai di registrasi & daftar event). */
    public static function sertifikatFields(): array
    {
        return [
            'sertifikat_lkmm' => 'Sertifikat LKMM-TD FMIPA',
            'sertifikat_pkkmb_fakultas' => 'Sertifikat PKKMB FMIPA',
            'sertifikat_pkkmb_jurusan' => 'Sertifikat Kaderisasi di Tingkat Program Studi',
            'sertifikat_pengalaman' => 'Sertifikat Pengalaman Kepanitiaan atau Organisasi',
        ];
    }

    /** URL foto profil (mahasiswa) / logo organisasi (ormawa); default: inisial nama. */
    public function avatarUrl(): string
    {
        $path = $this->isOrmawa() ? $this->organisasi?->logo : $this->avatar;
        if ($path) {
            return \App\Support\Files::url($path);
        }
        $inisial = e(mb_strtoupper(mb_substr($this->name ?? 'M', 0, 1)));
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect width="64" height="64" fill="#ABC9E9"/>'
            . '<text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="Poppins,sans-serif" font-size="28" font-weight="700" fill="#40679F">' . $inisial . '</text></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
