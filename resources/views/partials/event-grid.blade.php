{{-- Grid hasil Jelajahi Event + tombol "Temukan Lebih Banyak" (Figma: Frame 4059) --}}
<div class="@container flex min-w-0 flex-1 flex-col items-center gap-[50px]">
    @if ($events->isEmpty())
        <div data-domino class="w-full rounded-[20px] bg-primary-50 px-6 py-16 text-center drop-shadow-[0_0_4px_rgba(64,103,159,0.5)]">
            <x-ui-icon name="search" class="mx-auto h-10 w-10 text-gray-100" />
            <p class="mt-3 font-semibold text-black">Event tidak ditemukan</p>
            <p class="mt-1 text-sm text-gray-100">Coba ubah kata kunci atau reset filter.</p>
        </div>
    @else
        <div class="grid w-full grid-cols-1 gap-x-5 @[360px]:grid-cols-2 @[560px]:grid-cols-3 gap-y-5">
            @foreach ($events as $event)
                <x-event-item :event="$event" fluid />
            @endforeach
        </div>
        @if ($total > $tampil)
            <a href="{{ request()->fullUrlWithQuery(['tampil' => $tampil + 9]) }}" class="group flex h-[52px] w-[303px] items-center justify-center gap-[18px] rounded-[6px] bg-primary-150 px-[35px] py-[14px] text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">
                <img src="{{ asset('mivent_assets/icons/refresh.svg') }}" alt="" class="size-[22px] transition-transform duration-500 group-hover:rotate-180"> Temukan Lebih Banyak
            </a>
        @endif
    @endif
</div>
