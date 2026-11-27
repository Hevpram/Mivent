<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Referensi organisasi penyelenggara (ormawa / unit fakultas). */
class Organisasi extends Model
{
    protected $table = 'organisasi';

    protected $fillable = [
        'nama', 'singkatan', 'is_fakultas', 'nama_ketua', 'email', 'nomor_telpon',
        'akun_sosmed', 'logo', 'sk_ormawa', 'tahun_kepengurusan', 'kampus',
    ];

    protected $casts = ['is_fakultas' => 'boolean'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function admin()
    {
        return $this->hasOne(User::class)->where('role', User::ORMAWA);
    }
}
