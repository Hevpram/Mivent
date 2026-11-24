{{-- Dashboard Admin Ormawa (Figma: Dashboard - Admin Ormawa 7:12880).
     prototype: Ajukan Event Baru -> Ajukan Event, Lihat Semuanya -> Kelola Event, Lihat/Kelola -> detail --}}
@php $org = $user->organisasi; @endphp
<x-admin-layout title="Dashboard">
    <div class="mx-auto flex w-[1043px] max-w-full flex-col items-end gap-10">
        <div data-domino class="flex h-[122px] w-full items-center justify-between rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
            <div class="flex items-center gap-6">
                <img src="{{ $user->avatarUrl() }}" alt="Logo {{ $org?->nama }}" class="size-[70px] shrink-0 rounded-[12px] bg-white object-cover">
                <div class="flex w-[599px] shrink flex-col gap-1">
                    <h1 class="text-2xl font-bold uppercase text-primary-200">Halo, {{ $org?->nama }}{{ $org?->singkatan ? ' (' . $org->singkatan . ')' : '' }}</h1>
                    <div class="flex items-center justify-between text-sm text-black">
                        <p class="w-[175px]">Tahun Kepengurusan {{ $org?->tahun_kepengurusan ?? '-' }}</p>
                        <p class="w-[175px]">{{ $org?->kampus ?? 'Kampus Bukit Jimbaran, Badung' }}</p>
                        <p class="w-[144px]">Status Akun: {{ $user->is_active ? 'Aktif & Terverifikasi' : 'Nonaktif' }}</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('manage.create') }}" class="group flex w-[197px] shrink-0 items-center whitespace-nowrap justify-center gap-2 rounded-[10px] bg-primary-150 px-3 py-2 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">
                <img src="{{ asset('mivent_assets/icons/a-plus.svg') }}" alt="" class="size-4 transition-transform duration-300 group-hover:rotate-90"> Ajukan Event Baru
            </a>
        </div>

        <div class="flex w-full items-center justify-between">
            <x-stat-card label="Total Keseluruhan Kegiatan" :value="$stats['total'] . ' Event'" img="a-stat-total" />
            <x-stat-card label="Event Berlangsung" :value="$stats['aktif'] . ' Event Aktif'" img="a-stat-aktif" />
            <x-stat-card label="Event Terselesaikan" :value="$stats['selesai'] . ' Event Selesai'" img="a-stat-selesai" />
        </div>

        <div data-domino class="flex w-full flex-col gap-5 rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
            <div class="flex items-center justify-between pr-3">
                <div class="flex flex-col gap-1">
                    <h2 class="text-2xl font-bold text-primary-200">Event Saya</h2>
                    <p class="text-base text-black">Kelola jadwal, publikasi, pendaftar, dan sertifikat kepanitiaan serta peserta</p>
                </div>
                <a href="{{ route('manage.index') }}" class="text-base font-medium text-primary-200 underline transition-colors duration-300 hover:text-primary-150">Lihat Semuanya</a>
            </div>
            @include('partials.manage-table', ['events' => $events])
        </div>
    </div>
</x-admin-layout>
