<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** File sertifikat kegiatan yang diunggah penyelenggara. */
class Sertifikat extends Model
{
    protected $table = 'sertifikat';
    protected $fillable = ['event_id', 'registration_id', 'judul', 'file_sertif'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
