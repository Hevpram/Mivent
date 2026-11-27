{{-- Slot unggah berkas kecil (Figma: "Support File: .pdf / Pilih Berkas") --}}
@props(['name', 'label', 'accept' => '.pdf,.jpg,.jpeg,.png', 'hint' => 'Support File: .pdf, .jpg, .png (maks 2 MB)', 'current' => null, 'bag' => 'default', 'required' => false])
@php $id = 'f-' . str_replace(['[', ']'], '-', $name); @endphp
<div {{ $attributes->merge(['class' => 'rounded-[6px] border border-gray-100 bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition-all duration-300 hover:drop-shadow-[0_0_8px_rgba(64,103,159,0.5)]']) }}>
    <p class="text-xs font-bold text-primary-200">{{ $label }} @if ($required)<span class="text-red-100">*</span>@endif</p>
    <div class="mt-1.5 flex items-center gap-2">
        <x-ui-icon name="upload" class="h-5 w-5 shrink-0 text-primary-200" />
        <div class="min-w-0 flex-1 text-xs leading-4 text-black">
            <p>{{ $hint }}</p>
            <p id="{{ $id }}-label" data-empty="{{ $current ? 'Tersimpan: ' . $current : 'Belum ada file dipilih' }}" class="truncate">{{ $current ? 'Tersimpan: ' . $current : 'Belum ada file dipilih' }}</p>
        </div>
        <label class="cursor-pointer rounded-[6px] border border-primary-200 px-2.5 py-0.5 text-xs font-medium text-primary-200 transition-all duration-300 hover:bg-primary-200 hover:text-white">
            Pilih Berkas
            <input type="file" name="{{ $name }}" accept="{{ $accept }}" data-file-label="#{{ $id }}-label" class="sr-only" @if ($required) required @endif>
        </label>
    </div>
    <x-field-error :name="str_replace(['[', ']'], ['.', ''], $name)" :bag="$bag" />
</div>
