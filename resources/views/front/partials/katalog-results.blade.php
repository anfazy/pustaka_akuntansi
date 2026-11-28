@if($books->count() > 0)
    <div class="grid grid-cols-2 gap-x-3.5 gap-y-6 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @foreach($books as $book)
            <div class="catalog-card-stagger" style="--card-idx: {{ $loop->index }};">
                @include('front.components.book-card', ['book' => $book])
            </div>
        @endforeach
    </div>

    <div id="catalogPagination" class="mt-10">
        {{ $books->links('vendor.pagination.liquid') }}
    </div>
@else
    @php
        $contextLabel = $effectiveContext === 'ta_skripsi'
            ? 'TA/Skripsi'
            : ($effectiveContext === 'proposal_laporan_pkl' ? 'Laporan PKL' : ($selectedCategory?->nama ?? 'Koleksi'));
        $shortSubject = $effectiveContext === 'ta_skripsi'
            ? 'TA'
            : ($effectiveContext === 'proposal_laporan_pkl' ? 'Laporan PKL' : 'koleksi');
        $clearProdiKeepCategoryUrl = route('katalog', request()->except('prodi', 'tahun', 'tersedia', 'q', 'page'));
    @endphp
    <div class="glass-card my-8 flex flex-col items-center justify-center rounded-[28px] p-10 text-center">
        <div class="grid h-14 w-14 place-items-center rounded-full bg-slate-100 dark:bg-white/5 text-slate-400 mb-3.5">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </div>

        @if($currentProdi !== 'semua')
            <h3 class="text-[17px] font-bold text-slate-800 dark:text-white">
                Belum ada {{ $shortSubject }} untuk prodi ini.
            </h3>
            <p class="text-[13px] text-slate-500 dark:text-slate-400 mt-1 max-w-md">
                Prodi <strong class="font-semibold text-slate-700 dark:text-slate-200">{{ $currentProdi }}</strong> belum memiliki koleksi yang cocok dengan filter aktif saat ini.
            </p>
            <div class="mt-5 flex flex-wrap items-center justify-center gap-2.5">
                @if($selectedCategory || $effectiveContext !== null)
                    <a href="{{ $clearProdiKeepCategoryUrl }}"
                       data-catalog-nav
                       data-no-pjax
                       class="btn-emerald inline-flex min-h-[44px] sm:min-h-[40px] items-center rounded-full px-5 sm:px-4 text-[12.5px] font-semibold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                        Lihat semua {{ $contextLabel }}
                    </a>
                @endif
                <a href="{{ route('katalog') }}"
                   data-catalog-nav
                   data-no-pjax
                   class="btn-glass inline-flex min-h-[44px] sm:min-h-[40px] items-center rounded-full px-5 sm:px-4 text-[12.5px] font-semibold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                    Reset filter
                </a>
            </div>
        @else
            <h3 class="text-[17px] font-bold text-slate-800 dark:text-white">Tidak ada buku yang sesuai</h3>
            <p class="text-[13px] text-slate-500 dark:text-slate-400 mt-1 max-w-sm">
                Coba ubah kata kunci pencarian atau bersihkan filter kategori dan prodi Anda.
            </p>
            <a href="{{ route('katalog') }}"
               data-catalog-nav
               data-no-pjax
               class="btn-glass mt-5 inline-flex min-h-[44px] sm:min-h-[40px] items-center rounded-full px-5 text-[12.5px] font-semibold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                Reset filter
            </a>
        @endif
    </div>
@endif
