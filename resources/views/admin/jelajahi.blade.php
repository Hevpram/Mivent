{{-- Jelajahi Event (Admin Ormawa / Admin FMIPA) --}}
<x-admin-layout title="Jelajahi Event">
    <a href="{{ route('dashboard') }}" data-domino class="flex w-fit items-center gap-2 text-sm text-gray-100 transition hover:text-primary-200">
        <x-ui-icon name="arrow-left" class="h-4 w-4" /> Kembali Ke Dashboard
    </a>
    <h1 data-domino class="text-gradient-mivent mt-4 w-fit text-4xl font-bold">Jelajahi Event</h1>
    <p data-domino class="mt-1 text-lg text-black">Lihat seluruh event FMIPA yang sudah dipublikasikan.</p>

    <div class="mt-8 flex items-start gap-6">
        @include('partials.event-filter')
        @include('partials.event-grid')
    </div>
</x-admin-layout>
