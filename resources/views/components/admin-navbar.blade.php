{{-- Navbar admin (Figma: Frame 4208). prototype: ikon profil -> Pengaturan Akun, ikon lonceng -> Notifikasi --}}
<header class="sticky top-0 z-30 flex h-[75px] flex-col justify-center bg-white pl-7 font-poppins drop-shadow-[0_2px_2px_rgba(64,103,159,0.5)]">
    <div class="flex w-[calc(100%-47px)] items-center justify-between">
        <div class="flex items-center gap-[26px]">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-[9px] transition-opacity duration-300 hover:opacity-80">
                <img src="{{ asset('mivent_assets/icons/a-logo.svg') }}" alt="Logo Mivent" class="h-[15.125px] w-[17.037px]">
                <span class="text-gradient-mivent text-xl font-semibold">Mivent</span>
            </a>
            <form action="{{ route('events.index') }}" method="GET" role="search" class="flex items-center gap-[9px]">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari pengelolaan kepanitiaan, seminar, dll" aria-label="Cari"
                    class="h-8 w-[305px] rounded-[10px] border border-primary-200 bg-white pl-3 pr-3 text-xs font-light leading-4 text-black transition-all duration-300 placeholder:text-gray-100 focus:shadow-[0_0_8px_rgba(64,103,159,0.5)] focus:outline-none">
                <button type="submit" class="flex size-8 items-center justify-center rounded-[10px] bg-primary-200 transition-all duration-300 hover:bg-primary-150" aria-label="Cari">
                    <img src="{{ asset('mivent_assets/icons/a-search.svg') }}" alt="" class="size-[17.942px]">
                </button>
            </form>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('account.edit') }}" class="transition-transform duration-300 hover:scale-110" aria-label="Profil">
                <img src="{{ asset('mivent_assets/icons/a-user.svg') }}" alt="" class="h-[22.573px] w-[19.692px]">
            </a>
            <a href="{{ route('notifications') }}" class="transition-transform duration-300 hover:scale-110" aria-label="Notifikasi">
                <img src="{{ asset('mivent_assets/icons/' . (auth()->user()->unreadNotifications()->exists() ? 'a-bell' : 'a-bell-plain') . '.svg') }}" alt="" class="h-[21.473px] w-[19.165px]">
            </a>
        </div>
    </div>
</header>
