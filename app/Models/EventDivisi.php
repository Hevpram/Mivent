<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Kebutuhan sie / divisi pada event panitia. */
class EventDivisi extends Model
{
    protected $table = 'event_divisi';
    protected $fillable = ['event_id', 'nama', 'kuota', 'deskripsi'];
}
