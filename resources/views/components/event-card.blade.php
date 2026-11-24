@props([
    'image',
    'kategori',
    'penyelenggara',
    'judul',
    'subjudul',
    'lokasi',
    'tanggal',
    'deskripsi',
    'link',
    'fluid' => false,
])

<div {{ $attributes->merge(["class" => "relative h-[561px] " . ($fluid ? "w-full min-w-0" : "w-[295px] shrink-0") . " overflow-clip rounded-[20px] bg-primary-50 font-poppins shadow-[0_0_8px_rgba(64,103,159,0.5)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(64,103,159,0.6)]"]) }}>
    {{-- Poster --}}
    <img src="{{ $image }}" alt="{{ $judul }}" class="absolute left-[calc(50%-0.5px)] top-0 h-[302px] {{ $fluid ? 'w-[calc(100%+6px)]' : 'w-[301px]' }} max-w-none -translate-x-1/2 object-cover">
    <span class="absolute {{ $fluid ? 'right-[9px]' : 'left-[212px]' }} top-3 flex w-[74px] items-center justify-center rounded-[6px] bg-background/75 px-1 py-0.5 text-center text-xs text-primary-200">
        <span class="truncate">{{ $kategori }}</span>
    </span>

    {{-- Lengkungan putih di bawah poster --}}
    <img src="{{ asset('mivent_assets/icons/card-wave.svg') }}" alt="" class="absolute left-[-4px] top-[272px] h-[291.5px] {{ $fluid ? 'w-[calc(100%+8px)]' : 'w-[303px]' }} max-w-none">

    {{-- Isi --}}
    <div class="absolute left-[13px] top-[315px] flex h-[232px] {{ $fluid ? 'right-[14px]' : 'w-[268px]' }} flex-col items-center gap-[14px]">
        <div class="flex w-full flex-col items-start gap-1">
            <div class="flex w-full flex-col items-start">
                <div class="flex items-center gap-2 text-xs text-black">
                    <img src="{{ asset('mivent_assets/icons/card-org.svg') }}" alt="" class="h-[12.848px] w-[13px]">
                    <span class="truncate">{{ $penyelenggara }}</span>
                </div>
                <h2 class="w-full truncate text-xl font-bold text-primary-200">{{ $judul }}</h2>
            </div>
            <p class="line-clamp-2 h-8 w-full text-xs capitalize text-black">{{ $subjudul }}</p>
            <img src="{{ asset('mivent_assets/icons/card-line.svg') }}" alt="" class="h-[0.5px] w-[72.5px]">
            <div class="flex w-full items-center gap-2 text-xs text-black">
                <img src="{{ asset('mivent_assets/icons/card-lokasi.svg') }}" alt="" class="h-[13px] w-[12px] shrink-0 px-[2px]">
                <span class="min-w-0 flex-1 truncate">{{ $lokasi }}</span>
            </div>
            <div class="flex w-full items-center gap-2 text-xs text-black">
                <img src="{{ asset('mivent_assets/icons/card-tanggal.svg') }}" alt="" class="h-[11px] w-[12px] shrink-0">
                <span class="min-w-0 flex-1">{{ $tanggal }}</span>
            </div>
            <img src="{{ asset('mivent_assets/icons/card-line.svg') }}" alt="" class="h-[0.5px] w-[72.5px]">
            <p class="line-clamp-2 w-full text-xs capitalize text-black">{{ $deskripsi }}</p>
        </div>
        {{-- prototype: Lihat Selengkapnya -> Detail Event --}}
        <a href="{{ $link }}" class="mt-auto flex w-full items-center justify-center rounded-[6px] bg-primary-150 py-2 text-sm font-semibold text-white transition-all duration-300 hover:bg-primary-200">Lihat Selengkapnya</a>
    </div>
</div>
