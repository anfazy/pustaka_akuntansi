<div class="flex flex-col gap-2.5 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-[21px] sm:text-[24px] font-bold tracking-tight text-slate-900 dark:text-white leading-tight">Katalog Buku</h1>
        <p class="text-[12.5px] text-slate-500 dark:text-slate-400 mt-0.5 tabular-nums">
            Menampilkan <strong class="font-semibold text-slate-700 dark:text-slate-200">{{ $books->total() }}</strong> dari <strong class="font-semibold text-slate-700 dark:text-slate-200">{{ $totalCollectionCount }}</strong> koleksi
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <!-- Mobile: Satu tombol Filter (N) membuka Bottom Sheet -->
        <button type="button"
                id="catalogMobileFilterBtn"
                aria-haspopup="dialog"
                aria-controls="catalogMobileSheet"
                aria-expanded="false"
                class="sm:hidden inline-flex min-h-[36px] items-center gap-2 rounded-full px-3.5 text-[12px] font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $activeFilterCount > 0 ? 'btn-emerald shadow-sm' : 'liquid-chip' }}">
            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
            </svg>
            <span>Filter{{ $activeFilterCount > 0 ? " ({$activeFilterCount})" : '' }}</span>
        </button>

        <!-- Desktop/Tablet: Urutkan (Accessible Custom Listbox) -->
        <div class="relative hidden sm:block" data-listbox-root="sort">
            <button type="button"
                    id="catalogSortBtn"
                    data-listbox-trigger="sort"
                    aria-haspopup="listbox"
                    aria-expanded="false"
                    aria-controls="catalogSortListbox"
                    class="inline-flex min-h-[34px] items-center gap-1.5 rounded-full liquid-chip px-3 text-[11.5px] font-medium focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                <span class="text-slate-500 dark:text-slate-400">Urutkan:</span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $sortOptions[$currentSort] ?? 'Terbaru' }}</span>
                <svg class="h-3.5 w-3.5 opacity-70 transition-transform duration-200" data-listbox-chevron fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <ul id="catalogSortListbox"
                role="listbox"
                aria-label="Urutkan koleksi"
                tabindex="-1"
                data-listbox-menu="sort"
                class="glass-popover-sheet hidden absolute right-0 z-50 mt-1.5 min-w-[185px] rounded-[16px] p-1.5 space-y-0.5 focus:outline-none">
                @foreach($sortOptions as $sortKey => $sortLabel)
                    @php
                        $isSortActive = ($currentSort === $sortKey);
                        $sortUrl = route('katalog', array_merge(request()->except('sort', 'page'), $sortKey === 'terbaru' ? [] : ['sort' => $sortKey]));
                    @endphp
                    <li role="presentation">
                        <a href="{{ $sortUrl }}"
                           role="option"
                           aria-selected="{{ $isSortActive ? 'true' : 'false' }}"
                           data-catalog-nav
                           data-no-pjax
                           tabindex="-1"
                           class="flex w-full items-center justify-between gap-2 rounded-[11px] px-3 py-1.5 text-[12px] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $isSortActive ? 'bg-emerald-500/15 text-emerald-900 dark:text-emerald-300 font-semibold' : 'font-medium text-slate-700 dark:text-slate-200 hover:bg-emerald-500/10' }}">
                            <span>{{ $sortLabel }}</span>
                            @if($isSortActive)
                                <svg class="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Desktop/Tablet: Hanya Tersedia Toggle Chip -->
        @php
            $tersediaToggleUrl = $onlyAvailable
                ? route('katalog', request()->except('tersedia', 'page'))
                : route('katalog', array_merge(request()->except('page'), ['tersedia' => '1']));
        @endphp
        <a href="{{ $tersediaToggleUrl }}"
           role="button"
           aria-pressed="{{ $onlyAvailable ? 'true' : 'false' }}"
           data-catalog-nav
           data-no-pjax
           class="hidden sm:inline-flex min-h-[34px] items-center gap-1.5 rounded-full px-3 text-[11.5px] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $onlyAvailable ? 'liquid-badge-emerald font-semibold shadow-sm' : 'liquid-chip font-medium' }}">
            <span class="h-1.5 w-1.5 rounded-full {{ $onlyAvailable ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            <span>Hanya tersedia</span>
        </a>
    </div>
</div>
