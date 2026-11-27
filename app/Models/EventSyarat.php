<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Satu syarat pendaftaran event. */
class EventSyarat extends Model
{
    protected $table = 'event_syarat';
    protected $fillable = ['event_id', 'deskripsi', 'butuh_berkas', 'urutan'];
    protected $casts = ['butuh_berkas' => 'boolean'];
}
