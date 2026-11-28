@extends('front.layout')

@section('title', 'Beranda — Perpustakaan Jurusan Akuntansi')

@section('content')
    @php
        $nowWib = now('Asia/Jakarta');
        $dayOfWeek = $nowWib->dayOfWeekIso; // 1 = Mon .. 7 = Sun
        $timeDecimal = $nowWib->hour + ($nowWib->minute / 60);
        $isLibraryOpen = false;
        if ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
            $isLibraryOpen = $timeDecimal >= 8.5 && $timeDecimal <= 15.5;
        } elseif ($dayOfWeek === 5) {
            $isLibraryOpen = $timeDecimal >= 8.5 && $timeDecimal <= 14.0;
        }
        $spotlightList = ($spotlightBooks ?? collect())->count() > 0 ? $spotlightBooks : $popularBooks->take(5);
    @endphp

    <!-- TOP EDITORIAL HEADER WITH LIVE LIBRARY PULSE -->
    <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-[21px] sm:text-[24px] font-bold tracking-tight text-slate-900 leading-tight">
                Beranda
            </h1>
        </div>

        <div class="inline-flex items-center gap-2 rounded-full glass-card px-3 py-1 text-[11px] font-semibold shadow-sm">
            <span class="h-1.5 w-1.5 rounded-full {{ $isLibraryOpen ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
            <span class="text-slate-700 dark:text-slate-200">
                {{ $isLibraryOpen ? 'Ruang Baca Buka Sekarang' : 'Ruang Baca Tutup' }}
            </span>
            <span class="text-slate-400">•</span>
            <span class="text-emerald-800 dark:text-emerald-400 font-bold">
                {{ $dayOfWeek === 5 ? '08.30–14.00' : '08.30–15.30' }} WIB
            </span>
        </div>
    </div>

    <!-- ================================================================
         STAGE 1: ASYMMETRIC APPLE BENTO EDITORIAL SHOWCASE (12 COLS)
         ================================================================ -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 sm:gap-4 mb-7">
        <!-- LEFT / PRIMARY BENTO STAGE (7 COLS; Concentric 28px outer = 12px inner + 16px pad) -->
        <div class="lg:col-span-7 glass-card relative overflow-hidden rounded-[28px] p-4 flex flex-col justify-between">
            <!-- Subtle Ambient Liquid Glow Orbs -->
            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-emerald-400/20 blur-3xl dark:bg-emerald-500/15"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-16 h-44 w-44 rounded-full bg-amber-300/20 blur-3xl dark:bg-amber-400/10"></div>

            @if($student)
                @php
                    $firstName = \Illuminate\Support\Str::before($student->nama, ' ');
                    $quotaPercent = $stats['max_quota'] > 0 ? min(100, (int) round(($stats['active_count'] / $stats['max_quota']) * 100)) : 0;
                @endphp
                <div class="relative z-10 flex flex-col h-full justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2.5">
                            <span class="liquid-badge-emerald inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                Kartu Anggota Digital
                            </span>
                            <span class="text-[11px] font-mono font-semibold text-slate-500 dark:text-slate-400">
                                {{ $student->nim_no_anggota }}
                            </span>
                        </div>

                        <h2 class="text-[19px] sm:text-[22px] font-bold tracking-tight text-slate-900 leading-[1.15]">
                            Selamat Datang, <span class="bg-gradient-to-r from-emerald-700 to-teal-600 dark:from-emerald-400 dark:to-teal-300 bg-clip-text text-transparent">{{ $firstName }}</span>
                        </h2>
                        <p class="mt-0.5 text-[12px] text-slate-600 dark:text-slate-300">
                            {{ $student->kelas }} • {{ $student->program_studi }}
                        </p>

                        <!-- Quota Progress Pill Bar -->
                        <div class="mt-3 rounded-[12px] bg-white/65 dark:bg-white/5 border border-white/80 dark:border-white/10 p-3">
                            <div class="flex items-center justify-between text-[11px] font-semibold mb-1.5">
                                <span class="text-slate-600 dark:text-slate-300">Kuota Peminjaman Aktif</span>
                                <span class="text-emerald-800 dark:text-emerald-400">{{ $stats['active_count'] }} dari {{ $stats['max_quota'] }} Buku</span>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200/70 dark:bg-slate-800">
                                <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-700 ease-out" style="width: {{ $quotaPercent }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Concentric 3-Metric Micro Strip -->
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ route('pinjaman', ['tab' => 'dipinjam']) }}" class="apple-press rounded-[12px] bg-white/70 dark:bg-white/5 border border-white/80 dark:border-white/10 p-2.5 hover:bg-white/95 dark:hover:bg-white/10 transition-all">
                            <span class="block text-[11px] font-medium text-slate-500">Dipinjam</span>
                            <span class="mt-0.5 block text-base sm:text-lg font-bold text-slate-900">{{ $stats['borrowed_count'] }}</span>
                        </a>

                        <a href="{{ route('pinjaman', ['tab' => 'siap']) }}" class="apple-press rounded-[12px] bg-white/70 dark:bg-white/5 border border-white/80 dark:border-white/10 p-2.5 hover:bg-white/95 dark:hover:bg-white/10 transition-all">
                            <span class="block text-[11px] font-medium text-slate-500">Siap Diambil</span>
                            <div class="mt-0.5 flex items-center gap-1 flex-wrap">
                                <span class="text-base sm:text-lg font-bold text-slate-900">{{ $stats['booked_count'] }}</span>
                                @if($stats['booked_count'] > 0 && $stats['latest_booking_code'])
                                    <span class="liquid-badge-amber rounded-full px-1.5 py-0.5 text-[11px] font-bold">{{ $stats['latest_booking_code'] }}</span>
                                @endif
                            </div>
                        </a>

                        <a href="{{ route('pinjaman', ['tab' => 'denda']) }}" class="apple-press rounded-[12px] bg-white/70 dark:bg-white/5 border border-white/80 dark:border-white/10 p-2.5 hover:bg-white/95 dark:hover:bg-white/10 transition-all">
                            <span class="block text-[11px] font-medium text-slate-500">Denda Aktif</span>
                            <span class="mt-0.5 block text-[14px] sm:text-base font-bold {{ $stats['fine_amount'] > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900' }}">
                                Rp {{ number_format($stats['fine_amount'], 0, ',', '.') }}
                            </span>
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ $stats['active_count'] > 0 ? route('pinjaman') : route('katalog') }}" class="btn-emerald inline-flex min-h-[44px] sm:min-h-[38px] items-center gap-1.5 rounded-full px-4 text-xs font-semibold">
                            <span>{{ $stats['active_count'] > 0 ? 'Kelola Pinjaman Saya' : 'Jelajahi Katalog Buku' }}</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                        <a href="{{ route('favorit') }}" class="btn-glass inline-flex min-h-[44px] sm:min-h-[38px] items-center gap-1.5 rounded-full px-4 sm:px-3.5 text-xs font-semibold">
                            <span>Favorit Saya</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="relative z-10 flex flex-col h-full justify-between gap-4">
                    <div>
                        <h2 class="text-[20px] sm:text-[25px] font-bold tracking-tight text-slate-900 leading-[1.14]">
                            Baca Lebih Cerdas, <br class="hidden sm:inline">
                            <span class="bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-500 dark:from-emerald-400 dark:to-teal-300 bg-clip-text text-transparent">Pinjam Lebih Cepat.</span>
                        </h2>

                        <p class="mt-2 text-slate-600 dark:text-slate-300 text-[12.5px] leading-relaxed max-w-lg">
                            Jelajahi literatur Akuntansi, Perpajakan, Audit, hingga arsip Tugas Akhir &amp; Laporan PKL Polines. Pesan buku secara daring dan ambil langsung di ruang baca.
                        </p>
                    </div>

                    <!-- Micro Editorial Stats Row -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="rounded-[12px] bg-white/65 dark:bg-white/5 border border-white/80 dark:border-white/10 px-3 py-2">
                            <span class="block text-base sm:text-lg font-bold tracking-tight text-slate-900">{{ $librarySummary['total_titles'] ?? $popularBooks->count() }}</span>
                            <span class="block text-[11px] font-medium text-slate-500">Judul Koleksi</span>
                        </div>
                        <div class="rounded-[12px] bg-white/65 dark:bg-white/5 border border-white/80 dark:border-white/10 px-3 py-2">
                            <span class="block text-base sm:text-lg font-bold tracking-tight text-emerald-800 dark:text-emerald-400">{{ $librarySummary['available_copies'] ?? 0 }}</span>
                            <span class="block text-[11px] font-medium text-slate-500">Eksemplar Siap</span>
                        </div>
                        <div class="rounded-[12px] bg-white/65 dark:bg-white/5 border border-white/80 dark:border-white/10 px-3 py-2">
                            <span class="block text-base sm:text-lg font-bold tracking-tight text-slate-900">7&ndash;14 Hari</span>
                            <span class="block text-[11px] font-medium text-slate-500">Durasi Pinjam</span>
                        </div>
                    </div>

                    <!-- Primary Action Pair -->
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('katalog') }}" class="btn-emerald inline-flex min-h-[44px] sm:min-h-[38px] items-center gap-1.5 rounded-full px-4 text-xs font-semibold">
                            <span>Mulai Jelajah Katalog</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                        <a href="{{ route('tata-tertib') }}" class="btn-glass inline-flex min-h-[44px] sm:min-h-[38px] items-center gap-1.5 rounded-full px-4 sm:px-3.5 text-xs font-semibold">
                            <span>Ketentuan Pinjam</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT / APPLE BOOKS 3D SPOTLIGHT STAGE (5 COLS; Concentric 28px outer = 11px/12px inner + 16px pad) -->
        <div class="lg:col-span-5 glass-card spotlight-card relative overflow-hidden rounded-[28px] p-4 flex flex-col justify-between">
            <div class="flex items-center justify-between gap-2 mb-3">
                <div>
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Sorotan Pilihan</span>
                    <h3 class="text-[14px] font-bold tracking-tight text-slate-900">Rekomendasi Pustakawan</h3>
                </div>
                @if($spotlightList->count() > 1)
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="stepSpotlight(-1)" aria-label="Buku sebelumnya" class="liquid-icon-btn grid h-11 w-11 sm:h-8 sm:w-8 place-items-center rounded-full">
                            <svg class="h-4 w-4 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                        </button>
                        <button type="button" onclick="stepSpotlight(1)" aria-label="Buku berikutnya" class="liquid-icon-btn grid h-11 w-11 sm:h-8 sm:w-8 place-items-center rounded-full">
                            <svg class="h-4 w-4 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Spotlight Carousel Slides -->
            <div id="spotlightDeck" class="relative flex-1 flex items-center">
                @forelse($spotlightList as $idx => $sBook)
                    @php
                        $sCoverUrl = $sBook->cover_url;
                        $sArt = abs(crc32($sBook->judul)) % 6;
                    @endphp
                    <div data-spotlight-slide="{{ $idx }}" class="spotlight-slide {{ $idx === 0 ? 'is-active flex' : 'hidden' }} w-full items-center gap-4">
                        <!-- Angled 3D Book Cover -->
                        <a href="{{ route('buku.detail', $sBook->id) }}" class="apple-press shrink-0 w-[82px] sm:w-[94px]">
                            <div class="spotlight-cover-3d aspect-[5/7] w-full overflow-hidden rounded-[11px] book-shadow border border-white/40 dark:border-white/10">
                                @if($sCoverUrl)
                                    <img src="{{ $sCoverUrl }}" alt="{{ $sBook->judul }}" class="h-full w-full object-cover">
                                @else
                                    <div class="cover-art-{{ $sArt }} flex h-full w-full flex-col justify-between p-3.5">
                                        <div class="font-serif text-[13px] font-semibold leading-tight line-clamp-4">{{ $sBook->judul }}</div>
                                        <div class="text-[9px] uppercase tracking-wider opacity-75 line-clamp-1">{{ $sBook->penulis }}</div>
                                    </div>
                                @endif
                            </div>
                        </a>

                        <!-- Info -->
                        <div class="min-w-0 flex-1 space-y-2.5">
                            @if($sBook->category)
                                <span class="liquid-badge-emerald inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold">
                                    {{ $sBook->category->nama }}
                                </span>
                            @endif

                            <h4 class="text-[14px] sm:text-[15px] font-bold tracking-tight text-slate-900 leading-snug line-clamp-2">
                                {{ $sBook->judul }}
                            </h4>

                            <p class="text-[11.5px] font-medium text-slate-500 line-clamp-1">
                                {{ $sBook->penulis }}
                            </p>

                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold {{ $sBook->stok > 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-600' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $sBook->stok > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $sBook->stok > 0 ? "{$sBook->stok} tersedia" : 'Habis' }}
                                </span>
                            </div>

                            <div class="pt-0.5">
                                <a href="{{ route('buku.detail', $sBook->id) }}" class="btn-glass inline-flex min-h-[44px] sm:min-h-[34px] items-center gap-1 rounded-full px-3.5 sm:px-3 text-[11.5px] font-semibold">
                                    <span>Lihat Detail</span>
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">Koleksi buku akan tampil di sini.</p>
                @endforelse
            </div>

            <!-- Interactive Indicator Dots -->
            @if($spotlightList->count() > 1)
                <div class="mt-3 flex items-center justify-between pt-2 border-t border-slate-200/50 dark:border-white/10">
                    <div class="flex items-center gap-1.5" id="spotlightDots">
                        @foreach($spotlightList as $idx => $sBook)
                            <button type="button"
                                    onclick="goSpotlight({{ $idx }})"
                                    data-spotlight-dot="{{ $idx }}"
                                    aria-label="Pilih buku sorotan {{ $idx + 1 }}"
                                    class="spotlight-dot {{ $idx === 0 ? 'is-active bg-emerald-600 dark:bg-emerald-400' : 'bg-slate-300 dark:bg-slate-600 hover:bg-emerald-400' }}">
                            </button>
                        @endforeach
                    </div>
                    <span id="spotlightCounter" class="text-[11px] font-semibold text-slate-400 tabular-nums">
                        1 / {{ $spotlightList->count() }}
                    </span>
                </div>
            @endif
        </div>
    </section>

    <!-- ================================================================
         STAGE 2: INTERACTIVE TABBED SHELF (GLIDING APPLE SEGMENTED CONTROL)
         ================================================================ -->
    <section class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2.5 mb-3.5">
            <div>
                <h2 class="text-[16px] sm:text-[18px] font-bold tracking-tight text-slate-900">Etalase Koleksi</h2>
                <p class="text-[11.5px] text-slate-500 mt-0.5">Pindah rak secara instan dengan sentuhan</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Gliding Apple Segment Track (Buttons handled smoothly in JS) -->
                <div id="berandaShelfTabs" class="segment-track inline-flex rounded-full p-1 overflow-x-auto no-scrollbar max-w-full">
                    <button type="button" onclick="switchBerandaShelf('populer', this)" data-shelf-tab="populer"
                            class="is-active inline-flex min-h-[44px] sm:min-h-[32px] items-center rounded-full px-3.5 sm:px-3 text-[12px] sm:text-[11.5px] font-semibold text-emerald-800 dark:text-emerald-300 transition-colors whitespace-nowrap">
                        Populer
                    </button>
                    <button type="button" onclick="switchBerandaShelf('terbaru', this)" data-shelf-tab="terbaru"
                            class="inline-flex min-h-[44px] sm:min-h-[32px] items-center rounded-full px-3.5 sm:px-3 text-[12px] sm:text-[11.5px] font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 transition-colors whitespace-nowrap">
                        Terbaru
                    </button>
                    <button type="button" onclick="switchBerandaShelf('pelajaran', this)" data-shelf-tab="pelajaran"
                            class="inline-flex min-h-[44px] sm:min-h-[32px] items-center rounded-full px-3.5 sm:px-3 text-[12px] sm:text-[11.5px] font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 transition-colors whitespace-nowrap">
                        Pelajaran
                    </button>
                    <button type="button" onclick="switchBerandaShelf('skripsi', this)" data-shelf-tab="skripsi"
                            class="inline-flex min-h-[44px] sm:min-h-[32px] items-center rounded-full px-3.5 sm:px-3 text-[12px] sm:text-[11.5px] font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 transition-colors whitespace-nowrap">
                        TA / Skripsi
                    </button>
                </div>

                <a href="{{ route('katalog') }}" class="liquid-chip inline-flex min-h-[44px] sm:min-h-[32px] items-center shrink-0 whitespace-nowrap rounded-full px-3.5 sm:px-3 text-[11.5px] font-semibold text-emerald-800 dark:text-emerald-300">
                    Semua Koleksi &rarr;
                </a>
            </div>
        </div>

        <!-- SHELF PANELS (Zero-Reload Fluid Crossfade) -->
        @php
            $shelves = [
                'populer' => ['books' => $popularBooks->take(4), 'empty' => 'Belum ada data buku populer.'],
                'terbaru' => ['books' => $newestBooks->take(4), 'empty' => 'Belum ada koleksi terbaru.'],
                'pelajaran' => ['books' => ($pelajaranBooks ?? collect())->take(4), 'empty' => 'Belum ada koleksi kategori Pelajaran.'],
                'skripsi' => ['books' => ($thesisBooks ?? collect())->take(4), 'empty' => 'Belum ada koleksi TA / Skripsi atau PKL.'],
            ];
        @endphp

        @foreach($shelves as $shelfKey => $shelfData)
            <div data-shelf-panel="{{ $shelfKey }}" class="shelf-panel {{ $shelfKey === 'populer' ? 'is-active grid' : 'hidden' }} grid-cols-2 gap-x-3.5 gap-y-5 sm:grid-cols-3 lg:grid-cols-4">
                @forelse($shelfData['books'] as $book)
                    @include('front.components.book-card', ['book' => $book])
                @empty
                    <div class="col-span-full glass-card rounded-[18px] p-5 text-center text-xs text-slate-500">
                        {{ $shelfData['empty'] }}
                    </div>
                @endforelse
            </div>
        @endforeach
    </section>

    <!-- ================================================================
         STAGE 3: KATEGORI BENTO TILES (APPLE APP STORE / ARCADE STYLE)
         ================================================================ -->
    @if(($categories ?? collect())->count() > 0)
        <section class="mb-8 apple-reveal">
            <div class="flex items-center justify-between gap-3 mb-3.5">
                <div>
                    <h2 class="text-[16px] sm:text-[18px] font-bold tracking-tight text-slate-900">Telusuri Berdasarkan Kategori</h2>
                    <p class="text-[11.5px] text-slate-500 mt-0.5">Pilih bidang studi atau jenis referensi akuntansi</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-2.5">
                @foreach($categories as $cIdx => $cat)
                    <a href="{{ route('katalog', ['kategori' => $cat->slug]) }}"
                       style="--stagger: {{ $cIdx }}"
                       class="apple-reveal glass-card glass-card-hover apple-press group rounded-[20px] p-3 flex flex-col justify-between min-h-[82px]">
                        <div class="flex items-center justify-between gap-2">
                            <span class="grid h-7 w-7 place-items-center rounded-[8px] bg-emerald-500/12 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20 transition-transform duration-300 group-hover:scale-110">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            </span>
                            <svg class="h-3.5 w-3.5 text-slate-400 transition-transform duration-300 group-hover:translate-x-1 group-hover:text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>

                        <div class="mt-2.5">
                            <h3 class="text-[12.5px] font-bold text-slate-900 group-hover:text-emerald-800 dark:group-hover:text-emerald-400 transition-colors line-clamp-1">
                                {{ $cat->nama }}
                            </h3>
                            <p class="text-[11px] font-medium text-slate-500 mt-0.5">
                                {{ $cat->books_count }} Judul
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- ================================================================
         STAGE 4: SPATIAL CONCIERGE & LOKASI BENTO DOCK (3 SLEEK CARDS; Concentric 24px -> 12px)
         ================================================================ -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3.5 apple-reveal">
        <!-- Bento Card A: Interactive Apple Maps Widget (5 cols) -->
        <div class="lg:col-span-5 glass-card rounded-[24px] p-3 flex flex-col justify-between gap-2.5">
            <div class="relative w-full h-[150px] rounded-[12px] overflow-hidden border border-white/60 dark:border-white/10 shadow-inner bg-slate-100 dark:bg-slate-800">
                <iframe
                    title="Peta Lokasi Griya HiMA Polines"
                    src="https://maps.google.com/maps?q=Griya+HiMA+Polines,+Tembalang,+Semarang&ll=-7.0513902,110.4341623&z=17&ie=UTF8&iwloc=&output=embed"
                    class="w-full h-full border-0 filter contrast-[1.04]"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <div class="flex items-center justify-between gap-2.5 px-1">
                <div class="min-w-0">
                    <span class="block text-[12px] font-bold text-slate-900 truncate">Gedung Jurusan Akuntansi Polines (Griya HiMA)</span>
                    <span class="block text-[11px] text-slate-500 truncate">Tembalang, Kota Semarang 50275</span>
                </div>
                <a href="https://maps.app.goo.gl/65DaCK38rPP5mJ459" target="_blank" rel="noopener noreferrer"
                   class="btn-glass inline-flex min-h-[44px] sm:min-h-[34px] shrink-0 items-center gap-1 rounded-full px-3.5 sm:px-3 text-[11.5px] font-semibold text-emerald-800 dark:text-emerald-400">
                    <span>Maps</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                </a>
            </div>
        </div>

        <!-- Bento Card B: Jam Pelayanan & Sirkulasi (4 cols; Concentric 26px = 10px inner + 16px pad) -->
        <div class="lg:col-span-4 glass-card rounded-[26px] p-4 flex flex-col justify-between gap-3">
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="liquid-badge-emerald inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold">
                        <span class="h-1.5 w-1.5 rounded-full {{ $isLibraryOpen ? 'bg-emerald-600 animate-pulse' : 'bg-amber-500' }}"></span>
                        {{ $isLibraryOpen ? 'Buka' : 'Tutup' }}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-400">WIB</span>
                </div>
                <h3 class="text-[14px] font-bold tracking-tight text-slate-900">Jam Pelayanan Ruang Baca</h3>
                <p class="mt-0.5 text-[11.5px] text-slate-500 leading-relaxed">Pengambilan booking dan pengembalian fisik dilayani sesuai jam operasional.</p>
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between rounded-[10px] bg-white/70 dark:bg-white/5 border border-white/80 dark:border-white/10 px-3 py-2 text-[11.5px]">
                    <span class="font-semibold text-slate-600 dark:text-slate-300">Senin – Kamis</span>
                    <span class="font-bold text-slate-900">08.30 – 15.30</span>
                </div>
                <div class="flex items-center justify-between rounded-[10px] bg-white/70 dark:bg-white/5 border border-white/80 dark:border-white/10 px-3 py-2 text-[11.5px]">
                    <span class="font-semibold text-slate-600 dark:text-slate-300">Jumat</span>
                    <span class="font-bold text-slate-900">08.30 – 14.00</span>
                </div>
            </div>
        </div>

        <!-- Bento Card C: Concierge Admin & Tata Tertib (3 cols; Concentric 26px = 10px inner + 16px pad) -->
        <div class="md:col-span-2 lg:col-span-3 glass-card rounded-[26px] p-4 flex flex-col justify-between gap-3">
            <div>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-[10px] bg-emerald-500/12 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20 mb-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                </span>
                <h3 class="text-[14px] font-bold tracking-tight text-slate-900">Bantuan Pustakawan</h3>
                <p class="mt-0.5 text-[11.5px] text-slate-500 leading-relaxed">Layanan cetak, konsultasi koleksi, atau reset akun anggota.</p>
            </div>

            <div class="space-y-1.5">
                <a href="https://wa.me/62895336384919?text=Halo%20Admin%20Perpustakaan%20Akuntansi%20Polines" target="_blank"
                   class="btn-glass flex min-h-[44px] sm:min-h-[34px] w-full items-center justify-center gap-2 rounded-[10px] px-3 text-[11.5px] font-semibold text-emerald-800 dark:text-emerald-400">
                    <span>Chat WhatsApp Admin</span>
                </a>
                <a href="{{ route('tata-tertib') }}"
                   class="btn-glass flex min-h-[44px] sm:min-h-[34px] w-full items-center justify-center gap-2 rounded-[10px] px-3 text-[11.5px] font-semibold text-slate-700 dark:text-slate-200">
                    <span>Baca Tata Tertib &rarr;</span>
                </a>
            </div>
        </div>
    </section>
@endsection
