@extends('front.layout')

@section('title', 'Pinjaman Saya — Perpustakaan Jurusan Akuntansi')

@section('content')
    @php
        $totalActiveCount = $siapDiambil->count() + $sedangDipinjam->count();
    @endphp

    <!-- APPLE TYPOGRAPHY HEADER & QUOTA SUMMARY -->
    <header class="mb-4 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-[21px] sm:text-[24px] font-bold tracking-tight text-slate-900 leading-tight">
                Pinjaman Saya
            </h1>
            <p class="mt-0.5 text-[12.5px] text-slate-600 dark:text-slate-300">
                Pantau booking, masa pinjam, perpanjangan, dan denda aktif Anda.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 rounded-full glass-card px-3 py-1 text-[11.5px] font-semibold tabular-nums shadow-xs">
            <span class="h-1.5 w-1.5 rounded-full {{ $totalActiveCount >= 2 ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
            <span class="text-slate-700 dark:text-slate-200">Kuota aktif {{ $totalActiveCount }} dari 2 buku</span>
        </div>
    </header>

    <!-- INSTANT 0ms SEGMENTED CONTROL WITH GLIDING LIQUID GLASS THUMB -->
    <div id="pinjamanTabTrack" class="segment-track w-full sm:w-fit max-w-full flex flex-nowrap rounded-full mb-5 overflow-x-auto no-scrollbar">
        <button type="button"
                onclick="switchPinjamanTab('siap', this)"
                data-pinjaman-tab="siap"
                class="{{ $activeTab === 'siap' ? 'is-active text-emerald-800 dark:text-emerald-300' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }} shrink-0 inline-flex min-h-[34px] items-center justify-center px-3.5 sm:px-4 text-[11.5px] sm:text-xs font-semibold rounded-full transition-colors whitespace-nowrap tabular-nums">
            Siap Diambil &middot; {{ $siapDiambil->count() }}
        </button>
        <button type="button"
                onclick="switchPinjamanTab('dipinjam', this)"
                data-pinjaman-tab="dipinjam"
                class="{{ $activeTab === 'dipinjam' ? 'is-active text-emerald-800 dark:text-emerald-300' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }} shrink-0 inline-flex min-h-[34px] items-center justify-center px-3.5 sm:px-4 text-[11.5px] sm:text-xs font-semibold rounded-full transition-colors whitespace-nowrap tabular-nums">
            Sedang Dipinjam &middot; {{ $sedangDipinjam->count() }}
        </button>
        <button type="button"
                onclick="switchPinjamanTab('denda', this)"
                data-pinjaman-tab="denda"
                class="{{ $activeTab === 'denda' ? 'is-active text-emerald-800 dark:text-emerald-300' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }} shrink-0 inline-flex min-h-[34px] items-center justify-center px-3.5 sm:px-4 text-[11.5px] sm:text-xs font-semibold rounded-full transition-colors whitespace-nowrap tabular-nums">
            Terlambat &amp; Denda &middot; {{ $terlambat->count() }}
        </button>
    </div>

    <!-- ================================================================
         TAB 1: SIAP DIAMBIL
         ================================================================ -->
    <div data-pinjaman-panel="siap" class="shelf-panel space-y-3.5 {{ $activeTab === 'siap' ? 'is-active block' : 'hidden' }}">
        @forelse($siapDiambil as $loan)
            @php
                $deadline = $loan->booking_expires_at
                    ? $loan->booking_expires_at->locale('id')->translatedFormat('d M Y, H:i') . ' WIB'
                    : 'maks. 1×24 jam';
            @endphp
            <article class="glass-card rounded-[18px] p-4 flex flex-col sm:flex-row sm:items-center gap-4">
                <!-- QR Code Box -->
                <div class="grid h-20 w-20 shrink-0 place-items-center rounded-[12px] p-1.5 shadow-sm bg-white border border-slate-200 dark:border-white/20">
                    <canvas id="qr_{{ $loan->id }}" class="h-full w-full"></canvas>
                </div>

                <!-- Booking Details -->
                <div class="flex-1 min-w-0 space-y-0.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="liquid-badge-emerald inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[10px] font-semibold">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                            Siap Diambil
                        </span>
                        <span class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wider">Kode Booking</span>
                    </div>

                    <h2 class="text-[18px] sm:text-[20px] font-bold tracking-tight text-slate-900 tabular-nums leading-tight">
                        {{ $loan->booking_code }}
                    </h2>

                    <p class="text-[13.5px] font-semibold text-slate-800 dark:text-slate-100 leading-snug">
                        {{ $loan->book?->judul }}
                    </p>

                    <p class="text-[12px] text-slate-500 dark:text-slate-400 tabular-nums">
                        Ambil di ruang baca sebelum <strong class="font-semibold text-slate-800 dark:text-slate-200">{{ $deadline }}</strong>
                    </p>
                </div>

                <!-- Action -->
                <div class="shrink-0 sm:pl-4 sm:border-l sm:border-slate-200/60 dark:sm:border-white/10">
                    <form action="{{ route('pinjaman.batal', $loan->id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan booking ini?')">
                        @csrf
                        <button type="submit" class="btn-glass inline-flex min-h-[34px] w-full sm:w-auto items-center justify-center rounded-full px-3.5 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:border-rose-300">
                            Batalkan Booking
                        </button>
                    </form>
                </div>
            </article>

            <script>
                (function() {
                    const run = function() { renderQrCode("qr_{{ $loan->id }}", "{{ $loan->booking_code }}"); };
                    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', run); } else { run(); }
                })();
            </script>
        @empty
            <div class="glass-card my-4 flex flex-col items-center justify-center rounded-[20px] p-7 text-center">
                <div class="grid h-11 w-11 place-items-center rounded-full bg-slate-100 dark:bg-white/10 text-slate-400 mb-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
                </div>
                <h2 class="text-[15px] font-bold text-slate-900">Belum ada booking aktif</h2>
                <p class="text-[12.5px] text-slate-500 mt-1 max-w-sm">Pilih buku di katalog untuk memesan sebelum datang ke ruang baca.</p>
                <a href="{{ route('katalog') }}" class="btn-emerald mt-3.5 inline-flex min-h-[36px] items-center rounded-full px-4 text-xs font-semibold">
                    Jelajahi Katalog
                </a>
            </div>
        @endforelse
    </div>

    <!-- ================================================================
         TAB 2: SEDANG DIPINJAM
         ================================================================ -->
    <div data-pinjaman-panel="dipinjam" class="shelf-panel space-y-3.5 {{ $activeTab === 'dipinjam' ? 'is-active block' : 'hidden' }}">
        @forelse($sedangDipinjam as $loan)
            @php
                $coverUrl = $loan->book?->cover_url;
                $artIndex = abs(crc32($loan->book?->judul ?? 'buku')) % 6;
                $today = today();
                $due = $loan->jatuh_tempo ? $loan->jatuh_tempo->copy()->startOfDay() : null;
                $isOverdue = $loan->isOverdue();
                $daysDiff = $isOverdue ? $loan->calculateOverdueWeekdays(today()) : ($due ? abs((int) $today->diffInDays($due)) : 0);
                $lateFine = $loan->calculateLateFine(today());
            @endphp
            <article class="glass-card rounded-[18px] p-4 flex flex-col sm:flex-row sm:items-center gap-4">
                <!-- Book Cover -->
                <div class="relative aspect-[5/7] w-12 sm:w-14 shrink-0 overflow-hidden rounded-[7px] book-shadow">
                    @if($coverUrl)
                        <img src="{{ $coverUrl }}" alt="{{ $loan->book?->judul }}" class="h-full w-full object-cover">
                    @else
                        <div class="cover-art-{{ $artIndex }} h-full w-full p-1.5 flex flex-col justify-end">
                            <span class="font-serif text-[6.5px] font-bold leading-tight line-clamp-3">{{ $loan->book?->judul }}</span>
                        </div>
                    @endif
                </div>

                <!-- Main Book Meta -->
                <div class="flex-1 min-w-0 space-y-0.5">
                    @if($isOverdue)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/15 text-rose-700 dark:text-rose-300 px-2.5 py-0.5 text-[10px] font-semibold">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                            Terlambat {{ $daysDiff }} hari kerja
                        </span>
                    @else
                        <span class="liquid-badge-amber inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[10px] font-semibold">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-600"></span>
                            Sisa {{ $daysDiff }} hari
                        </span>
                    @endif

                    <h2 class="text-[14.5px] sm:text-[15.5px] font-bold tracking-tight text-slate-900 leading-snug">
                        {{ $loan->book?->judul }}
                    </h2>

                    <p class="text-[12px] font-medium text-slate-500">
                        {{ $loan->book?->penulis }}
                    </p>

                    <div class="pt-0.5 flex flex-wrap items-center gap-x-3 gap-y-0.5 text-[11.5px] text-slate-500 dark:text-slate-400 tabular-nums">
                        <span>Kode <strong class="font-semibold text-slate-700 dark:text-slate-200">{{ $loan->book?->book_code ?? $loan->booking_code }}</strong></span>
                        <span>Dipinjam <strong class="font-semibold text-slate-700 dark:text-slate-200">{{ $loan->tanggal_pinjam?->locale('id')->translatedFormat('d M Y') }}</strong></span>
                        <span>Jatuh tempo <strong class="font-semibold text-slate-700 dark:text-slate-200">{{ $loan->jatuh_tempo?->locale('id')->translatedFormat('d M Y') }}</strong></span>
                    </div>
                </div>

                <!-- Unified Right Status & Action Block -->
                <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 shrink-0 sm:min-w-[155px] pt-2.5 sm:pt-0 border-t sm:border-t-0 sm:border-l border-slate-200/60 dark:border-white/10 sm:pl-4">
                    <div class="sm:text-right tabular-nums">
                        <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Denda berjalan</span>
                        <span class="text-[16px] font-bold {{ $lateFine > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900' }}">
                            Rp {{ number_format($lateFine, 0, ',', '.') }}
                        </span>
                    </div>

                    @if(! $loan->is_extended && ! $isOverdue)
                        <form action="{{ route('pinjaman.perpanjang', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-glass inline-flex min-h-[34px] items-center rounded-full px-3 text-[11.5px] font-semibold text-emerald-800 dark:text-emerald-300">
                                Perpanjang 3 Hari
                            </button>
                        </form>
                    @elseif($loan->is_extended)
                        <span class="inline-flex min-h-[28px] items-center rounded-full bg-slate-100 dark:bg-white/10 px-2.5 text-[10.5px] font-semibold text-slate-500">
                            Sudah diperpanjang
                        </span>
                    @else
                        <span class="text-[11.5px] font-semibold text-rose-600 dark:text-rose-400">
                            Kembalikan di ruang baca
                        </span>
                    @endif
                </div>
            </article>
        @empty
            <div class="glass-card my-4 flex flex-col items-center justify-center rounded-[20px] p-7 text-center">
                <div class="grid h-11 w-11 place-items-center rounded-full bg-slate-100 dark:bg-white/10 text-slate-400 mb-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                </div>
                <h2 class="text-[15px] font-bold text-slate-900">Tidak ada pinjaman aktif</h2>
                <p class="text-[12.5px] text-slate-500 mt-1 max-w-sm">Buku yang sedang Anda pinjam akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <!-- ================================================================
         TAB 3: TERLAMBAT & DENDA
         ================================================================ -->
    <div data-pinjaman-panel="denda" class="shelf-panel space-y-3.5 {{ $activeTab === 'denda' ? 'is-active block' : 'hidden' }}">
        @forelse($terlambat as $loan)
            @php
                $coverUrl = $loan->book?->cover_url;
                $artIndex = abs(crc32($loan->book?->judul ?? 'buku')) % 6;
                $lateFine = $loan->calculateLateFine(today());
                $daysDiff = $loan->calculateOverdueWeekdays(today());
            @endphp
            <article class="glass-card rounded-[18px] p-4 flex flex-col sm:flex-row sm:items-center gap-4 border-rose-200/80 dark:border-rose-500/25">
                <!-- Consistent Book Cover -->
                <div class="relative aspect-[5/7] w-12 sm:w-14 shrink-0 overflow-hidden rounded-[7px] book-shadow">
                    @if($coverUrl)
                        <img src="{{ $coverUrl }}" alt="{{ $loan->book?->judul }}" class="h-full w-full object-cover">
                    @else
                        <div class="cover-art-{{ $artIndex }} h-full w-full p-1.5 flex flex-col justify-end">
                            <span class="font-serif text-[6.5px] font-bold leading-tight line-clamp-3">{{ $loan->book?->judul }}</span>
                        </div>
                    @endif
                </div>

                <!-- Details -->
                <div class="flex-1 min-w-0 space-y-0.5">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/15 px-2.5 py-0.5 text-[10px] font-semibold text-rose-700 dark:text-rose-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                        Terlambat {{ $daysDiff }} hari kerja
                    </span>

                    <h2 class="text-[14.5px] sm:text-[15.5px] font-bold tracking-tight text-slate-900 leading-snug">
                        {{ $loan->book?->judul }}
                    </h2>

                    <p class="text-[12px] text-slate-500 tabular-nums">
                        Jatuh tempo <strong class="font-semibold text-slate-700 dark:text-slate-200">{{ $loan->jatuh_tempo?->locale('id')->translatedFormat('d F Y') }}</strong>
                    </p>
                </div>

                <!-- Unified Right Fine Block -->
                <div class="sm:text-right shrink-0 pt-2.5 sm:pt-0 border-t sm:border-t-0 sm:border-l border-slate-200/60 dark:border-white/10 sm:pl-4 tabular-nums">
                    <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Estimasi denda</span>
                    <span class="block text-[18px] font-bold tracking-tight text-rose-600 dark:text-rose-400 leading-tight mt-0.5">
                        Rp {{ number_format($lateFine, 0, ',', '.') }}
                    </span>
                    <span class="block text-[11px] text-slate-500 mt-0.5">Bayar ke petugas saat pengembalian</span>
                </div>
            </article>
        @empty
            <div class="glass-card my-4 flex flex-col items-center justify-center rounded-[20px] p-7 text-center">
                <div class="grid h-11 w-11 place-items-center rounded-full bg-emerald-500/15 text-emerald-600 mb-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                </div>
                <h2 class="text-[15px] font-bold text-slate-900">Tidak ada keterlambatan atau denda</h2>
                <p class="text-[12.5px] text-slate-500 mt-1 max-w-sm">Terima kasih selalu mengembalikan buku tepat waktu.</p>
            </div>
        @endforelse
    </div>

    <!-- MODAL SUKSES BOOKING -->
    @if($successBookingCode)
        @php
            $successLoan = \App\Models\Loan::with('book')->where('booking_code', $successBookingCode)->first();
            $successDeadline = $successLoan?->booking_expires_at
                ? $successLoan->booking_expires_at->locale('id')->translatedFormat('d M Y, H:i') . ' WIB'
                : 'maks. 1×24 jam';
        @endphp
        <div id="bookingSuccessModal" class="apple-modal-root fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="glass-modal-backdrop fixed inset-0 bg-slate-900/40 backdrop-blur-md" onclick="closeAppleModal('bookingSuccessModal')"></div>

            <div class="glass-card glass-popover-sheet glass-modal-panel relative z-10 w-full max-w-[390px] max-h-[90vh] overflow-y-auto rounded-[22px] p-4 sm:p-5 text-center shadow-2xl border border-white/95 dark:border-white/10">
                <div class="mx-auto grid h-11 w-11 place-items-center rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 mb-2.5">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                </div>

                <h3 class="text-[17px] font-bold tracking-tight text-slate-900">Booking Berhasil Dibuat</h3>
                <p class="text-[13px] text-slate-500 mt-0.5 line-clamp-2">
                    {{ $successLoan?->book?->judul ?? 'Buku Pilihan' }}
                </p>

                <div class="pearl-card mt-4 rounded-[18px] p-4 shadow-sm border border-slate-200 dark:border-white/10">
                    <div class="mx-auto grid h-32 w-32 place-items-center rounded-[12px] bg-white p-2">
                        <canvas id="successQrCanvas" class="h-full w-full"></canvas>
                    </div>
                    <div class="mt-3 text-[18px] font-bold tracking-tight text-slate-900 tabular-nums">
                        Kode Booking: {{ $successBookingCode }}
                    </div>
                    <p class="mt-1.5 text-[12px] leading-relaxed text-slate-500 dark:text-slate-300">
                        Tunjukkan kode ini ke petugas di ruang baca sebelum <strong class="font-semibold text-slate-800 dark:text-slate-100">{{ $successDeadline }}</strong> (1&times;24 jam). Masa pinjam dimulai saat buku diambil.
                    </p>
                </div>

                <div class="mt-4 flex gap-2.5">
                    <button type="button" onclick="closeAppleModal('bookingSuccessModal')"
                            class="btn-glass flex-1 h-10 rounded-full text-[13px] font-semibold">
                        Tutup
                    </button>
                    <button type="button" onclick="closeAppleModal('bookingSuccessModal'); switchPinjamanTab('siap', document.querySelector('[data-pinjaman-tab=siap]'))"
                            class="btn-emerald flex-1 h-10 rounded-full text-[13px] font-semibold">
                        Lihat Siap Diambil
                    </button>
                </div>
            </div>
        </div>

        <script>
            (function() {
                const run = function() { renderQrCode("successQrCanvas", "{{ $successBookingCode }}"); };
                if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', run); } else { run(); }
            })();
        </script>
    @endif

    <script>
        function switchPinjamanTab(tabKey, btnEl) {
            const track = document.getElementById('pinjamanTabTrack');
            if (track && btnEl) {
                track.querySelectorAll('button').forEach(function(btn) {
                    btn.classList.remove('is-active', 'text-emerald-800', 'dark:text-emerald-300');
                    btn.classList.add('text-slate-600', 'dark:text-slate-300');
                });
                btnEl.classList.add('is-active', 'text-emerald-800', 'dark:text-emerald-300');
                btnEl.classList.remove('text-slate-600');
                if (typeof moveSegmentThumb === 'function') {
                    moveSegmentThumb(track, btnEl, true);
                }
            }

            document.querySelectorAll('[data-pinjaman-panel]').forEach(function(panel) {
                if (panel.dataset.pinjamanPanel === tabKey) {
                    panel.classList.remove('hidden');
                    panel.classList.add('is-active', 'block');
                } else {
                    panel.classList.add('hidden');
                    panel.classList.remove('is-active', 'block');
                }
            });

            try {
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tabKey);
                history.replaceState(history.state, '', url.href);
            } catch (_) {}
        }
    </script>
@endsection
