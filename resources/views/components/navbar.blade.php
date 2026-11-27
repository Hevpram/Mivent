{{-- Navbar publik / mahasiswa (Figma: NAVBAR 7:254 sebelum login, 7:24029 setelah login). Link aktif ditandai .is-active --}}
@php($u = auth()->user())
<header id="navbar" class="sticky top-0 z-50 rounded-b-[30px] font-poppins transition-all duration-300">
    <nav class="flex h-[75px] items-center justify-between px-[99px] py-3">
        <a href="{{ route('home') }}" class="flex w-[164.732px] items-center gap-[9px]">
            <img id="logo" src="{{ asset('mivent_assets/icons/nav-logo.svg') }}" data-logo-color="{{ asset('mivent_assets/icons/nav-logo.svg') }}" data-logo-white="{{ asset('mivent_assets/icons/footer-logo.svg') }}" alt="Logo Mivent" class="h-[20.685px] w-[23.3px]">
            <span class="logo_text text-gradient-mivent text-xl font-semibold">Mivent</span>
        </a>
        <div class="navbar_text flex items-center gap-10 text-base font-medium text-black">
            <a href="{{ route('home') }}" class="navbar_textIn px-1 {{ request()->routeIs('home') ? 'is-active' : '' }}">Beranda</a>
            <a href="{{ route('events.index') }}" class="navbar_textIn px-1 {{ request()->routeIs('events.*', 'registrations.create') ? 'is-active' : '' }}">Jelajahi Event</a>
            {{-- prototype sebelum login: Event Diikuti -> modal "Yuk, Login Dulu!" --}}
            <a href="{{ route('registrations.index') }}" @guest data-modal-open="login-diikuti" @endguest class="navbar_textIn px-1 {{ request()->routeIs('registrations.index', 'registrations.show') ? 'is-active' : '' }}">Event Diikuti</a>
            <a href="{{ route('faq') }}" class="navbar_textIn px-1 {{ request()->routeIs('faq') ? 'is-active' : '' }}">FAQ</a>
        </div>
        @if ($u)
            {{-- Sudah login: foto + nama -> Pengaturan Akun --}}
            <a href="{{ route('account.edit') }}" class="navbar_user flex w-[164.732px] items-center justify-end gap-2 text-sm font-medium text-black transition hover:opacity-80">
                <img src="{{ $u->avatarUrl() }}" alt="Foto profil" class="size-[47px] rounded-full object-cover">
                <span class="w-[94px] truncate">{{ \Illuminate\Support\Str::of($u->name)->explode(' ')->take(2)->implode(' ') }}</span>
            </a>
        @else
            <div class="flex w-[164.732px] justify-end">
                <a href="{{ route('login') }}" class="navbar_button inline-flex w-[130px] items-center justify-center rounded-[6px] border border-primary-150 py-2 text-base font-bold text-primary-150 transition-all duration-300 hover:bg-primary-150 hover:text-white">Login</a>
            </div>
        @endif
    </nav>
</header>
@guest
    <x-login-modal id="login-diikuti" :batal="route('events.index')" />
@endguest
