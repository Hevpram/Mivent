{{-- Kartu ringkasan event: poster + info (Figma: Detail Event 7:2861). Variabel: $event --}}
@php
    $infoItems = [
        ['info-org', 'h-[20.755px] w-[21px]', $event->organisasi?->nama],
        ['info-lokasi', 'h-[22.946px] w-[13.979px]', $event->lokasi],
        ['info-lokasi', 'h-[22.946px] w-[13.979px]', $event->judul],
        ['info-tanggal', 'size-5', $event->rentangTanggal()],
        ['info-tanggal', 'size-5', 'Terbuka Untuk ' . (\App\Models\Event::KATEGORI_PENDAFTAR[$event->kategori_pendaftar] ?? 'Umum')],
    ];
@endphp
<div data-domino class="flex w-full items-center rounded-[20px] bg-primary-50 px-8 py-4 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
    <div class="flex w-full items-center gap-8">
        <img src="{{ $event->posterUrl() }}" alt="Poster {{ $event->nama_kegiatan }}" class="h-[476px] w-[475px] shrink-0 rounded-[10px] object-cover">
        <div class="flex min-w-0 flex-1 flex-col items-start gap-4">
            <h1 class="w-[545px] text-4xl font-bold uppercase text-primary-200">{{ $event->nama_kegiatan }}</h1>
            <p class="w-full text-base capitalize text-black">{{ $event->deskripsi_singkat }}</p>
            @foreach ($infoItems as [$ikon, $ukuran, $teks])
                <div class="flex w-full items-center gap-3 rounded-[10px] bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:translate-x-1">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-[18px] bg-primary-100">
                        <img src="{{ asset('mivent_assets/icons/'.$ikon.'.svg') }}" alt="" class="{{ $ukuran }}">
                    </span>
                    <span class="min-w-0 flex-1 text-base text-black">{{ $teks }}</span>
                </div>
            @endforeach
            @if ($event->status !== 'disetujui')
                <p class="w-full rounded-[10px] border border-yellow-100 bg-yellow-50 px-4 py-2 text-sm text-black"><x-ui-icon name="info" class="mr-1 inline h-4 w-4" /> Event ini belum dipublikasikan ({{ $event->kelolaLabel() }}).</p>
            @endif
        </div>
    </div>
</div>
