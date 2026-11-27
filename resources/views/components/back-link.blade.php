{{-- Tautan kembali (Figma: "Kembali Ke ..." — panah abu + teks 14px) --}}
@props(['href', 'label'])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'group flex w-fit items-center gap-3 text-sm text-gray-100 transition-colors duration-300 hover:text-primary-200']) }}>
    <img src="{{ asset('mivent_assets/icons/arrow-back.svg') }}" alt="" class="h-[14.728px] w-[19px] max-w-none -mx-px transition-transform duration-300 group-hover:-translate-x-1">
    <span>{{ $label }}</span>
</a>
