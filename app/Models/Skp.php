<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Referensi kategori SKP yang ditetapkan Admin FMIPA. */
class Skp extends Model
{
    protected $table = 'skp';
    protected $fillable = ['jenis_skp', 'poin_skp'];
}
