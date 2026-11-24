{{-- Detail Event untuk mahasiswa / pengunjung (Figma: Detail Event 7:2844, Detail Event - Sebelum Login 10:26193) --}}
<x-layout :title="$event->nama_kegiatan">
    <section class="relative isolate flex justify-center px-[155px] pb-[84px] pt-20">
        <div class="pointer-events-none absolute inset-x-0 top-[634px] -z-10">
            <div class="h-[100px] bg-gradient-to-b from-background to-white"></div>
            <div class="h-[494px] bg-white"></div>
            <div class="h-[100px] bg-gradient-to-b from-white to-background"></div>
        </div>
        <div class="flex w-[1130px] flex-col items-center gap-10">
            @include('partials.event-body', ['back' => route('events.index'), 'backLabel' => 'Kembali Ke List Event'])

            {{-- CTA pendaftaran (prototype: Daftar ke Form Pendaftaran Event -> Daftar Event 1; sebelum login -> modal Harus Login) --}}
            @php
                $label = $event->isPanitia() ? 'Daftar ke Form Pendaftaran Event' : 'Daftar sebagai Peserta';
                $cta = 'group flex w-[568px] items-center justify-center gap-[18px] rounded-[6px] px-[35px] py-[14px] text-base font-bold drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300';
                $panah = '<span class="flex h-[14px] w-[23px] items-center justify-center"><img src="'.asset('mivent_assets/icons/arrow-right.svg').'" alt="" class="h-[23px] w-[14px] max-w-none rotate-90 transition-transform duration-300 group-hover:translate-y-1"></span>';
            @endphp
            <div data-domino class="flex flex-col items-center gap-2">
                @if ($registration)
                    <a href="{{ route('registrations.show', $registration) }}" class="{{ $cta }} bg-primary-150 text-white hover:bg-primary-200">Lihat Status Pendaftaran {!! $panah !!}</a>
                    <p class="text-xs text-gray-100">Kamu sudah terdaftar di event ini.</p>
                @elseif (! $event->isOpen())
                    <span class="{{ $cta }} cursor-not-allowed bg-gray-50 text-gray-100">Pendaftaran Ditutup</span>
                @elseif (! auth()->check())
                    <a href="{{ route('login') }}" data-modal-open="login-wajib" class="{{ $cta }} bg-primary-150 text-white hover:bg-primary-200">{{ $label }} {!! $panah !!}</a>
                @else
                    <a href="{{ route('registrations.create', $event) }}" class="{{ $cta }} bg-primary-150 text-white hover:bg-primary-200">{{ $label }} {!! $panah !!}</a>
                @endif
            </div>
        </div>
    </section>
    @guest
        <x-login-modal id="login-wajib" />
    @endguest
</x-layout>
