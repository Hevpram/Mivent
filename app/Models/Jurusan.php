<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Referensi program studi FMIPA. */
class Jurusan extends Model
{
    protected $table = 'jurusan';
    protected $fillable = ['nama'];
}
