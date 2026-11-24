{{-- Kartu statistik admin (Figma: Dashboard Admin 7:12880 / 7:19175) --}}
@props(['label', 'value', 'icon' => null, 'img' => null])
<div data-domino class="flex items-start gap-5 rounded-[20px] bg-primary-50 px-6 py-3 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)] transition-transform duration-300 hover:-translate-y-0.5">
    <div class="flex flex-col gap-1 whitespace-nowrap text-black">
        <p class="text-base">{{ $label }}</p>
        <p class="text-2xl font-bold">{{ $value }}</p>
    </div>
    @if ($img)
        <span class="relative size-9 shrink-0"><img src="{{ asset('mivent_assets/icons/' . $img . '.svg') }}" alt="" class="absolute -inset-[22.22%] size-[144.44%] max-w-none"></span>
    @else
        <span class="flex size-9 shrink-0 items-center justify-center rounded-[6px] bg-white text-primary-200 shadow-[0_0_4px_rgba(64,103,159,0.5)]"><x-ui-icon :name="$icon" class="h-5 w-5" /></span>
    @endif
</div>
