{{-- Tombol kembali di panel auth (Figma: "Kembali ke Beranda" — pil biru transparan 225x49, panah putih) --}}
@props(['href', 'label' => 'Kembali'])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'group flex h-[49px] w-fit min-w-[225px] items-center justify-center gap-3 rounded-[20px] bg-primary-200/20 px-[22px] text-sm font-bold text-white transition-all duration-300 hover:bg-primary-200/40']) }}>
    <span class="flex h-[14px] w-[23px] items-center justify-center"><img src="{{ asset('mivent_assets/icons/back-white.svg') }}" alt="" class="h-[23px] w-[14px] max-w-none -rotate-90 transition-transform duration-300 group-hover:-translate-x-1"></span>
    {{ $label }}
</a>
