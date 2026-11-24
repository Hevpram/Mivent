{{-- Checkbox kustom: belum dicentang = putih + border gray-100; dicentang = primary-200 + ikon centang putih.
     Input asli tetap dipakai (bisa diklik, Tab/Space, ikut submit form). --}}
@props(['size' => 'size-[15px]', 'radius' => 'rounded-[3.75px]'])
<span class="relative inline-flex shrink-0 {{ $size }}">
    <input type="checkbox" {{ $attributes->merge(['class' => "peer absolute inset-0 m-0 size-full cursor-pointer appearance-none $radius border border-gray-100 bg-white transition-all duration-300 hover:border-primary-200 checked:border-primary-200 checked:bg-primary-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-200 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"]) }}>
    <img src="{{ asset('mivent_assets/icons/check-white.svg') }}" alt="" class="pointer-events-none absolute inset-0 m-auto hidden w-[72%] peer-checked:block">
</span>
