{{-- Tabel event milik pengelola (Figma: Dashboard - Admin Ormawa 7:12880 "Event Saya" / Kelola Event 7:13044).
     prototype: Lihat -> Detail event, Kelola -> Kelola Event (detail) --}}
<div @class(['flex w-full flex-col gap-5', 'overflow-hidden rounded-[20px] bg-primary-50 pb-5 shadow-[0_0_15px_0_rgba(64,103,159,0.75)]' => empty($flat)])>
    <div class="flex w-full items-center gap-6 bg-primary-200 px-3 py-3 text-center text-base font-bold leading-5 text-white">
        <p class="w-[280px] shrink-0">Nama Event &amp; Tempat Pelaksanaan</p>
        <p class="w-[135px] shrink-0">Tanggal Pelaksanaan</p>
        <p class="w-[160px] shrink-0">Jenis &amp; Jumlah Pendaftar</p>
        <p class="w-[160px] shrink-0">Status</p>
        <p class="w-[150px] shrink-0">Aksi</p>
    </div>
    <div class="flex w-full flex-col">
        @forelse ($events as $e)
            <div data-domino class="flex w-full flex-col items-center gap-1 transition-colors duration-300 hover:bg-blue-50">
                <div class="flex w-full items-center gap-6 px-3 py-2">
                    <x-event-cell :event="$e" />
                    <p class="w-[135px] shrink-0 text-center text-sm leading-5 text-black">{{ $e->rentangTanggal() }}</p>
                    <p class="w-[160px] shrink-0 text-center text-sm leading-5 text-black"><span class="font-semibold">{{ $e->registrations_count }}{{ $e->isPanitia() && $e->totalKuota() ? '/' . $e->totalKuota() : '' }}</span> {{ $e->isPanitia() ? 'Panitia' : 'Peserta' }} Terdaftar</p>
                    <span class="flex w-[160px] shrink-0 items-center justify-center rounded-[3px] border-[0.5px] px-2 py-1 whitespace-nowrap text-center text-sm font-medium leading-5 {{ $e->kelolaBadge() }}">{{ $e->kelolaLabel() }}</span>
                    <div class="flex w-[150px] shrink-0 items-center justify-center gap-[5px]">
                        @unless ($e->status === 'revisi')<a href="{{ route('events.show', $e) }}" class="flex h-[22px] w-[70px] items-center justify-center rounded-[3px] border border-primary-200 text-sm font-medium leading-5 text-primary-200 transition-all duration-300 hover:bg-primary-200 hover:text-white">Lihat</a>@endunless
                        @if ($e->status === 'revisi')
                            <a href="{{ route('manage.edit', $e) }}" class="flex h-[22px] w-[145px] items-center justify-center rounded-[3px] bg-primary-200 text-sm font-medium leading-5 text-white transition-all duration-300 hover:bg-primary-150">Perbaiki Event</a>
                        @else
                            <a href="{{ route('manage.show', $e) }}" class="flex h-[22px] w-[70px] items-center justify-center rounded-[3px] bg-primary-200 text-sm font-medium leading-5 text-white transition-all duration-300 hover:bg-primary-150">Kelola</a>
                        @endif
                    </div>
                </div>
                @if (! $loop->last || ! empty($flat))<div class="h-px w-full bg-primary-200"></div>@endif
            </div>
        @empty
            <p class="px-4 py-12 text-center text-sm text-gray-100">Belum ada event. <a href="{{ route('manage.create') }}" class="font-semibold text-primary-200 hover:underline">Ajukan event pertama</a></p>
        @endforelse
    </div>
</div>
