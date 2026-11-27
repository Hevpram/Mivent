@php $panitia = $event->isPanitia(); $hariIni = now()->startOfDay(); @endphp
{{-- Isi Detail Event (Figma: Detail Event 7:2844). Variabel: $event, $registration, $back, $backLabel --}}
<div class="flex w-full flex-col items-start gap-3">
    {{-- prototype: Kembali Ke List Event -> Event --}}
    <x-back-link data-domino :href="$back" :label="$backLabel" />

    <div class="flex w-full flex-col items-center gap-10">
        @include('partials.event-hero')

        <div class="flex w-full items-start gap-11">
            {{-- Tentang Kegiatan --}}
            <div data-domino class="flex w-[599px] shrink-0 flex-col gap-5 rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('mivent_assets/icons/sec-tentang.svg') }}" alt="" class="h-[29.5px] w-[23.6px]">
                    <h2 class="text-2xl font-bold text-primary-200">Tentang Kegiatan</h2>
                </div>
                <div class="prose-mivent text-base text-black [&_p]:mb-4 [&_p:last-child]:mb-0">{!! clean($event->deskripsi) !!}</div>
            </div>

            {{-- Timeline --}}
            <div data-domino class="flex min-w-0 flex-1 flex-col gap-5 rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('mivent_assets/icons/sec-timeline.svg') }}" alt="" class="h-[24.527px] w-[44.966px]">
                    <h2 class="text-2xl font-bold text-primary-200">Timeline {{ $panitia ? 'Open Recruitment' : 'Kegiatan' }}</h2>
                </div>
                <ol class="relative flex flex-col gap-6 before:absolute before:bottom-[60px] before:left-[11px] before:top-[11px] before:w-0.5 before:bg-primary-100">
                    @forelse ($event->timelines as $i => $t)
                        @php
                            $sisa = (int) $hariIni->diffInDays($t->tanggal_mulai, false);
                            $selesai = $t->tanggal_selesai ?? $t->tanggal_mulai;
                            $ket = $sisa > 0 ? "Tersisa {$sisa} Hari Lagi" : ($hariIni->lte($selesai) ? 'Sedang Berlangsung' : 'Telah Selesai');
                        @endphp
                        <li class="relative pl-[50px]">
                            <span class="absolute left-0 top-0 flex size-[25px] items-center justify-center rounded-[12.5px] border-3 border-primary-100 bg-primary-200 text-sm font-bold text-white">{{ $i + 1 }}</span>
                            <div class="flex w-[389px] flex-col gap-1 rounded-[10px] bg-white py-3 pl-6 pr-3 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:translate-x-1">
                                <p class="text-base font-semibold text-black">{{ $t->nama_tahapan }}</p>
                                <p class="flex items-center gap-2 text-sm text-black"><img src="{{ asset('mivent_assets/icons/timeline-cal.svg') }}" alt="" class="h-[18.333px] w-5"> {{ \App\Models\Event::formatRange($t->tanggal_mulai, $t->tanggal_selesai) }}</p>
                                <p class="text-sm {{ $sisa > 0 ? 'text-green-150' : 'text-gray-100' }}">{{ $ket }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-100">Timeline belum tersedia.</li>
                    @endforelse
                </ol>
            </div>
        </div>

        {{-- Persyaratan --}}
        <div data-domino class="flex w-full flex-col gap-5 rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
            <div class="flex items-center gap-4">
                <img src="{{ asset('mivent_assets/icons/sec-syarat.svg') }}" alt="" class="h-[24.974px] w-[33.133px]">
                <h2 class="text-2xl font-bold text-primary-200">Persyaratan Pendaftaran</h2>
            </div>
            <div class="grid w-full grid-cols-2 gap-4">
                @forelse ($event->syarat as $s)
                    <div class="flex items-center gap-2.5 rounded-[10px] bg-white py-3 pl-6 pr-3 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:-translate-y-0.5">
                        <img src="{{ asset('mivent_assets/icons/syarat-check.svg') }}" alt="" class="size-10 shrink-0">
                        <p class="flex min-h-[78px] min-w-0 flex-1 items-center text-base font-semibold text-black">{{ $s->deskripsi }}</p>
                    </div>
                @empty
                    <p class="col-span-2 text-sm text-gray-100">Tidak ada persyaratan khusus.</p>
                @endforelse
            </div>
        </div>

        {{-- Kebutuhan Divisi (hanya event panitia) --}}
        @if ($panitia)
            <div data-domino class="flex w-full flex-col gap-5 rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('mivent_assets/icons/sec-divisi.svg') }}" alt="" class="h-[28.579px] w-[27.15px]">
                    <h2 class="text-2xl font-bold text-primary-200">Kebutuhan Divisi &amp; Job Description</h2>
                </div>
                @forelse ($event->divisi as $d)
                    <div data-accordion class="w-full rounded-[10px] bg-white py-3 pl-6 pr-3 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)]">
                        <button type="button" data-accordion-btn aria-expanded="false" class="flex w-full items-start justify-between text-left">
                            <span class="flex w-[801px] items-center gap-3">
                                <img src="{{ asset('mivent_assets/icons/divisi-check.svg') }}" alt="" class="size-[34px] shrink-0">
                                <span class="flex-1 text-base font-semibold text-black transition hover:text-primary-200">{{ $d->nama }}</span>
                            </span>
                            <span class="flex items-center gap-3">
                                <span class="rounded-[10px] bg-background px-3 py-1 text-base font-semibold text-primary-200">{{ $d->kuota }} Orang</span>
                                <img src="{{ asset('mivent_assets/icons/accordion-chevron.svg') }}" alt="" data-accordion-arrow class="h-3 w-6 transition-transform duration-300">
                            </span>
                        </button>
                        <p data-accordion-body class="hidden w-[755px] pl-[46px] text-sm text-black">Deskripsi Peran: {{ $d->deskripsi }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-100">Belum ada divisi.</p>
                @endforelse
            </div>
        @endif
    </div>
</div>
