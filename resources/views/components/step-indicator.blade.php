{{-- Indikator langkah (Figma: Daftar Event 1–3 — lingkaran 35px; selesai = centang hijau). Klik untuk pindah langkah (prototype). --}}
@props(['steps'])
<div data-domino class="flex h-[74px] w-full items-center rounded-[20px] bg-primary-50 px-8 py-4 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
    <div class="flex w-full items-center justify-between">
        @foreach ($steps as $i => [$judul, $sub])
            <button type="button" data-step-indicator data-step-goto="{{ $i }}" data-state="{{ $i === 0 ? 'active' : 'todo' }}" class="group flex cursor-pointer items-center gap-3 text-left">
                <span class="flex size-[35px] shrink-0 items-center justify-center rounded-full bg-gray-50 text-base font-bold leading-5 text-gray-100 transition-colors duration-300 group-data-[state=active]:bg-primary-200 group-data-[state=active]:text-white group-data-[state=done]:bg-transparent">
                    <span class="group-data-[state=done]:hidden">{{ $i + 1 }}</span>
                    <img src="{{ asset('mivent_assets/icons/step-done.svg') }}" alt="" class="hidden size-9 max-w-none group-data-[state=done]:block">
                </span>
                <span class="flex flex-col gap-0.5 text-sm leading-5 text-black"><b class="font-bold">{{ $i + 1 }}. {{ $judul }}</b><span>{{ $sub }}</span></span>
            </button>
        @endforeach
    </div>
</div>
