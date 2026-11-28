@extends('front.layout')

@section('title', $book->judul . ' — Perpustakaan Jurusan Akuntansi')

@php
    $coverUrl = $book->cover_url;
    $artIndex = abs(crc32($book->judul)) % 6;
    $loanDuration = $book->getLoanDurationWeekdays();
    $estReturn = today()->copy()->addWeekdays($loanDuration);
    $availableToBook = max(0, $book->stok - ($activeBookingsCount ?? 0));
@endphp

@section('content')
    <!-- BREADCRUMB -->
    <nav class="mb-4 flex items-center gap-1.5 text-[11.5px] text-slate-500">
        <a href="{{ route('katalog') }}" class="hover:text-emerald-800 dark:hover:text-emerald-400 hover:underline">Katalog</a>
        <span>/</span>
        <a href="{{ route('katalog', ['kategori' => $book->category?->slug ?? 'semua']) }}" class="hover:text-emerald-800 dark:hover:text-emerald-400 hover:underline">
            {{ $book->category?->nama ?? 'Umum' }}
        </a>
        <span>/</span>
        <span class="truncate text-slate-800 dark:text-slate-200 font-medium max-w-[200px] sm:max-w-none">{{ $book->judul }}</span>
    </nav>

    <!-- BOOK MAIN HERO SECTION -->
    <div class="grid grid-cols-1 gap-6 md:gap-7 md:grid-cols-12 lg:gap-8">
        <!-- LEFT: COVER & FAVORITE BUTTON -->
        <div class="md:col-span-4 lg:col-span-3 flex flex-col items-center md:items-start">
            <div class="relative aspect-[5/7] w-full max-w-[165px] sm:max-w-[185px] md:max-w-[205px] overflow-hidden rounded-[11px] book-shadow dark:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.8)]">
                @if($coverUrl)
                    <img src="{{ $coverUrl }}" alt="Sampul {{ $book->judul }}" class="h-full w-full object-cover">
                @else
                    <div class="cover-art-{{ $artIndex }} flex h-full w-full flex-col justify-between p-4">
                        <div class="font-serif text-[17px] sm:text-[19px] font-semibold leading-[1.12] tracking-tight">
                            {{ $book->judul }}
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11.5px] font-medium opacity-90">{{ $book->penulis }}</p>
                            <p class="text-[8.5px] uppercase tracking-widest opacity-60">Perpustakaan Jurusan Akuntansi</p>
                        </div>
                    </div>
                @endif
            </div>

            @if($student)
            @php
                $isFavorited = $book->isFavoritedBy($student);
            @endphp
            <button type="button"
                    id="btnToggleFav"
                    onclick="toggleFavoriteBook({{ $book->id }})"
                    class="{{ $isFavorited ? 'btn-amber-gold' : 'btn-glass text-slate-700 dark:text-slate-200' }} mt-3.5 flex min-h-[44px] sm:min-h-[38px] w-full max-w-[165px] sm:max-w-[185px] md:max-w-[205px] items-center justify-center gap-1.5 rounded-full px-3.5 text-xs font-semibold">
                <svg id="favStarIcon" class="h-3.5 w-3.5 shrink-0 {{ $isFavorited ? 'text-amber-700 fill-amber-600' : 'text-amber-500' }}" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
                <span id="favTextLabel">{{ $isFavorited ? 'Tersimpan di Favorit' : 'Simpan ke Favorit' }}</span>
            </button>
            @endif
        </div>

        <!-- RIGHT: BOOK SPECS & BORROW ACTION -->
        <div class="md:col-span-8 lg:col-span-9 flex flex-col justify-between">
            <div>
                <!-- Category & Prodi Tags (Liquid Glass Badges) -->
                <div class="flex flex-wrap items-center gap-1.5 mb-2">
                    <span class="inline-flex items-center rounded-full liquid-badge-emerald px-2.5 py-0.5 text-[11px] font-semibold">
                        {{ $book->category?->nama ?? 'Buku Akuntansi' }}
                    </span>
                    @if(filled($book->program_studi))
                        <a href="{{ route('katalog', ['prodi' => $book->program_studi]) }}"
                           class="inline-flex items-center rounded-full {{ $book->program_studi_level === 'D3' ? 'liquid-badge-amber' : 'liquid-chip' }} px-2.5 py-0.5 text-[11px] font-semibold hover:opacity-90 transition">
                            {{ $book->program_studi }}
                        </a>
                    @endif
                </div>

                <!-- Title & Author -->
                <h1 class="text-[21px] sm:text-[25px] font-bold tracking-tight text-slate-900 leading-tight">
                    {{ $book->judul }}
                </h1>
                <p class="mt-0.5 text-[13.5px] font-medium text-slate-600 dark:text-slate-300">
                    {{ $book->penulis }}
                </p>

                <!-- Metadata Grid -->
                <div class="mt-4 grid grid-cols-2 gap-y-2.5 gap-x-5 text-[12.5px] border-t border-b border-slate-200/70 dark:border-white/10 py-3.5">
                    <div>
                        <span class="block text-slate-500 text-[11px]">Penerbit</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $book->penerbit ?? 'Pustaka Jurusan' }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-[11px]">Tahun &amp; Edisi</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $book->tahun_terbit ?? '2024' }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-[11px]">ISBN / Kode Buku</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $book->isbn ?? $book->book_code ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-[11px]">Jenis Koleksi</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $book->collection_type_label }}</span>
                    </div>
                    @if(filled($book->program_studi))
                        <div>
                            <span class="block text-slate-500 text-[11px]">Program Studi</span>
                            <a href="{{ route('katalog', ['prodi' => $book->program_studi]) }}" class="font-medium text-emerald-700 dark:text-emerald-400 hover:underline">
                                {{ $book->program_studi }}
                            </a>
                        </div>
                    @endif
                    <div>
                        <span class="block text-slate-500 text-[11px]">Ketersediaan Stok</span>
                        <span class="font-medium {{ $availableToBook > 0 ? 'text-emerald-800 dark:text-emerald-400' : ($book->stok > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-rose-600 dark:text-rose-400') }}">
                            @if($availableToBook > 0)
                                Tersedia {{ $availableToBook }} Eksemplar
                            @elseif($book->stok > 0)
                                Sedang Di-booking ({{ $book->stok }} Eksemplar)
                            @else
                                Sedang Habis
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-[11px]">Aturan Peminjaman</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $loanDuration }} Hari Kerja • Denda Rp {{ $book->collection_type === 'ta_skripsi' ? '300' : '200' }}/hari</span>
                    </div>
                </div>

                <!-- Availability Status Badge -->
                <div class="mt-3.5">
                    <div class="inline-flex items-center gap-2 rounded-full liquid-chip px-3 py-1 text-[11.5px] font-medium">
                        <span class="h-1.5 w-1.5 rounded-full {{ $availableToBook > 0 ? 'bg-emerald-500' : ($book->stok > 0 ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                        <span>
                            @if($availableToBook > 0)
                                Siap dipinjam di Ruang Baca Jurusan
                            @elseif($book->stok > 0)
                                Seluruh eksemplar sedang menunggu pengambilan booking
                            @else
                                Stok sedang tidak tersedia
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Action Button Section -->
                <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center gap-2.5">
                    @if(session('student_id'))
                        @if($availableToBook > 0)
                            <button type="button" onclick="openAppleModal('bookingModal')"
                                    class="btn-emerald min-h-[44px] sm:min-h-[38px] rounded-full px-5 text-[13px] font-semibold w-full sm:w-auto">
                                Ajukan Pinjam Buku
                            </button>
                            <span class="text-[11.5px] text-slate-500 font-medium">Durasi {{ $loanDuration }} hari kerja • Ambil maks. 1&times;24 jam</span>
                        @elseif($book->stok > 0)
                            <button type="button" disabled class="min-h-[44px] sm:min-h-[38px] rounded-full px-5 text-[13px] font-semibold bg-amber-100/80 dark:bg-amber-500/15 text-amber-800 dark:text-amber-300 cursor-not-allowed">
                                Sedang Di-booking Mahasiswa Lain
                            </button>
                        @else
                            <button type="button" disabled class="min-h-[44px] sm:min-h-[38px] rounded-full px-5 text-[13px] font-semibold bg-slate-200 dark:bg-white/10 text-slate-400 cursor-not-allowed">
                                Stok Buku Sedang Habis
                            </button>
                        @endif
                    @else
                        <!-- Guest User CTA -->
                        <a href="{{ route('masuk') }}" class="btn-amber-gold flex min-h-[44px] sm:min-h-[38px] items-center justify-center rounded-full px-5 text-[13px] font-semibold w-full sm:w-auto shadow-md">
                            Masuk untuk Meminjam Buku
                        </a>
                        <span class="text-[11.5px] text-slate-500 font-medium">Durasi {{ $loanDuration }} hari kerja • Ambil fisik di ruang baca</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- DESKRIPSI BUKU -->
    <section class="mt-8 max-w-3xl">
        <h2 class="text-[16px] font-bold tracking-tight text-slate-900 mb-2">Deskripsi</h2>
        <div class="text-[13px] leading-relaxed text-slate-600 dark:text-slate-300">
            {{ $book->sinopsis ?? 'Buku koleksi ruang baca Jurusan Akuntansi Politeknik Negeri Semarang, memuat materi pembelajaran perkuliahan, teori akuntansi, dan studi kasus praktis.' }}
        </div>
    </section>

    <!-- BUKU TERKAIT -->
    <section class="mt-8 border-t border-slate-200/70 dark:border-white/10 pt-5">
        <h2 class="text-[16px] font-bold tracking-tight text-slate-900 mb-3.5">Buku Terkait</h2>
        <div class="grid grid-cols-2 gap-x-3.5 gap-y-5 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            @foreach($relatedBooks as $rel)
                @include('front.components.book-card', ['book' => $rel])
            @endforeach
        </div>
    </section>

    <!-- MODAL KONFIRMASI PEMINJAMAN FISIK (Apple Liquid Glass) -->
    @if($student)
        <div id="bookingModal" class="apple-modal-root fixed inset-0 z-50 hidden flex items-center justify-center p-4">
            <!-- Scrim Backdrop -->
            <div class="glass-modal-backdrop fixed inset-0 bg-slate-950/60 backdrop-blur-md" onclick="closeAppleModal('bookingModal')"></div>

            <!-- Dialog Window (Concentric 26px outer = 12px inner + 14px pad) -->
            <div class="glass-card glass-popover-sheet glass-modal-panel relative z-10 w-full max-w-[400px] max-h-[90vh] overflow-y-auto rounded-[26px] p-3.5 sm:p-4 shadow-2xl border border-white/95 dark:border-white/10">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-[0.12em] text-emerald-700 dark:text-emerald-400">Booking Fisik</span>
                        <h3 class="text-[17px] font-bold tracking-tight text-slate-900 leading-snug mt-0.5">Konfirmasi Peminjaman</h3>
                    </div>
                    <button type="button" onclick="closeAppleModal('bookingModal')" aria-label="Tutup" class="liquid-icon-btn grid h-11 w-11 sm:h-8 sm:w-8 shrink-0 place-items-center rounded-full text-slate-500">
                        <svg class="h-4 w-4 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Mini Book Info -->
                <div class="mt-3.5 flex items-center gap-3 rounded-[12px] bg-white/60 dark:bg-white/5 border border-white/80 dark:border-white/10 p-2.5">
                    <div class="relative aspect-[5/7] w-11 shrink-0 overflow-hidden rounded-[6px] book-shadow">
                        @if($coverUrl)
                            <img src="{{ $coverUrl }}" alt="{{ $book->judul }}" class="h-full w-full object-cover">
                        @else
                            <div class="cover-art-{{ $artIndex }} h-full w-full p-1 flex flex-col justify-end">
                                <span class="font-serif text-[6.5px] font-bold leading-tight line-clamp-3">{{ $book->judul }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="inline-flex items-center rounded-full liquid-badge-emerald px-2 py-0.5 text-[11px] font-semibold mb-1">{{ $book->collection_type_label }}</span>
                        <h4 class="text-[13.5px] font-bold text-slate-900 leading-snug line-clamp-2">{{ $book->judul }}</h4>
                        <p class="text-[11.5px] text-slate-500 truncate mt-0.5">{{ $book->penulis }}</p>
                    </div>
                </div>

                <!-- Form Action -->
                <form action="{{ route('buku.booking', $book->id) }}" method="POST" class="mt-3.5">
                    @csrf

                    <!-- Ketentuan Pinjam Summary -->
                    <div class="pearl-card rounded-[12px] p-3.5 text-[12px] leading-relaxed text-slate-600 dark:text-slate-300 space-y-1.5 border border-white/80 dark:border-white/10">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-500 dark:text-slate-400">Durasi Pinjam</span>
                            <span class="font-semibold text-slate-900 dark:text-white tabular-nums">{{ $loanDuration }} hari kerja (+{{ $loanDuration }} hari perpanjangan)</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-500 dark:text-slate-400">Estimasi Kembali*</span>
                            <span class="font-semibold text-emerald-800 dark:text-emerald-400 tabular-nums">{{ $estReturn->locale('id')->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-500 dark:text-slate-400">Batas Ambil</span>
                            <span class="font-semibold text-amber-700 dark:text-amber-400 tabular-nums">Maks. 1&times;24 jam</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-slate-500 dark:text-slate-400">Denda Telat</span>
                            <span class="font-semibold text-rose-600 dark:text-rose-400 tabular-nums">Rp {{ $book->collection_type === 'ta_skripsi' ? '300' : '200' }} / hari kerja</span>
                        </div>
                        <p class="pt-1.5 mt-1 border-t border-slate-200/60 dark:border-white/10 text-[11px] text-slate-500 dark:text-slate-400">
                            *Masa pinjam mulai berjalan saat buku diambil di ruang baca dengan menitipkan 1 kartu jaminan (KTM/KTP/Asuransi).
                        </p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="mt-4 flex gap-2.5">
                        <button type="button" onclick="closeAppleModal('bookingModal')"
                                class="btn-glass w-28 min-h-[44px] sm:min-h-[40px] rounded-full text-[13px] font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                                class="btn-emerald flex-1 min-h-[44px] sm:min-h-[40px] rounded-full text-[13px] font-semibold">
                            Konfirmasi Pinjam
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function toggleFavoriteBook(bookId) {
                const btn = document.getElementById('btnToggleFav');
                const label = document.getElementById('favTextLabel');
                const icon = document.getElementById('favStarIcon');

                fetch(`/buku/${bookId}/favorit`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.is_favorited) {
                            btn.className = 'btn-amber-gold mt-3.5 flex min-h-[44px] sm:min-h-[38px] w-full max-w-[165px] sm:max-w-[185px] md:max-w-[205px] items-center justify-center gap-1.5 rounded-full px-3.5 text-xs font-semibold';
                            icon.setAttribute('fill', 'currentColor');
                            icon.className = 'h-3.5 w-3.5 shrink-0 text-amber-700 fill-amber-600';
                            label.textContent = 'Tersimpan di Favorit';
                        } else {
                            btn.className = 'btn-glass text-slate-700 dark:text-slate-200 mt-3.5 flex min-h-[44px] sm:min-h-[38px] w-full max-w-[165px] sm:max-w-[185px] md:max-w-[205px] items-center justify-center gap-1.5 rounded-full px-3.5 text-xs font-semibold';
                            icon.setAttribute('fill', 'none');
                            icon.className = 'h-3.5 w-3.5 shrink-0 text-amber-500';
                            label.textContent = 'Simpan ke Favorit';
                        }
                    }
                })
                .catch(err => console.error(err));
            }
        </script>
    @endif
@endsection
