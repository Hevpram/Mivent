<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Berkas yang dilampirkan mahasiswa saat mendaftar. */
class RegistrationFile extends Model
{
    protected $fillable = ['registration_id', 'syarat_id', 'label', 'path', 'salah', 'catatan'];
    protected $casts = ['salah' => 'boolean'];
}
