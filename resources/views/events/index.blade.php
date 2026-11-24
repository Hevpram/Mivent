{{-- Jelajahi Event (Figma: Event 7:1701 / Event - Sebelum Login 10:25050) --}}
<x-layout title="Jelajahi Event">
    <section class="relative isolate flex justify-center px-[58px] pb-20 pt-20">
        {{-- Latar dekoratif (Figma: Group 5) --}}
        <div class="pointer-events-none absolute inset-x-0 top-[634px] -z-10">
            <div class="h-[100px] bg-gradient-to-b from-background to-white"></div>
            <div class="h-[494px] bg-white"></div>
            <div class="h-[100px] bg-gradient-to-b from-white to-background"></div>
        </div>

        <div class="flex w-[1325px] max-w-full flex-col items-start gap-4">
            {{-- prototype: Kembali Ke Beranda -> Landing Page --}}
            <x-back-link data-domino :href="route('home')" label="Kembali Ke Beranda" />
            <div class="flex w-[898px] flex-col items-start gap-0.5">
                <h1 data-domino class="text-gradient-mivent text-4xl font-bold leading-normal">Jelajahi Event</h1>
                <p data-domino class="text-lg text-black">Temukan berbagai event FMIPA yang sesuai dengan minat dan target SKP akademikmu.</p>
            </div>

            <div class="flex w-full items-start gap-8">
                @include('partials.event-filter')
                @include('partials.event-grid')
            </div>
        </div>
    </section>
</x-layout>
