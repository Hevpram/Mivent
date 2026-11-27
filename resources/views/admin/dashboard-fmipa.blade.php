{{-- Dashboard Admin FMIPA (Figma: Dashboard - Admin FMIPA 7:19175).
     prototype: Lihat Semuanya -> Verifikasi Event; Lihat Detail -> Verifikasi Event - Lihat Detail --}}
<x-admin-layout title="Dashboard">
    <div class="mx-auto flex w-[1043px] max-w-full flex-col gap-10">
        <div class="flex items-end justify-between">
            <div class="flex flex-col gap-1">
                <h1 data-domino class="text-gradient-mivent w-fit text-4xl font-bold">Dashboard Admin FMIPA</h1>
                <p data-domino class="text-lg text-black">Ringkasan aktivitas, permohonan verifikasi, dan distribusi poin SKP di lingkungan FMIPA.</p>
            </div>
            <a data-domino href="{{ route('manage.create') }}" class="group flex shrink-0 items-center gap-2 rounded-[10px] bg-primary-150 px-4 py-3 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">
                <img src="{{ asset('mivent_assets/icons/a-plus.svg') }}" alt="" class="size-4 transition-transform duration-300 group-hover:rotate-90"> Buat Event Baru
            </a>
        </div>

        <div class="flex w-full items-center justify-between">
            <x-stat-card label="Event Disetujui" :value="$stats['disetujui'] . ' Event Disetujui'" img="a-stat-selesai" />
            <x-stat-card label="Event Perlu Revisi" :value="$stats['revisi'] . ' Event Revisi'" img="f-stat-revisi" />
            <x-stat-card label="Organisasi Terdaftar" :value="$stats['ormawa'] . ' Ormawa'" img="f-stat-ormawa" />
        </div>

        <div data-domino class="flex w-full flex-col gap-5 rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
            <div class="flex items-center justify-between pr-3">
                <div class="flex flex-col gap-1">
                    <h2 class="text-2xl font-bold text-primary-200">Verifikasi Event</h2>
                    <p class="text-base text-black">Tinjau proposal kegiatan ilmiah, legalitas berkas SIK, dan kategori SKP mahasiswa FMIPA Unud.</p>
                </div>
                <a href="{{ route('fmipa.verifikasi.index') }}" class="text-base font-medium text-primary-200 underline transition-colors duration-300 hover:text-primary-150">Lihat Semuanya</a>
            </div>
            @include('partials.verif-table', ['events' => $events])
        </div>
    </div>
</x-admin-layout>
