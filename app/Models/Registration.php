<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Pendaftaran mahasiswa ke sebuah event. */
class Registration extends Model
{
    public const STATUS = [
        'diproses' => 'Diproses',
        'lolos_berkas' => 'Lolos Berkas',
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
    ];

    protected $fillable = ['event_id', 'user_id', 'divisi_id', 'divisi2_id', 'divisi_diterima_id', 'phone', 'alasan', 'status', 'keputusan'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi()
    {
        return $this->belongsTo(EventDivisi::class, 'divisi_id');
    }

    public function divisi2()
    {
        return $this->belongsTo(EventDivisi::class, 'divisi2_id');
    }

    public function divisiDiterima()
    {
        return $this->belongsTo(EventDivisi::class, 'divisi_diterima_id');
    }

    /** Sertifikat milik pendaftar ini: sertifikat khusus, atau sertifikat umum event. */
    public function sertifikat(): ?Sertifikat
    {
        return Sertifikat::where('event_id', $this->event_id)
            ->where(fn ($q) => $q->where('registration_id', $this->id)->orWhereNull('registration_id'))
            ->orderByDesc('registration_id')->latest()->first();
    }

    public function hasBerkasSalah(): bool
    {
        return $this->files->contains('salah', true);
    }

    public function files()
    {
        return $this->hasMany(RegistrationFile::class);
    }

    /** Label status untuk mahasiswa; diterima + event selesai = "Selesai". */
    public function statusLabel(): string
    {
        if ($this->status === 'diterima' && $this->event?->tahap === 'selesai') {
            return 'Acara Selesai';
        }
        if ($this->status === 'diproses' && $this->event && $this->event->tahap !== 'pendaftaran') {
            return 'Proses Seleksi';
        }
        if ($this->status === 'diproses') {
            return 'Sedang Berlangsung';
        }
        if ($this->status === 'ditolak') {
            return 'Anda Ditolak';
        }

        return self::STATUS[$this->status] ?? $this->status;
    }

    /** Kelas badge sesuai warna tema. */
    public function badgeClass(): string
    {
        return match ($this->statusLabel()) {
            'Diterima', 'Acara Selesai' => 'bg-green-50 text-green-150 border-green-150',
            'Anda Ditolak' => 'bg-red-50 text-red-100 border-red-100',
            'Lolos Berkas', 'Proses Seleksi' => 'bg-yellow-50 text-yellow-100 border-yellow-100',
            default => 'bg-blue-50 text-primary-200 border-primary-200',
        };
    }
}
