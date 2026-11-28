<div id="catalogMobileSheet"
     role="dialog"
     aria-modal="true"
     aria-labelledby="catalogMobileSheetTitle"
     class="fixed inset-0 z-50 hidden sm:hidden">
    <div data-sheet-backdrop class="fixed inset-0 bg-slate-900/45 backdrop-blur-xs transition-opacity duration-200 opacity-0"></div>

    <div data-sheet-panel class="glass-panel fixed inset-x-0 bottom-0 z-10 max-h-[85vh] flex flex-col rounded-t-[28px] border-t border-white/90 dark:border-white/20 shadow-2xl translate-y-full transition-transform duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]">
        <!-- Sheet Handle & Header -->
        <div class="px-5 pt-3 pb-2.5 border-b border-slate-200/60 dark:border-white/10 shrink-0">
            <div class="mx-auto mb-2.5 h-1 w-10 rounded-full bg-slate-300 dark:bg-white/25"></div>
            <div class="flex items-center justify-between">
                <h2 id="catalogMobileSheetTitle" class="text-[17px] font-bold text-slate-900 dark:text-white">Filter Katalog</h2>
                <button type="button"
                        data-sheet-close
                        aria-label="Tutup panel filter"
                        class="grid h-11 w-11 place-items-center rounded-full bg-slate-200/60 dark:bg-white/10 text-slate-600 dark:text-slate-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Sheet Body -->
        <div class="overflow-y-auto px-5 py-4 space-y-5 flex-1">
            <!-- Urutkan -->
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Urutkan</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($sortOptions as $sortKey => $sortLabel)
                        <button type="button"
                                data-sheet-sort="{{ $sortKey }}"
                                aria-pressed="{{ $currentSort === $sortKey ? 'true' : 'false' }}"
                                class="sheet-chip-sort min-h-[44px] rounded-full px-3.5 text-[12px] font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $currentSort === $sortKey ? 'btn-emerald' : 'liquid-chip' }}">
                            {{ $sortLabel }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Kategori -->
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Kategori Koleksi</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button"
                            data-sheet-category="semua"
                            data-contextual="0"
                            aria-pressed="{{ $currentKategori === 'semua' ? 'true' : 'false' }}"
                            class="sheet-chip-category min-h-[44px] rounded-full px-3.5 text-[12px] font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $currentKategori === 'semua' ? 'btn-emerald' : 'liquid-chip' }}">
                        Semua ({{ $totalCollectionCount }})
                    </button>
                    @foreach($categories as $category)
                        @php
                            $isCatActive = ($currentKategori === $category->slug || $currentKategori === $category->nama);
                            $isContextualCat = in_array($category->slug, $categoryContextualSlugs, true);
                        @endphp
                        <button type="button"
                                data-sheet-category="{{ $category->slug }}"
                                data-contextual="{{ $isContextualCat ? '1' : '0' }}"
                                aria-pressed="{{ $isCatActive ? 'true' : 'false' }}"
                                class="sheet-chip-category min-h-[44px] rounded-full px-3.5 text-[12px] font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $isCatActive ? 'btn-emerald font-semibold' : 'liquid-chip' }}">
                            {{ $category->nama }} ({{ $category->books_count }})
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Program Studi (Kontekstual di Bottom Sheet) -->
            <div id="sheetProdiSection" class="{{ $showProdiSubfilter ? '' : 'hidden' }} space-y-2.5 pt-2 border-t border-slate-200/60 dark:border-white/10">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Program Studi</p>

                <div class="flex flex-wrap gap-2">
                    <button type="button"
                            data-sheet-prodi="semua"
                            aria-pressed="{{ $currentProdi === 'semua' ? 'true' : 'false' }}"
                            class="sheet-chip-prodi min-h-[44px] rounded-full px-3.5 text-[12px] font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $currentProdi === 'semua' ? 'btn-emerald' : 'liquid-chip' }}">
                        Semua Prodi
                    </button>

                    @foreach($prodiGroups as $group)
                        @foreach($group['items'] as $item)
                            @php
                                $prodiCount = (int) ($prodiCounts[$item['name']] ?? 0);
                                $isProdiActive = ($currentProdi === $item['name']);
                                $isDisabled = ($prodiCount === 0 && ! $isProdiActive);
                            @endphp
                            <button type="button"
                                    data-sheet-prodi="{{ $item['name'] }}"
                                    data-sheet-prodi-level="{{ $group['key'] }}"
                                    @if($isDisabled) disabled aria-disabled="true" data-originally-disabled="1" @endif
                                    aria-pressed="{{ $isProdiActive ? 'true' : 'false' }}"
                                    class="sheet-chip-prodi min-h-[44px] rounded-full px-3.5 text-[12px] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $isDisabled ? 'opacity-50 cursor-not-allowed bg-slate-100/80 dark:bg-white/5 text-slate-400' : ($isProdiActive ? ($group['key'] === 'd3' ? 'btn-amber-gold font-semibold' : 'btn-emerald font-semibold') : 'liquid-chip font-medium') }}">
                                {{ $item['code'] }} · {{ str_replace([' (S. Tr)', ' (D3)'], '', $item['name']) }} ({{ $prodiCount }})
                            </button>
                        @endforeach
                    @endforeach
                </div>

                @if($availableYears->isNotEmpty())
                    <div class="pt-2">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tahun Terbit</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button"
                                    data-sheet-tahun="semua"
                                    aria-pressed="{{ $currentTahun === 'semua' ? 'true' : 'false' }}"
                                    class="sheet-chip-tahun min-h-[44px] rounded-full px-3.5 text-[12px] font-semibold transition-all {{ $currentTahun === 'semua' ? 'btn-emerald' : 'liquid-chip' }}">
                                Semua
                            </button>
                            @foreach($availableYears as $yearOption)
                                @php
                                    $isYActive = ($currentTahun === (string) $yearOption);
                                @endphp
                                <button type="button"
                                        data-sheet-tahun="{{ $yearOption }}"
                                        aria-pressed="{{ $isYActive ? 'true' : 'false' }}"
                                        class="sheet-chip-tahun min-h-[44px] rounded-full px-3.5 text-[12px] tabular-nums transition-all {{ $isYActive ? 'btn-emerald font-semibold' : 'liquid-chip font-medium' }}">
                                    {{ $yearOption }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Ketersediaan Stok -->
            <div class="pt-2 border-t border-slate-200/60 dark:border-white/10">
                <button type="button"
                        id="sheetOnlyAvailableToggle"
                        aria-pressed="{{ $onlyAvailable ? 'true' : 'false' }}"
                        class="flex min-h-[56px] w-full items-center justify-between rounded-[16px] glass-card px-4 py-3 text-left focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                    <div>
                        <span class="block text-[13.5px] font-semibold text-slate-800 dark:text-white">Hanya stok tersedia</span>
                        <span class="block text-[11.5px] text-slate-500 dark:text-slate-400">Sembunyikan buku yang sedang habis</span>
                    </div>
                    <span id="sheetStockIndicator" class="inline-flex h-6 w-11 items-center rounded-full p-0.5 transition-colors {{ $onlyAvailable ? 'bg-emerald-500 justify-end' : 'bg-slate-300 dark:bg-white/20 justify-start' }}">
                        <span class="h-5 w-5 rounded-full bg-white shadow-xs"></span>
                    </span>
                </button>
            </div>
        </div>

        <!-- Sticky Footer Actions -->
        <div class="px-5 py-3.5 border-t border-slate-200/60 dark:border-white/10 flex items-center gap-2.5 shrink-0 pb-[max(0.875rem,env(safe-area-inset-bottom))]">
            <button type="button"
                    id="sheetResetBtn"
                    class="btn-glass flex-1 min-h-[44px] rounded-full text-[13px] font-semibold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                Reset
            </button>
            <button type="button"
                    id="sheetApplyBtn"
                    class="btn-emerald flex-[2] min-h-[44px] rounded-full text-[13px] font-semibold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                Terapkan
            </button>
        </div>
    </div>
</div>
