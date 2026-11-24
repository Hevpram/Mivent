{{-- Riwayat Event / Event Diikuti (Figma: Event Di Ikuti 7:3446) --}}
@php $statusOpsi = ['Sedang Berlangsung', 'Proses Seleksi', 'Lolos Berkas', 'Diterima', 'Anda Ditolak', 'Acara Selesai']; @endphp
<x-layout title="Event Diikuti">
    <section class="relative isolate flex justify-center px-14 pb-[46px] pt-20">
        <div class="pointer-events-none absolute inset-x-0 top-[634px] -z-10">
            <div class="h-[100px] bg-gradient-to-b from-background to-white"></div>
            <div class="h-[494px] bg-white"></div>
            <div class="h-[100px] bg-gradient-to-b from-white to-background"></div>
        </div>
        <div class="flex flex-col items-center gap-[60px]">
            <div class="flex flex-col items-center gap-2 text-center">
                <h1 data-domino class="text-4xl font-bold text-black">Riwayat <span class="text-gradient-mivent">Event</span></h1>
                <p data-domino class="h-[60px] w-[868px] text-lg text-black">Pantau riwayat event yang pernah kamu ikuti, mulai dari kegiatan yang sedang berlangsung hingga yang telah selesai.</p>
            </div>

            <div class="flex w-[1329px] flex-col items-center gap-10">
                <form data-domino method="GET" class="flex w-full items-center gap-6 rounded-[20px] bg-primary-50 px-8 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
                    <label class="flex w-[1051px] items-center gap-4 rounded-[10px] bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)]">
                        <img src="{{ asset('mivent_assets/icons/search.svg') }}" alt="" class="size-[29px]">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama kegiatan atau penyelenggara" class="min-w-0 flex-1 bg-transparent text-sm font-light text-black placeholder:text-gray-100 focus:outline-none">
                    </label>
                    <label class="relative flex h-[45px] w-[189px] items-center rounded-[10px] bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)]">
                        <img src="{{ asset('mivent_assets/icons/status-filter.svg') }}" alt="" class="pointer-events-none absolute left-3 h-2 w-[21px]">
                        <select name="status" data-autosubmit-change aria-label="Filter status" class="w-full cursor-pointer appearance-none bg-transparent pl-[37px] pr-4 text-sm font-light {{ request('status') ? 'text-black' : 'text-gray-100' }} focus:outline-none">
                            <option value="">Pilih Status</option>
                            @foreach ($statusOpsi as $s)<option @selected(request('status') === $s)>{{ $s }}</option>@endforeach
                        </select>
                        <img src="{{ asset('mivent_assets/icons/select-chevron.svg') }}" alt="" class="pointer-events-none absolute right-3 rotate-180 h-[4.769px] w-[10.731px]">
                    </label>
                </form>

                <div data-domino class="flex w-full flex-col gap-5 overflow-clip rounded-[20px] bg-primary-50 pb-5 shadow-[0_0_15px_rgba(64,103,159,0.75)]">
                    <div class="flex items-center gap-6 bg-primary-200 px-3 py-3 text-center text-base font-bold leading-5 text-white">
                        <p class="w-[300px]">Nama Event &amp; Lembaga Penyelenggara</p>
                        <p class="w-[145px]">Jabatan / Divisi</p>
                        <p class="w-[150px]">Tanggal Pelaksanaan</p>
                        <p class="w-[300px]">Kategori SKP</p>
                        <p class="w-[170px]">Status</p>
                        <p class="w-[120px]">Aksi</p>
                    </div>
                    <div class="flex flex-col gap-3">
                        @forelse ($registrations as $r)
                            <div class="flex flex-col items-center gap-1">
                                <div class="flex w-full items-center gap-6 px-3 py-2 transition hover:bg-blue-50">
                                    <div class="flex w-[300px] items-center gap-3">
                                        <img src="{{ $r->event->posterUrl() }}" alt="" class="size-[52px] shrink-0 rounded-[5px] object-cover">
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-black">{{ $r->event->nama_kegiatan }}</p>
                                            <p class="flex items-center gap-2 text-xs text-black"><img src="{{ asset('mivent_assets/icons/org-small.svg') }}" alt="" class="h-[12.849px] w-[13px]"> {{ $r->event->organisasi?->nama }}</p>
                                        </div>
                                    </div>
                                    <p class="w-[145px] text-center text-sm leading-5 text-black">{{ $r->event->isPanitia() ? ($r->divisiDiterima?->nama ?? $r->divisi?->nama ?? '-') : 'Peserta' }}</p>
                                    <p class="w-[150px] text-center text-sm leading-5 text-black">{{ $r->event->rentangTanggal() }}</p>
                                    <p class="w-[300px] text-center text-sm leading-5 text-black">{{ $r->event->skp?->nama ?? '-' }}</p>
                                    <span class="flex w-[170px] items-center justify-center rounded-[3px] border-[0.5px] px-2 py-1 text-center text-sm font-medium leading-5 {{ $r->badgeClass() }}">{{ $r->statusLabel() }}</span>
                                    {{-- prototype: Lihat Detail -> Event Di Ikuti - Detail Seleksi Berkas --}}
                                    <a href="{{ route('registrations.show', $r) }}" class="flex h-[22px] w-[120px] items-center justify-center gap-1 rounded-[3px] bg-primary-200 text-sm font-medium leading-5 text-white transition-all duration-300 hover:bg-primary-150"><img src="{{ asset('mivent_assets/icons/eye.svg') }}" alt="" class="h-[9.999px] w-[15.28px]"> Lihat Detail</a>
                                </div>
                                <div class="h-px w-[1305px] bg-primary-200"></div>
                            </div>
                        @empty
                            <p class="px-6 py-12 text-center text-sm text-gray-100">Belum ada event yang kamu ikuti. <a href="{{ route('events.index') }}" class="font-semibold text-primary-200 hover:underline">Jelajahi event</a></p>
                        @endforelse
                    </div>
                    <div class="flex items-center justify-between px-6">
                        <p class="text-sm font-semibold leading-5 text-black">Show {{ $registrations->firstItem() ?? 0 }} to {{ $registrations->total() }} entries</p>
                        {{ $registrations->onEachSide(1)->links('partials.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
