<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Email berisi kode 6 digit (verifikasi akun / lupa password). */
class KodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $kode, public string $judul, public string $nama) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Mivent - ' . $this->judul);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.kode');
    }
}
