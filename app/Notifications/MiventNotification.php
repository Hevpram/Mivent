<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * Notifikasi sederhana yang disimpan di database (tabel notifications)
 * dan ditampilkan di halaman Notifikasi / ikon lonceng.
 */
class MiventNotification extends Notification
{
    public function __construct(public string $judul, public string $pesan, public ?string $url = null, public string $tipe = 'info') {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return ['judul' => $this->judul, 'pesan' => $this->pesan, 'url' => $this->url, 'tipe' => $this->tipe];
    }
}
