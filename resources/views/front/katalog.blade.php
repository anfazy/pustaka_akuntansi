@extends('front.layout')

@section('title', 'Katalog Buku — Perpustakaan Jurusan Akuntansi')

@section('content')
    <div class="mb-4 flex flex-col gap-2.5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-[21px] sm:text-[24px] font-bold tracking-tight text-slate-900 dark:text-white leading-tight">Katalog Buku</h1>
            <p class="text-[12.5px] text-slate-500 dark:text-slate-400 mt-0.5">Jelajahi {{ $books->total() }} koleksi buku dan referensi jurusan akuntansi.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- FILTER PROGRAM STUDI (Apple Liquid Glass Dropdown) -->
            <div class="relative" id="catalogProdiCombobox">
                <button type="button"
                        id="catalogProdiToggleBtn"
                        aria-expanded="false"
                        aria-haspopup="listbox"
                        class="inline-flex min-h-[34px] items-center gap-2 rounded-full px-3 text-[11.5px] transition-all {{ $currentProdi !== 'semua' ? 'liquid-badge-emerald font-semibold shadow-sm' : 'liquid-chip font-medium' }}">
                    <span class="max-w-[210px] truncate">
                        {{ $currentProdi !== 'semua' ? 'Prodi: ' . $currentProdi : 'Semua Prodi' }}
                    </span>
                    <svg id="catalogProdiChevron" class="h-3.5 w-3.5 shrink-0 opacity-75 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div id="catalogProdiDropdown"
                     role="listbox"
                     class="glass-popover-sheet hidden absolute left-0 sm:left-auto sm:right-0 w-[min(320px,calc(100vw-2rem))] sm:min-w-[315px] z-50 mt-1.5 max-h-80 overflow-y-auto rounded-[18px] p-1.5 transition-all">
                    <div class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        Pilihan Jurusan Akuntansi Polines
                    </div>
                    <div class="space-y-0.5 mt-0.5">
                        <a href="{{ route('katalog', request()->except('prodi', 'page')) }}"
                           class="catalog-prodi-item group flex w-full items-center justify-between gap-3 rounded-[12px] px-3 py-2 text-left text-[12.5px] transition-colors {{ $currentProdi === 'semua' ? 'bg-emerald-500/15 text-emerald-900 dark:text-emerald-300 font-semibold' : 'font-medium text-slate-800 dark:text-slate-100 hover:bg-emerald-500/12 dark:hover:bg-emerald-400/18 hover:text-emerald-900 dark:hover:text-emerald-300' }}">
                            <span class="leading-snug sm:whitespace-nowrap">Semua Program Studi</span>
                            <span class="shrink-0 whitespace-nowrap rounded-full bg-slate-200/80 dark:bg-white/10 px-2 py-0.5 text-[9.5px] font-bold text-slate-600 dark:text-slate-300">Semua</span>
                        </a>

                        @foreach($prodiOptions as $prodiName)
                            @php
                                $isProdiActive = ($currentProdi === $prodiName);
                                $isD3 = str_contains($prodiName, '(D3)');
                            @endphp
                            <a href="{{ route('katalog', array_merge(request()->except('prodi', 'page'), ['prodi' => $prodiName])) }}"
                               class="catalog-prodi-item group flex w-full items-center justify-between gap-3 rounded-[12px] px-3 py-2 text-left text-[12.5px] transition-colors {{ $isProdiActive ? 'bg-emerald-500/15 text-emerald-900 dark:text-emerald-300 font-semibold' : 'font-medium text-slate-800 dark:text-slate-100 hover:bg-emerald-500/12 dark:hover:bg-emerald-400/18 hover:text-emerald-900 dark:hover:text-emerald-300' }}">
                                <span class="leading-snug sm:whitespace-nowrap">{{ $prodiName }}</span>
                                <span class="shrink-0 whitespace-nowrap rounded-full {{ $isD3 ? 'liquid-badge-amber' : 'liquid-badge-emerald' }} px-2 py-0.5 text-[9.5px] font-bold">
                                    {{ $isD3 ? 'D3' : 'S. Tr' }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            @if(request('tersedia') === '1')
                <a href="{{ route('katalog', request()->except('tersedia', 'page')) }}"
                   class="inline-flex min-h-[34px] items-center gap-1.5 rounded-full liquid-badge-emerald px-3 text-[11.5px] font-semibold shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    <span>Hanya Stok Tersedia &times;</span>
                </a>
            @else
                <a href="{{ route('katalog', array_merge(request()->except('page'), ['tersedia' => '1'])) }}"
                   class="inline-flex min-h-[34px] items-center gap-1.5 rounded-full liquid-chip px-3 text-[11.5px] font-medium">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                    <span>Hanya Stok Tersedia</span>
                </a>
            @endif
        </div>
    </div>

    <!-- CATEGORY FILTER CHIPS -->
    <div class="no-scrollbar mb-4 -mx-4 px-4 flex items-center gap-2 overflow-x-auto pt-0.5 pb-3 sm:mx-0 sm:px-0 sm:flex-wrap sm:overflow-visible sm:pb-2.5">
        <!-- Semua -->
        <a href="{{ route('katalog', array_merge(request()->except('kategori', 'page'), ['kategori' => 'semua'])) }}"
           class="inline-flex min-h-[34px] shrink-0 items-center gap-1.5 rounded-full px-3 text-[12px] font-semibold transition-all duration-200 {{ $currentKategori === 'semua' ? 'btn-emerald' : 'liquid-chip' }}">
            Semua Kategori
        </a>

        @foreach($categories as $category)
            @php
                $isActiveCat = ($currentKategori === $category->slug || $currentKategori === $category->nama);
            @endphp
            <a href="{{ route('katalog', array_merge(request()->except('kategori', 'page'), ['kategori' => $category->slug])) }}"
               class="inline-flex min-h-[34px] shrink-0 items-center gap-1.5 rounded-full px-3 text-[12px] font-medium transition-all duration-200 {{ $isActiveCat ? 'btn-emerald' : 'liquid-chip' }}">
                <span>{{ $category->nama }}</span>
                <span class="rounded-full px-1.5 py-0.5 text-[10px] font-semibold {{ $isActiveCat ? 'bg-white/25 text-white' : 'bg-black/5 dark:bg-white/10 text-slate-500 dark:text-slate-400' }}">
                    {{ $category->books_count }}
                </span>
            </a>
        @endforeach
    </div>

    <!-- BOOK GRID -->
    @if($books->count() > 0)
        <div class="grid grid-cols-2 gap-x-3.5 gap-y-6 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @foreach($books as $book)
                @include('front.components.book-card', ['book' => $book])
            @endforeach
        </div>

        <!-- PAGINATION -->
        <div class="mt-12">
            {{ $books->links('vendor.pagination.liquid') }}
        </div>
    @else
        <!-- EMPTY STATE -->
        <div class="glass-card my-12 flex flex-col items-center justify-center rounded-[32px] p-12 text-center">
            <div class="grid h-16 w-16 place-items-center rounded-full bg-slate-100 dark:bg-white/5 text-slate-400 mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Tidak ada buku yang sesuai</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm">Coba ubah kata kunci pencarian atau bersihkan filter kategori dan prodi Anda.</p>
            <a href="{{ route('katalog') }}" class="btn-glass mt-5 inline-flex min-h-[44px] items-center rounded-full px-5 text-sm font-semibold">
                Reset Semua Filter
            </a>
        </div>
    @endif

    <script>
        (function() {
            function setCatalogProdiOpen(isOpen) {
                const dropdown = document.getElementById('catalogProdiDropdown');
                const chevron = document.getElementById('catalogProdiChevron');
                const toggleBtn = document.getElementById('catalogProdiToggleBtn');
                if (!dropdown) return;

                dropdown.classList.toggle('hidden', !isOpen);
                if (chevron) chevron.classList.toggle('rotate-180', isOpen);
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            if (!window.__catalogProdiBound) {
                window.__catalogProdiBound = true;

                document.addEventListener('click', function(e) {
                    const target = e.target;
                    if (!target || typeof target.closest !== 'function') return;

                    const toggleBtn = target.closest('#catalogProdiToggleBtn');
                    const dropdown = document.getElementById('catalogProdiDropdown');
                    const combobox = document.getElementById('catalogProdiCombobox');

                    if (toggleBtn && dropdown) {
                        e.stopPropagation();
                        setCatalogProdiOpen(dropdown.classList.contains('hidden'));
                        return;
                    }

                    if (target.closest('.catalog-prodi-item')) {
                        setCatalogProdiOpen(false);
                        return;
                    }

                    if (combobox && dropdown && !combobox.contains(target)) {
                        setCatalogProdiOpen(false);
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        setCatalogProdiOpen(false);
                    }
                });
            }
        })();
    </script>
@endsection
