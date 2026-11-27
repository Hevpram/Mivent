<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Referensi kategori event (Seminar, Pengabdian, Perlombaan, PKKMB, Lainnya). */
class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['nama'];
}
