{{-- Kerangka halaman login / lupa sandi (Figma: Login 7:5419 — panel kiri berpola + ilustrasi, panel kanan rounded-l-80) --}}
@props(['title', 'back' => null, 'backLabel' => 'Kembali ke Beranda'])
<x-layout :title="$title" :navbar="false" :footer="false">
    <div class="relative flex min-h-screen overflow-hidden bg-primary-100">
        {{-- dekorasi: pola kotak + titik, cahaya lembut --}}
        <div style="background-image: url('{{ asset('mivent_assets/icons/auth-tile.svg') }}')" class="pointer-events-none absolute -top-12 left-0 h-[1120px] w-[672px] bg-[length:56px_56px] opacity-40"></div>
        <div class="pointer-events-none absolute -left-32 -top-32 size-96 rounded-full bg-white/15 blur-[32px]"></div>
        <div class="pointer-events-none absolute left-[425px] top-[775px] size-80 rounded-full bg-primary-200/25 blur-[37.65px]"></div>

        <aside class="relative flex w-[610px] shrink-0 flex-col px-[51px] pb-[53px] pt-10">
            <x-back-pill data-domino :href="$back ?? route('home')" :label="$backLabel" />
            <div class="my-auto flex w-[513px] flex-col gap-[30.6px]">
                <div data-domino class="rounded-[20px] bg-primary-200/20 px-4 pb-3 pt-3">
                    <div class="relative h-[266px] w-[482px] overflow-hidden rounded-[20px]">
                        <img src="{{ asset('mivent_assets/login.png') }}" alt="Ilustrasi mahasiswa FMIPA di laboratorium" class="size-full object-cover">
                        <div class="absolute left-[11px] top-4 flex h-[25px] w-[70px] items-center gap-0.5 bg-primary-150 px-px">
                            <img src="{{ asset('mivent_assets/logo-unud.png') }}" alt="" class="size-[19px] opacity-75">
                            <span aria-hidden="true" class="w-[45px] text-[4.75px] font-bold leading-tight text-white/75">FMIPA Universitas Udayana</span>
                        </div>
                    </div>
                </div>
                <div data-domino class="flex h-[100.5px] items-center justify-center rounded-[19px] bg-primary-200/20">
                    <div class="flex items-start gap-[11.5px]">
                        <img src="{{ asset('mivent_assets/icons/quote.svg') }}" alt="" class="mt-1 h-[11.5px] w-[16.3px] rotate-180">
                        <div class="flex w-[457px] flex-col gap-1 text-sm text-white">
                            <p class="leading-[24.9px]">"Gerbang terpadu ormawa, seminar sains, dan pemenuhan<br>poin SKP civitas akademika FMIPA."</p>
                            <p class="font-medium leading-[23px] tracking-[0.38px]">— Dekanat FMIPA Universitas</p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="-ml-[51px] w-[610px] text-center text-sm font-semibold text-white/80">&copy; {{ date('Y') }} Mivent. All Rights Reserved.</p>
        </aside>
        <main class="relative flex flex-1 items-center rounded-l-[80px] bg-primary-50 py-12 pl-[124px] pr-[125px] shadow-[-18px_0_47.5px_rgba(234,240,246,0.25)]">
            <div class="w-[581px]">{{ $slot }}</div>
        </main>
    </div>
</x-layout>
