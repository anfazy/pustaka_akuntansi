@extends('front.layout')

@section('title', 'Pinjaman Saya — Pustaka Akuntansi')

@section('content')
    <h1 class="text-[34px] font-bold tracking-tight text-slate-900 mb-8">Pinjaman Saya</h1>

    <!-- SEGMENTED TABS (Apple Pill Track) -->
    <div class="segment-track max-w-[760px] flex rounded-full p-1.5 mb-10">
        <a href="{{ route('pinjaman', ['tab' => 'siap']) }}"
           class="flex-1 text-center py-2 text-[14px] font-semibold rounded-full transition-all {{ $activeTab === 'siap' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
            Siap Diambil ({{ $siapDiambil->count() }})
        </a>
        <a href="{{ route('pinjaman', ['tab' => 'dipinjam']) }}"
           class="flex-1 text-center py-2 text-[14px] font-semibold rounded-full transition-all {{ $activeTab === 'dipinjam' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
            Sedang Dipinjam ({{ $sedangDipinjam->count() }})
        </a>
        <a href="{{ route('pinjaman', ['tab' => 'denda']) }}"
           class="flex-1 text-center py-2 text-[14px] font-semibold rounded-full transition-all {{ $activeTab === 'denda' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
            Terlambat & Denda ({{ $terlambat->count() }})
        </a>
    </div>

    <!-- TAB CONTENT -->
    <div class="space-y-6">
        @if($activeTab === 'siap')
            @forelse($siapDiambil as $loan)
                @php
                    $rack = $loan->book?->book_code ? 'Rak ' . substr($loan->book->book_code, 0, 3) : 'Rak A-02';
                    $deadline = $loan->created_at ? $loan->created_at->addHours(24)->translatedFormat('d M, H:i') . ' WIB' : 'Besok 16.00 WIB';
                @endphp
                <article class="glass-card flex flex-col gap-6 rounded-[28px] p-6 sm:p-8 sm:flex-row sm:items-center animate-rise">
                    <!-- QR Code Box (White background for scanner reliability) -->
                    <div class="grid h-32 w-32 shrink-0 place-items-center rounded-2xl p-2.5 shadow-sm bg-white border border-slate-200 dark:border-white/20">
                        <canvas id="qr_{{ $loan->id }}" class="h-full w-full"></canvas>
                    </div>

                    <!-- Booking Details -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Booking</span>
                            <span class="liquid-badge-emerald rounded-full px-2.5 py-0.5 text-xs font-bold">AKTIF</span>
                        </div>
                        <h3 class="text-[22px] sm:text-[26px] font-bold tracking-tight text-slate-900">
                            {{ $loan->booking_code }}
                        </h3>
                        <p class="mt-2 text-[16px] font-semibold text-slate-800">
                            {{ $loan->book?->judul }} <span class="font-normal text-slate-500">({{ $rack }})</span>
                        </p>
                        <p class="mt-1 text-[13px] text-slate-500">
                            Batas ambil di perpustakaan: <strong class="text-slate-700">{{ $deadline }}</strong>
                        </p>
                    </div>

                    <!-- Right Status & Cancel Button -->
                    <div class="flex sm:flex-col sm:items-end justify-between items-center gap-4 sm:w-[220px]">
                        <span class="liquid-badge-emerald inline-flex items-center gap-1.5 rounded-full px-3.5 py-1 text-xs font-bold">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                            Siap Diambil
                        </span>

                        <form action="{{ route('pinjaman.batal', $loan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan booking ini?')">
                            @csrf
                            <button type="submit" class="btn-glass rounded-full px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-300">
                                Batalkan Booking
                            </button>
                        </form>
                    </div>
                </article>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        renderQrCode("qr_{{ $loan->id }}", "{{ $loan->booking_code }}");
                    });
                </script>
            @empty
                <div class="glass-card my-8 flex flex-col items-center justify-center rounded-[32px] p-12 text-center">
                    <div class="grid h-16 w-16 place-items-center rounded-full bg-slate-100 text-slate-400 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Belum ada booking aktif</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm">Pilih buku di katalog dan ajukan pemesanan untuk mengambilnya di ruang baca jurusan.</p>
                    <a href="{{ route('katalog') }}" class="btn-emerald mt-5 rounded-full px-6 py-2 text-sm font-semibold">
                        Jelajahi Katalog
                    </a>
                </div>
            @endforelse

        @elseif($activeTab === 'dipinjam')
            @forelse($sedangDipinjam as $loan)
                @php
                    $cover = $loan->book?->cover;
                    $artIndex = abs(crc32($loan->book?->judul ?? 'buku')) % 6;
                    $today = today();
                    $due = $loan->jatuh_tempo ? $loan->jatuh_tempo->copy()->startOfDay() : null;
                    $isOverdue = $due && $today->gt($due);
                    $daysDiff = $due ? abs($today->diffInDays($due)) : 0;
                    $lateFine = $loan->calculateLateFine(today());
                @endphp
                <article class="glass-card flex flex-col gap-6 rounded-[28px] p-6 sm:p-7 sm:flex-row sm:items-center animate-rise">
                    <!-- Mini Book Cover -->
                    <div class="relative aspect-[5/7] w-16 sm:w-20 shrink-0 overflow-hidden rounded-[6px] book-shadow">
                        @if($cover && (file_exists(public_path(ltrim($cover, '/'))) || str_starts_with($cover, 'http')))
                            <img src="{{ asset(ltrim($cover, '/')) }}" alt="{{ $loan->book?->judul }}" class="h-full w-full object-cover">
                        @else
                            <div class="cover-art-{{ $artIndex }} h-full w-full p-2 flex flex-col justify-end">
                                <span class="font-serif text-[8px] font-bold leading-tight">{{ $loan->book?->judul }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Book Info -->
                    <div class="flex-1">
                        <h3 class="text-[18px] sm:text-[20px] font-bold text-slate-900 leading-snug">
                            {{ $loan->book?->judul }}
                        </h3>
                        <p class="text-[13px] text-slate-500 mt-1">
                            {{ $loan->book?->penulis }}
                        </p>
                        <p class="text-xs text-slate-400 mt-2 font-mono">
                            Kode: {{ $loan->book?->book_code ?? $loan->booking_code }} • Dipinjam: {{ $loan->tanggal_pinjam?->locale('id')->translatedFormat('d M Y') }} • Jatuh Tempo: {{ $loan->jatuh_tempo?->locale('id')->translatedFormat('d M Y') }}
                        </p>
                    </div>

                    <!-- Due Status & Fine -->
                    <div class="sm:text-right flex flex-col sm:items-end gap-1">
                        @if($isOverdue)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                                Terlambat {{ $daysDiff }} hari
                            </span>
                            <span class="text-xs font-bold text-rose-600">Denda: Rp {{ number_format($lateFine, 0, ',', '.') }}</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-600"></span>
                                Jatuh tempo {{ $daysDiff }} hari lagi ({{ $loan->jatuh_tempo?->locale('id')->translatedFormat('d M Y') }})
                            </span>
                            <span class="text-xs text-slate-500">Denda: Rp 0</span>
                        @endif
                    </div>

                    <!-- Extend Action Button -->
                    <div class="sm:w-[200px] flex justify-end">
                        @if(! $loan->is_extended && ! $isOverdue)
                            <form action="{{ route('pinjaman.perpanjang', $loan->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-glass rounded-full px-4 py-2 text-xs font-semibold text-emerald-800 hover:border-emerald-300">
                                    Perpanjang +3 Hari • Sisa 1x
                                </button>
                            </form>
                        @elseif($loan->is_extended)
                            <span class="text-xs text-slate-400 font-medium px-3 py-1 rounded-full bg-slate-100">
                                Sudah Diperpanjang
                            </span>
                        @else
                            <span class="text-xs text-rose-500 font-semibold">
                                Harap Segera Dikembalikan
                            </span>
                        @endif
                    </div>
                </article>
            @empty
                <div class="glass-card my-8 flex flex-col items-center justify-center rounded-[32px] p-12 text-center">
                    <div class="grid h-16 w-16 place-items-center rounded-full bg-slate-100 text-slate-400 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Tidak ada buku yang sedang dipinjam</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm">Buku fisik yang sedang Anda bawa akan tampil pada halaman ini.</p>
                </div>
            @endforelse

        @elseif($activeTab === 'denda')
            @forelse($terlambat as $loan)
                @php
                    $lateFine = $loan->calculateLateFine(today());
                    $daysDiff = $loan->calculateOverdueWeekdays(today());
                @endphp
                <article class="glass-card flex flex-col gap-6 rounded-[28px] p-6 sm:p-7 sm:flex-row sm:items-center border-rose-200/80 bg-rose-50/40 animate-rise">
                    <div class="flex-1">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700 mb-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                            Terlambat {{ $daysDiff }} Hari Kerja
                        </span>
                        <h3 class="text-[20px] font-bold text-slate-900 leading-snug">
                            {{ $loan->book?->judul }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Jatuh tempo: {{ $loan->jatuh_tempo?->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <div class="sm:text-right">
                        <span class="block text-xs text-slate-500">Estimasi Denda:</span>
                        <span class="text-2xl font-bold text-rose-600">Rp {{ number_format($lateFine, 0, ',', '.') }}</span>
                        <p class="text-[11px] text-slate-400 mt-0.5">Bayar di meja sirkulasi saat pengembalian</p>
                    </div>
                </article>
            @empty
                <div class="glass-card my-8 flex flex-col items-center justify-center rounded-[32px] p-12 text-center">
                    <div class="grid h-16 w-16 place-items-center rounded-full bg-emerald-100 text-emerald-600 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Tidak ada keterlambatan atau denda</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm">Terima kasih telah selalu mengembalikan buku tepat waktu!</p>
                </div>
            @endforelse
        @endif
    </div>

    <!-- MODAL SUKSES BOOKING (Matching Image 3) -->
    @if($successBookingCode)
        @php
            $successLoan = \App\Models\Loan::with('book')->where('booking_code', $successBookingCode)->first();
            $succRack = $successLoan?->book?->book_code ? 'Rak ' . substr($successLoan->book->book_code, 0, 3) : 'Rak A-02';
        @endphp
        <div id="bookingSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md" onclick="document.getElementById('bookingSuccessModal').classList.add('hidden')"></div>

            <div class="glass-card relative z-10 w-full max-w-[480px] overflow-hidden rounded-[36px] p-8 sm:p-10 text-center shadow-2xl border border-white/95 animate-rise">
                <!-- Checkmark Icon -->
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-emerald-100 text-emerald-600 mb-5">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                </div>

                <h3 class="text-[26px] font-bold tracking-tight text-slate-900">Booking Berhasil Dibuat</h3>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $successLoan?->book?->judul ?? 'Buku Pilihan' }} • {{ $succRack }}
                </p>

                <!-- QR Card -->
                <div class="pearl-card mt-7 rounded-[26px] p-6 shadow-sm border border-slate-200">
                    <div class="mx-auto grid h-36 w-36 place-items-center rounded-xl bg-white p-2">
                        <canvas id="successQrCanvas" class="h-full w-full"></canvas>
                    </div>
                    <div class="mt-4 text-[20px] font-bold tracking-tight text-slate-900">
                        Kode Booking: {{ $successBookingCode }}
                    </div>
                    <p class="mt-2 text-xs leading-relaxed text-slate-500">
                        Tunjukkan kode ini ke pustakawan di ruang baca sebelum Besok, 16.00 WIB
                    </p>
                </div>

                <!-- Modal Actions -->
                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="document.getElementById('bookingSuccessModal').classList.add('hidden')"
                            class="btn-glass flex-1 h-11 rounded-full text-sm font-semibold">
                        Tutup
                    </button>
                    <a href="{{ route('pinjaman', ['tab' => 'siap']) }}"
                       class="btn-emerald flex-1 h-11 rounded-full text-sm font-semibold inline-flex items-center justify-center">
                        Lihat Pinjaman Saya
                    </a>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                renderQrCode("successQrCanvas", "{{ $successBookingCode }}");
            });
        </script>
    @endif
@endsection

@push('scripts')
    <!-- Standalone QR Code Generator (Zero dependencies) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script>
        function renderQrCode(canvasId, value) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            try {
                new QRious({
                    element: canvas,
                    value: value,
                    size: 160,
                    backgroundAlpha: 0,
                    foreground: '#0f172a'
                });
            } catch (e) {
                console.error(e);
            }
        }
    </script>
@endpush
