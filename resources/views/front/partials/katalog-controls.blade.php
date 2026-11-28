@php
    $allCategoriesUrl = route('katalog', request()->except('kategori', 'prodi', 'tipe', 'page'));
    $allProdiUrl = route('katalog', request()->except('prodi', 'page'));
    $hasActiveStrip = ($currentKategori !== 'semua') || ($currentProdi !== 'semua') || ($currentTahun !== 'semua') || $onlyAvailable || ($currentSearch !== '');
@endphp

<div class="glass-card rounded-[36px] sm:rounded-[30px] p-3.5 shadow-sm">
    <!-- BARIS 1: KATEGORI CHIPS (Horizontal scrollable + edge fade di HP) -->
    <div class="relative">
        <div class="catalog-chip-strip no-scrollbar flex items-center gap-1.5 overflow-x-auto py-5 -my-5 pl-3.5 -ml-3.5 pr-9 -mr-3.5 sm:p-0 sm:m-0 sm:flex-wrap sm:overflow-visible [mask-image:linear-gradient(to_right,black_calc(100%-36px),transparent)] sm:[mask-image:none]">
            <a href="{{ $allCategoriesUrl }}"
               aria-pressed="{{ $currentKategori === 'semua' ? 'true' : 'false' }}"
               data-catalog-nav
               data-no-pjax
               data-contextual="0"
               class="inline-flex min-h-[44px] sm:min-h-[32px] shrink-0 items-center gap-1.5 rounded-full px-3.5 sm:px-3 text-[12px] font-semibold transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-1 {{ $currentKategori === 'semua' ? 'btn-emerald' : 'liquid-chip' }}">
                <span>Semua</span>
                <span class="rounded-full px-1.5 py-0.5 text-[11px] font-semibold tabular-nums {{ $currentKategori === 'semua' ? 'bg-white/25 text-white' : 'bg-black/5 dark:bg-white/10 text-slate-500 dark:text-slate-400' }}">
                    {{ $totalCollectionCount }}
                </span>
            </a>

            @foreach($categories as $category)
                @php
                    $isActiveCat = ($currentKategori === $category->slug || $currentKategori === $category->nama);
                    $isContextualCat = in_array($category->slug, $categoryContextualSlugs, true);
                    $catParams = request()->except('kategori', 'tipe', 'page');
                    if (! $isContextualCat) {
                        unset($catParams['prodi']);
                    }
                    $catParams['kategori'] = $category->slug;
                @endphp
                <a href="{{ route('katalog', $catParams) }}"
                   aria-pressed="{{ $isActiveCat ? 'true' : 'false' }}"
                   data-catalog-nav
                   data-no-pjax
                   data-contextual="{{ $isContextualCat ? '1' : '0' }}"
                   class="inline-flex min-h-[44px] sm:min-h-[32px] shrink-0 items-center gap-1.5 rounded-full px-3.5 sm:px-3 text-[12px] transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-1 {{ $isActiveCat ? 'btn-emerald font-semibold' : 'liquid-chip font-medium' }}">
                    <span>{{ $category->nama }}</span>
                    <span class="rounded-full px-1.5 py-0.5 text-[11px] font-semibold tabular-nums {{ $isActiveCat ? 'bg-white/25 text-white' : 'bg-black/5 dark:bg-white/10 text-slate-500 dark:text-slate-400' }}">
                        {{ $category->books_count }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- BARIS 2: SUB-FILTER KONTEKSTUAL PRODI + TAHUN (Slide-down saat TA/Skripsi atau Laporan PKL dipilih) -->
    <div id="catalogProdiCollapse"
         data-visible="{{ $showProdiSubfilter ? '1' : '0' }}"
         class="catalog-prodi-collapse hidden sm:grid {{ $showProdiSubfilter ? '' : 'is-collapsed' }}"
         aria-hidden="{{ $showProdiSubfilter ? 'false' : 'true' }}">
        <div class="overflow-visible min-h-0">
            <div class="pt-2.5 border-t border-slate-200/70 dark:border-white/10 flex flex-wrap items-center justify-between gap-x-3 gap-y-2">
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mr-0.5">Program Studi:</span>

                    <a href="{{ $allProdiUrl }}"
                       aria-pressed="{{ $currentProdi === 'semua' ? 'true' : 'false' }}"
                       data-catalog-nav
                       data-no-pjax
                       class="inline-flex min-h-[30px] items-center rounded-full px-2.5 text-[11px] font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $currentProdi === 'semua' ? 'btn-emerald' : 'liquid-chip' }}">
                        Semua
                    </a>

                    @foreach($prodiGroups as $group)
                        @if(! $loop->first)
                            <span class="h-4 w-px bg-slate-200 dark:bg-white/15 mx-0.5" aria-hidden="true"></span>
                        @endif

                        @foreach($group['items'] as $item)
                            @php
                                $prodiCount = (int) ($prodiCounts[$item['name']] ?? 0);
                                $isProdiActive = ($currentProdi === $item['name']);
                                $isDisabled = ($prodiCount === 0 && ! $isProdiActive);
                                $prodiUrl = route('katalog', array_merge(request()->except('prodi', 'page'), ['prodi' => $item['name']]));
                            @endphp

                            @if($isDisabled)
                                <button type="button"
                                        disabled
                                        aria-disabled="true"
                                        aria-pressed="false"
                                        title="{{ $item['name'] }} (0 koleksi)"
                                        aria-label="{{ $item['name'] }} (0 koleksi)"
                                        class="group/chip relative inline-flex min-h-[30px] items-center gap-1 rounded-full border border-slate-200/70 dark:border-white/10 bg-slate-100/80 dark:bg-white/5 px-2.5 text-[11px] font-medium text-slate-400 dark:text-slate-500 opacity-60 cursor-not-allowed">
                                    <span>{{ $item['code'] }}</span>
                                    <span class="tabular-nums text-[11px] opacity-80">0</span>
                                    <span role="tooltip" class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 whitespace-nowrap rounded-[10px] bg-slate-900 dark:bg-slate-800 px-2.5 py-1 text-[11px] font-medium text-white shadow-lg opacity-0 transition-opacity duration-150 group-hover/chip:opacity-100 z-30">
                                        {{ $item['name'] }}
                                    </span>
                                </button>
                            @else
                                <a href="{{ $prodiUrl }}"
                                   aria-pressed="{{ $isProdiActive ? 'true' : 'false' }}"
                                   title="{{ $item['name'] }}"
                                   aria-label="{{ $item['name'] }} ({{ $prodiCount }} koleksi)"
                                   data-catalog-nav
                                   data-no-pjax
                                   class="group/chip relative inline-flex min-h-[30px] items-center gap-1 rounded-full px-2.5 text-[11px] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-1 {{ $isProdiActive ? ($group['key'] === 'd3' ? 'btn-amber-gold font-semibold shadow-sm' : 'btn-emerald font-semibold') : 'liquid-chip font-medium' }}">
                                    <span>{{ $item['code'] }}</span>
                                    <span class="rounded-full px-1 text-[11px] font-semibold tabular-nums {{ $isProdiActive ? ($group['key'] === 'd3' ? 'bg-black/10 text-amber-950' : 'bg-white/25 text-white') : 'bg-black/5 dark:bg-white/10 text-slate-500 dark:text-slate-400' }}">
                                        {{ $prodiCount }}
                                    </span>
                                    <span role="tooltip" class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 whitespace-nowrap rounded-[10px] bg-slate-900 dark:bg-slate-800 px-2.5 py-1 text-[11px] font-medium text-white shadow-lg opacity-0 transition-opacity duration-150 group-hover/chip:opacity-100 group-focus-visible/chip:opacity-100 z-30">
                                        {{ $item['name'] }}
                                    </span>
                                </a>
                            @endif
                        @endforeach
                    @endforeach
                </div>

                <!-- Tahun Dropdown (Accessible Listbox: 18px outer = 12px inner + 6px padding) -->
                <div class="relative shrink-0" data-listbox-root="tahun">
                    <button type="button"
                            id="catalogYearBtn"
                            data-listbox-trigger="tahun"
                            aria-haspopup="listbox"
                            aria-expanded="false"
                            aria-controls="catalogYearListbox"
                            class="inline-flex min-h-[30px] items-center gap-1.5 rounded-full px-2.5 text-[11px] transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $currentTahun !== 'semua' ? 'liquid-badge-emerald font-semibold' : 'liquid-chip font-medium' }}">
                        <span class="text-slate-500 dark:text-slate-400">Tahun:</span>
                        <span class="font-semibold">{{ $currentTahun === 'semua' ? 'Semua' : $currentTahun }}</span>
                        <svg class="h-3 w-3 opacity-70 transition-transform duration-200" data-listbox-chevron fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <ul id="catalogYearListbox"
                        role="listbox"
                        aria-label="Filter tahun terbit"
                        tabindex="-1"
                        data-listbox-menu="tahun"
                        class="glass-popover-sheet hidden absolute right-0 z-50 mt-1.5 max-h-60 min-w-[140px] overflow-y-auto rounded-[18px] p-1.5 space-y-0.5 focus:outline-none">
                        <li role="presentation">
                            <a href="{{ route('katalog', request()->except('tahun', 'page')) }}"
                               role="option"
                               aria-selected="{{ $currentTahun === 'semua' ? 'true' : 'false' }}"
                               data-catalog-nav
                               data-no-pjax
                               tabindex="-1"
                               class="flex w-full items-center justify-between rounded-[12px] px-2.5 py-1.5 text-[11.5px] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $currentTahun === 'semua' ? 'bg-emerald-500/15 text-emerald-900 dark:text-emerald-300 font-semibold' : 'font-medium text-slate-700 dark:text-slate-200 hover:bg-emerald-500/10' }}">
                                <span>Semua</span>
                            </a>
                        </li>
                        @foreach($availableYears as $yearOption)
                            @php
                                $isYearActive = ($currentTahun === (string) $yearOption);
                            @endphp
                            <li role="presentation">
                                <a href="{{ route('katalog', array_merge(request()->except('tahun', 'page'), ['tahun' => $yearOption])) }}"
                                   role="option"
                                   aria-selected="{{ $isYearActive ? 'true' : 'false' }}"
                                   data-catalog-nav
                                   data-no-pjax
                                   tabindex="-1"
                                   class="flex w-full items-center justify-between rounded-[12px] px-2.5 py-1.5 text-[11.5px] tabular-nums transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $isYearActive ? 'bg-emerald-500/15 text-emerald-900 dark:text-emerald-300 font-semibold' : 'font-medium text-slate-700 dark:text-slate-200 hover:bg-emerald-500/10' }}">
                                    <span>{{ $yearOption }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 3: FILTER AKTIF -->
    @if($hasActiveStrip)
        <div class="mt-2.5 pt-2 border-t border-slate-200/60 dark:border-white/10 flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Filter aktif:</span>

                @if($currentSearch !== '')
                    <a href="{{ route('katalog', request()->except('q', 'page')) }}"
                       data-catalog-nav
                       data-no-pjax
                       class="inline-flex min-h-[36px] sm:min-h-[26px] items-center gap-1 rounded-full bg-slate-200/75 dark:bg-white/10 px-3 sm:px-2.5 py-0.5 text-[11px] font-medium text-slate-700 dark:text-slate-200 hover:bg-rose-500/15 hover:text-rose-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                        <span>Cari: "{{ $currentSearch }}"</span>
                        <span aria-hidden="true">&times;</span>
                    </a>
                @endif

                @if($currentKategori !== 'semua' && $selectedCategory)
                    <a href="{{ route('katalog', request()->except('kategori', 'prodi', 'tipe', 'page')) }}"
                       data-catalog-nav
                       data-no-pjax
                       class="inline-flex min-h-[36px] sm:min-h-[26px] items-center gap-1 rounded-full liquid-badge-emerald px-3 sm:px-2.5 py-0.5 text-[11px] font-semibold hover:opacity-85 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                        <span>{{ $selectedCategory->nama }}</span>
                        <span aria-hidden="true">&times;</span>
                    </a>
                @endif

                @if($currentProdi !== 'semua')
                    <a href="{{ route('katalog', request()->except('prodi', 'page')) }}"
                       data-catalog-nav
                       data-no-pjax
                       class="inline-flex min-h-[36px] sm:min-h-[26px] items-center gap-1 rounded-full {{ str_contains($currentProdi, '(D3)') ? 'liquid-badge-amber' : 'liquid-badge-emerald' }} px-3 sm:px-2.5 py-0.5 text-[11px] font-semibold hover:opacity-85 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                        <span>{{ $currentProdi }}</span>
                        <span aria-hidden="true">&times;</span>
                    </a>
                @endif

                @if($currentTahun !== 'semua')
                    <a href="{{ route('katalog', request()->except('tahun', 'page')) }}"
                       data-catalog-nav
                       data-no-pjax
                       class="inline-flex min-h-[36px] sm:min-h-[26px] items-center gap-1 rounded-full bg-slate-200/75 dark:bg-white/10 px-3 sm:px-2.5 py-0.5 text-[11px] font-semibold text-slate-700 dark:text-slate-200 hover:bg-rose-500/15 hover:text-rose-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                        <span>Tahun: {{ $currentTahun }}</span>
                        <span aria-hidden="true">&times;</span>
                    </a>
                @endif

                @if($onlyAvailable)
                    <a href="{{ route('katalog', request()->except('tersedia', 'page')) }}"
                       data-catalog-nav
                       data-no-pjax
                       class="inline-flex min-h-[36px] sm:min-h-[26px] items-center gap-1 rounded-full liquid-badge-emerald px-3 sm:px-2.5 py-0.5 text-[11px] font-semibold hover:opacity-85 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                        <span>Hanya tersedia</span>
                        <span aria-hidden="true">&times;</span>
                    </a>
                @endif
            </div>

            <a href="{{ route('katalog') }}"
               data-catalog-nav
               data-no-pjax
               class="inline-flex min-h-[36px] sm:min-h-[26px] items-center text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 rounded-full px-2">
                Reset semua
            </a>
        </div>
    @endif
</div>
