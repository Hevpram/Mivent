{{-- Kartu berkas (Figma: "Sertifikat PKKMB FMIPA" + Lihat Berkas / Ganti Berkas; badge "Terisi Otomatis" bila diambil dari profil) --}}
@props(['name', 'label', 'path' => null, 'auto' => false, 'required' => false, 'accept' => '.pdf,.jpg,.jpeg,.png', 'bag' => 'default'])
@php $id = 'bk-' . trim(preg_replace('/[^a-z0-9]+/i', '-', $name), '-'); @endphp
<div {{ $attributes->merge(['class' => 'flex flex-col justify-center gap-2 rounded-[6px] border border-gray-100 bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition-all duration-300 hover:drop-shadow-[0_0_8px_rgba(64,103,159,0.5)]']) }}>
    <div class="flex items-center justify-between gap-2">
        <div class="flex min-w-0 items-center gap-3">
            <img src="{{ asset('mivent_assets/icons/file-doc.svg') }}" alt="" class="size-8 shrink-0">
            <div class="flex min-w-0 flex-col gap-0.5">
                <p class="text-sm font-bold leading-[18px] text-primary-200">{{ $label }} @if ($required)<span class="text-red-100">*</span>@endif</p>
                <p id="{{ $id }}-label" data-empty="{{ $path ? basename($path) : 'Belum ada berkas' }}" class="truncate text-xs leading-[15px] text-black">{{ $path ? basename($path) : 'Belum ada berkas' }}</p>
            </div>
        </div>
        @if ($auto && $path)
            <span class="shrink-0 rounded-[5px] bg-green-50 p-1 text-xs text-green-150">Terisi Otomatis</span>
        @endif
    </div>
    <div class="flex items-start justify-between">
        @if ($path)
            <a href="{{ \App\Support\Files::url($path) }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-[6px] border border-primary-200 px-2.5 py-1 text-xs font-medium leading-[16.5px] text-primary-200 transition-all duration-300 hover:bg-blue-50"><img src="{{ asset('mivent_assets/icons/eye-outline.svg') }}" alt="" class="h-[10.469px] w-4"> Lihat Berkas</a>
        @else
            <span></span>
        @endif
        <label class="flex cursor-pointer items-center gap-2 rounded-[6px] border border-white bg-primary-200 px-2.5 py-1 text-xs font-medium leading-[16.5px] text-white transition-all duration-300 hover:bg-primary-150">
            <img src="{{ asset('mivent_assets/icons/swap-white.svg') }}" alt="" class="h-[10.667px] w-[13.333px]"> {{ $path ? 'Ganti Berkas' : 'Unggah Berkas' }}
            <input type="file" id="{{ $id }}" name="{{ $name }}" accept="{{ $accept }}" data-file-label="#{{ $id }}-label" class="sr-only" @if ($required) required @endif>
        </label>
    </div>
    <x-field-error :name="str_replace(['[', ']'], ['.', ''], $name)" :bag="$bag" />
</div>
