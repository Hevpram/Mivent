{{-- Sidebar "Filter Event" (Figma: Frame 4051 di halaman Event; dipakai Jelajahi Event mahasiswa & admin) --}}
<form action="{{ url()->current() }}" method="GET" data-domino
      class="flex h-fit w-[300px] shrink-0 flex-col gap-8 xl:w-[369px] rounded-[20px] bg-primary-50 px-5 py-6 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)]">
    <div class="flex items-center gap-4">
        <img src="{{ asset('mivent_assets/icons/filter.svg') }}" alt="" class="h-[23.323px] w-[26px]">
        <p class="text-xl font-semibold text-black">Filter Event</p>
    </div>

    <div class="flex flex-col gap-5">
        <div class="flex flex-col gap-1">
            <p class="text-base font-medium text-black">Cari Event</p>
            <label class="flex items-center gap-4 rounded-[10px] bg-white px-3 py-2 drop-shadow-[0_0_5px_rgba(64,103,159,0.25)] transition focus-within:drop-shadow-[0_0_5px_rgba(64,103,159,0.6)]">
                <img src="{{ asset('mivent_assets/icons/search.svg') }}" alt="" class="size-[29px]">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari kepanitiaan, seminar, dll" class="min-w-0 flex-1 bg-transparent text-sm font-light text-black placeholder:text-gray-100 focus:outline-none">
            </label>
        </div>

        <div class="flex flex-col gap-1">
            <p class="text-base font-medium text-black">Peran Event</p>
            <div class="flex flex-col gap-1">
                @foreach (['peserta' => 'Peserta', 'panitia' => 'Panitia / Volunteer'] as $v => $n)
                    <label class="flex w-fit cursor-pointer items-center gap-3 text-sm text-black">
                        <x-checkbox name="peran[]" value="{{ $v }}" :checked="in_array($v, (array) request('peran'))" /> {{ $n }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col gap-1">
            <p class="text-base font-medium text-black">Penyelenggara (Ormawa)</p>
            <div class="flex flex-col gap-1">
                @foreach ($organisasi as $o)
                    <label class="flex w-fit cursor-pointer items-center gap-3 text-sm text-black">
                        <x-checkbox name="organisasi[]" value="{{ $o->id }}" :checked="in_array($o->id, (array) request('organisasi'))" /> {{ $o->nama }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col gap-1">
            <p class="text-base font-medium text-black">Kategori Event</p>
            <div class="flex flex-col gap-1">
                @foreach ($kategori as $k)
                    <label class="flex w-fit cursor-pointer items-center gap-3 text-sm text-black">
                        <x-checkbox name="kategori[]" value="{{ $k->id }}" :checked="in_array($k->id, (array) request('kategori'))" /> {{ $k->nama }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col gap-1">
            <p class="text-base font-medium text-black">Rentang Tanggal</p>
            <x-date-range-calendar variant="filter" nameStart="dari" nameEnd="sampai" :start="request('dari')" :end="request('sampai')" />
        </div>
    </div>

    <div class="flex flex-col gap-3">
        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-[10px] bg-primary-150 px-[35px] py-2 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">
            <img src="{{ asset('mivent_assets/icons/filter-funnel.svg') }}" alt="" class="h-[15.905px] w-[13.098px]"> Terapkan Filter
        </button>
        <a href="{{ url()->current() }}" class="flex w-full items-center justify-center rounded-[10px] bg-white px-[35px] py-2 text-base font-bold text-primary-150 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-150 hover:text-white">Reset Pilihan</a>
    </div>
</form>
