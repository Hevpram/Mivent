{{-- Sidebar admin (Figma: Frame 4209 Admin Ormawa / Frame 4211 Admin FMIPA).
     prototype: menu -> halaman masing-masing; Logout -> modal "Yakin Ingin Logout?" (17:8261 / 17:7921) --}}
@php
    $user = auth()->user();
    $fmipa = $user->isFmipa();
    $singkatan = $fmipa ? 'FMIPA' : ($user->organisasi?->singkatan ?? $user->name);
@endphp
<aside class="fixed left-0 top-0 z-40 flex h-[720px] max-h-screen w-[250px] bg-white py-4 pl-[29px] font-poppins drop-shadow-[2px_0_2px_rgba(64,103,159,0.5)]">
    <div class="flex h-full w-[200px] flex-col gap-[82px]">
        <a href="{{ route('account.edit') }}" class="group flex items-center gap-3" title="Pengaturan Akun">
            <span class="flex size-11 shrink-0 items-center justify-center overflow-hidden rounded-[10px] border-2 border-primary-200 bg-white transition-all duration-300 group-hover:shadow-[0_0_8px_rgba(64,103,159,0.5)]">
                @if (! $fmipa && $user->organisasi?->logo)
                    <img src="{{ $user->avatarUrl() }}" alt="Logo" class="size-full object-cover">
                @else
                    <img src="{{ asset('mivent_assets/icons/a-profile.svg') }}" alt="" class="h-[31.056px] w-[21px]">
                @endif
            </span>
            <span class="flex min-w-0 flex-col text-primary-200">
                <span class="truncate text-base font-bold leading-6">Admin {{ $singkatan }}</span>
                <span class="truncate text-xs leading-4">Wellcome Back, {{ $singkatan }}!</span>
            </span>
        </a>

        <nav class="flex min-h-0 flex-1 flex-col justify-between">
            <div class="flex flex-col gap-3">
                <x-admin-side-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="a-dashboard">Dashboard</x-admin-side-link>
                <x-admin-side-link :href="route('events.index')" :active="request()->routeIs('events.*')" icon="a-jelajahi" size="size-[14.08px]">Jelajahi Event</x-admin-side-link>
                @if ($fmipa)
                    <x-admin-side-link :href="route('fmipa.verifikasi.index')" :active="request()->routeIs('fmipa.verifikasi.*')" icon="a-verif" size="h-[14.872px] w-[12.195px]">Verifikasi Event</x-admin-side-link>
                    <x-admin-side-link :href="route('fmipa.ormawa.index')" :active="request()->routeIs('fmipa.ormawa.*')" icon="a-akun" size="h-[16.391px] w-[16.585px]">Kelola Akun Ormawa</x-admin-side-link>
                @else
                    <x-admin-side-link :href="route('manage.create')" :active="request()->routeIs('manage.create', 'manage.edit')" icon="a-ajukan" size="h-[13.145px] w-[11.83px]">Ajukan Event</x-admin-side-link>
                    <x-admin-side-link :href="route('manage.index')" :active="request()->routeIs('manage.index', 'manage.show', 'manage.registrant')" icon="a-kelola" size="h-[17.404px] w-[14.598px]">Kelola Event</x-admin-side-link>
                @endif
            </div>
            <button type="button" data-modal-open="logout" class="side-link group">
                <img src="{{ asset('mivent_assets/icons/a-logout.svg') }}" alt="" class="h-[15.182px] w-[17.873px] transition-transform duration-300 group-hover:-translate-x-0.5">
                Logout
            </button>
        </nav>
    </div>
</aside>
<x-logout-modal />
