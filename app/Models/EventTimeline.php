<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Satu tahapan pada timeline event. */
class EventTimeline extends Model
{
    protected $fillable = ['event_id', 'nama_tahapan', 'tanggal_mulai', 'tanggal_selesai', 'urutan'];
    protected $casts = ['tanggal_mulai' => 'date', 'tanggal_selesai' => 'date'];
}
