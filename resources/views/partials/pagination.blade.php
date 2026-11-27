{{-- Paginasi (Figma: tabel Event Diikuti — kotak 33.5px, aktif biru, "Next >") --}}
@if ($paginator->hasPages())
    <nav class="flex items-center gap-2.5" aria-label="Paginasi">
        @if ($paginator->onFirstPage())
            <span class="flex h-[33.514px] items-center opacity-40"><img src="{{ asset('mivent_assets/icons/page-prev.svg') }}" alt="" class="h-[10.613px] w-[6.144px] rotate-180"></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" aria-label="Sebelumnya" class="flex h-[33.514px] items-center transition-transform duration-300 hover:-translate-x-0.5"><img src="{{ asset('mivent_assets/icons/page-prev.svg') }}" alt="" class="h-[10.613px] w-[6.144px] rotate-180"></a>
        @endif
        <div class="flex items-end gap-2">
            @foreach ($elements as $el)
                @if (is_string($el))<span class="text-base font-bold leading-6 text-primary-200">....</span>@endif
                @if (is_array($el))
                    @foreach ($el as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex size-[33.514px] items-center justify-center rounded-[4px] bg-primary-200 text-sm font-bold text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="flex size-[33.514px] items-center justify-center rounded-[4px] border-[1.676px] border-transparent bg-white text-sm font-bold text-primary-200 transition-all duration-300 hover:border-primary-200">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="group flex items-center gap-1 text-base font-bold text-primary-200">Next <img src="{{ asset('mivent_assets/icons/page-next.svg') }}" alt="" class="h-[10.613px] w-[6.144px] transition-transform duration-300 group-hover:translate-x-0.5"></a>
        @else
            <span class="flex items-center gap-1 text-base font-bold text-primary-200 opacity-40">Next <img src="{{ asset('mivent_assets/icons/page-next.svg') }}" alt="" class="h-[10.613px] w-[6.144px]"></span>
        @endif
    </nav>
@endif
