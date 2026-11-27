{{-- Item menu sidebar admin: ikon abu (nonaktif) / biru (aktif) dari Figma --}}
@props(['href', 'active' => false, 'icon', 'size' => 'size-[17px]'])
<a href="{{ $href }}" @class(['side-link group', 'is-active' => $active]) @if ($active) aria-current="page" @endif>
    <span class="relative shrink-0 {{ $size }}">
        <img src="{{ asset('mivent_assets/icons/' . $icon . ($active ? '-on' : '-off') . '.svg') }}" alt="" class="absolute inset-0 size-full transition-opacity duration-300 group-hover:opacity-0">
        <img src="{{ asset('mivent_assets/icons/' . $icon . '-on.svg') }}" alt="" class="absolute inset-0 size-full opacity-0 transition-opacity duration-300 group-hover:opacity-100">
    </span>
    {{ $slot }}
</a>
