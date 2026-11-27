{{-- Pembungkus <x-event-card> untuk model Event (dipakai di beranda & jelajahi) --}}
@props(['event', 'fluid' => false])
<x-event-card data-domino
    :fluid="$fluid"
    :image="$event->posterUrl()"
    :kategori="$event->kategori?->nama ?? \App\Models\Event::PERAN[$event->peran]"
    :penyelenggara="$event->organisasi?->nama"
    :judul="$event->nama_kegiatan"
    :subjudul="$event->judul"
    :lokasi="$event->lokasi"
    :tanggal="$event->rentangTanggal()"
    :deskripsi="$event->deskripsi_singkat"
    :link="route('events.show', $event)" />
