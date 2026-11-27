{{-- Tabel verifikasi event (Figma: Dashboard - Admin FMIPA 7:19175 / Verifikasi Event 7:17158).
     prototype: Lihat Detail -> Verifikasi Event - Lihat Detail --}}
<div @class(['flex w-full flex-col gap-5', 'overflow-hidden rounded-[20px] bg-primary-50 pb-5 shadow-[0_0_15px_0_rgba(64,103,159,0.75)]' => empty($flat)])>
    <div class="flex w-full items-center gap-6 bg-primary-200 px-3 py-3 text-center text-base font-bold leading-5 text-white">
        <p class="w-[280px] shrink-0">Nama Event &amp; Tempat Pelaksanaan</p>
        <p class="w-[170px] shrink-0">Penyelenggara</p>
        <p class="w-[125px] shrink-0">Tanggal Pelaksanaan</p>
        <p class="w-[160px] shrink-0">Status</p>
        <p class="w-[150px] shrink-0">Aksi</p>
    </div>
    <div class="flex w-full flex-col">
        @forelse ($events as $e)
            <div data-domino class="flex w-full flex-col items-center gap-1 transition-colors duration-300 hover:bg-blue-50">
                <div class="flex w-full items-center gap-6 px-3 py-2">
                    <x-event-cell :event="$e" />
                    <p class="w-[170px] shrink-0 text-center text-sm leading-5 text-black">{{ $e->organisasi?->nama }}{{ $e->organisasi?->singkatan ? ' (' . $e->organisasi->singkatan . ')' : '' }}</p>
                    <p class="w-[125px] shrink-0 text-center text-sm leading-5 text-black">{{ $e->rentangTanggal() }}</p>
                    <span class="flex w-[160px] shrink-0 items-center justify-center whitespace-nowrap rounded-[3px] border-[0.5px] px-2 py-1 text-sm font-medium leading-5 {{ $e->statusBadge() }}">{{ $e->statusLabel() }}</span>
                    <div class="flex w-[150px] shrink-0 items-center justify-center">
                        <a href="{{ route('fmipa.verifikasi.show', $e) }}" class="flex h-[22px] w-[120px] items-center justify-center gap-1 rounded-[3px] bg-primary-200 text-sm font-medium leading-5 text-white transition-all duration-300 hover:bg-primary-150">
                            <img src="{{ asset('mivent_assets/icons/f-eye-white.svg') }}" alt="" class="h-[9.999px] w-[15.28px]"> Lihat Detail
                        </a>
                    </div>
                </div>
                <div class="h-px w-full bg-primary-200"></div>
            </div>
        @empty
            <p class="px-4 py-12 text-center text-sm text-gray-100">Belum ada pengajuan event.</p>
        @endforelse
    </div>
</div>
