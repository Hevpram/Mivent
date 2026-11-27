{{-- Pagination custom bertema Mivent: "Show 1 to 10 of 24 entries  < 1 2 3 Next >" --}}
@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 text-xs font-semibold">
        <p>Menampilkan {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data</p>
        @if ($paginator->hasPages())
            <div class="flex items-center gap-1">
                @if ($paginator->onFirstPage())
                    <span class="px-2 text-gray-100">&lsaquo;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-2 text-primary-200" rel="prev">&lsaquo;</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-2">{{ $element }}</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="rounded bg-primary-200 px-2.5 py-1 text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="rounded border border-primary-100 px-2.5 py-1 hover:bg-blue-50">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-2 text-primary-200" rel="next">Next &rsaquo;</a>
                @else
                    <span class="px-2 text-gray-100">Next &rsaquo;</span>
                @endif
            </div>
        @endif
    </nav>
@endif
