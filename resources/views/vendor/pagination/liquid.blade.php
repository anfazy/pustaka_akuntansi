@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
            Menampilkan
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
            –
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $paginator->total() }}</span>
            koleksi
        </p>

        <div class="flex flex-wrap items-center justify-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-10 items-center rounded-full px-4 text-xs font-semibold text-slate-400 dark:text-slate-600 bg-white/40 dark:bg-white/5 border border-white/60 dark:border-white/10 cursor-not-allowed">
                    &laquo; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="liquid-chip inline-flex h-10 items-center rounded-full px-4 text-xs font-semibold text-slate-700 dark:text-slate-200">
                    &laquo; Sebelumnya
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-10 w-10 items-center justify-center text-xs font-semibold text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="btn-emerald inline-flex h-10 w-10 items-center justify-center rounded-full text-xs font-bold shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="liquid-chip inline-flex h-10 w-10 items-center justify-center rounded-full text-xs font-semibold text-slate-700 dark:text-slate-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="liquid-chip inline-flex h-10 items-center rounded-full px-4 text-xs font-semibold text-slate-700 dark:text-slate-200">
                    Berikutnya &raquo;
                </a>
            @else
                <span class="inline-flex h-10 items-center rounded-full px-4 text-xs font-semibold text-slate-400 dark:text-slate-600 bg-white/40 dark:bg-white/5 border border-white/60 dark:border-white/10 cursor-not-allowed">
                    Berikutnya &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif
