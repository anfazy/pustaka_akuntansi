@extends('front.layout')

@section('title', 'Tata Tertib — Perpustakaan Jurusan Akuntansi')

@section('content')
    @php
        $nowWib = now('Asia/Jakarta');
        $dayOfWeek = $nowWib->dayOfWeekIso;
        $timeDecimal = $nowWib->hour + ($nowWib->minute / 60);
        $isLibraryOpen = false;
        if ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
            $isLibraryOpen = $timeDecimal >= 8.5 && $timeDecimal <= 15.5;
        } elseif ($dayOfWeek === 5) {
            $isLibraryOpen = $timeDecimal >= 8.5 && $timeDecimal <= 14.0;
        }
    @endphp

    <!-- APPLE TYPOGRAPHY HEADER (Large Title 34px + Concise Subheadline 16px, Zero Parentheses) -->
    <header class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-5">
        <div>
            <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-[13px] font-medium text-slate-500 mb-2">
                <a href="{{ route('beranda') }}" class="hover:text-emerald-700 transition-colors">Beranda</a>
                <span aria-hidden="true">/</span>
                <span class="text-emerald-700 dark:text-emerald-400 font-semibold">Tata Tertib</span>
            </nav>
            <h1 class="text-[32px] sm:text-[38px] font-bold tracking-tight text-slate-900 leading-[1.12]">
                Tata Tertib Perpustakaan
            </h1>
            <p class="mt-2 text-[15px] sm:text-[16px] text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl">
                Aturan booking buku, jaminan kartu, perpanjangan, dan denda di Ruang Baca Jurusan Akuntansi Polines.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="#alur-booking" class="liquid-chip inline-flex min-h-[44px] items-center rounded-full px-4 text-[13px] font-semibold">
                Alur Booking
            </a>
            <a href="#jaminan-kartu" class="liquid-chip inline-flex min-h-[44px] items-center rounded-full px-4 text-[13px] font-semibold">
                Jaminan Kartu
            </a>
            <a href="#tarif-denda" class="liquid-chip inline-flex min-h-[44px] items-center rounded-full px-4 text-[13px] font-semibold">
                Durasi &amp; Denda
            </a>
            <a href="#jam-layanan" class="liquid-chip inline-flex min-h-[44px] items-center rounded-full px-4 text-[13px] font-semibold">
                Jam Layanan
            </a>
        </div>
    </header>

    <div class="space-y-8">
        <!-- SECTION 1: ALUR BOOKING, PERPANJANGAN & DENDA (Clean Typographic Hierarchy, 15px Body) -->
        <section id="alur-booking" class="scroll-mt-32 glass-card rounded-[36px] p-6 sm:p-8 shadow-xl border border-white/85 dark:border-white/10 apple-reveal">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-slate-200/60 dark:border-white/10">
                <div>
                    <h2 class="text-[22px] sm:text-[25px] font-bold tracking-tight text-slate-900 leading-snug">
                        Alur Booking &amp; Peminjaman
                    </h2>
                    <p class="mt-1 text-[14px] sm:text-[15px] text-slate-500">
                        Empat tahap meminjam hingga mengembalikan buku fisik.
                    </p>
                </div>

                <a href="{{ route('katalog') }}" class="btn-emerald inline-flex min-h-[44px] items-center gap-2 rounded-full px-5 text-[14px] font-semibold">
                    <span>Cari Buku</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                <!-- Langkah 1 -->
                <article class="rounded-[20px] bg-white/70 dark:bg-white/5 border border-white/85 dark:border-white/10 p-5 sm:p-6">
                    <div class="flex items-center gap-3 mb-2.5">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-emerald-600 text-white text-[14px] font-bold tabular-nums">1</span>
                        <h3 class="text-[17px] font-semibold text-slate-900">Booking lewat katalog</h3>
                    </div>
                    <p class="text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed">
                        Masuk akun mahasiswa, pilih buku yang tersedia, lalu ajukan pinjam untuk mendapat kode booking dan QR. Pesanan bisa dibatalkan lewat menu Pinjaman Saya.
                    </p>
                </article>

                <!-- Langkah 2 -->
                <article class="rounded-[20px] bg-white/70 dark:bg-white/5 border border-white/85 dark:border-white/10 p-5 sm:p-6">
                    <div class="flex items-center gap-3 mb-2.5">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-amber-500 text-white text-[14px] font-bold tabular-nums">2</span>
                        <h3 class="text-[17px] font-semibold text-slate-900">Ambil maksimal 1&times;24 jam</h3>
                    </div>
                    <p class="text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed">
                        Datang ke ruang baca paling lambat besok pukul <strong class="font-semibold text-slate-900 tabular-nums">16.00 WIB</strong>. Tunjukkan kode booking dan titipkan satu kartu jaminan. <strong class="font-semibold text-rose-600 dark:text-rose-400">Lewat batas waktu, booking hangus otomatis.</strong>
                    </p>
                </article>

                <!-- Langkah 3 -->
                <article class="rounded-[20px] bg-white/70 dark:bg-white/5 border border-white/85 dark:border-white/10 p-5 sm:p-6">
                    <div class="flex items-center gap-3 mb-2.5">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-emerald-600 text-white text-[14px] font-bold tabular-nums">3</span>
                        <h3 class="text-[17px] font-semibold text-slate-900">Bisa perpanjang 1 kali</h3>
                    </div>
                    <p class="text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed">
                        Pinjaman aktif dapat diperpanjang mandiri lewat menu Pinjaman Saya untuk tambahan <strong class="font-semibold text-slate-900">3 hari kerja</strong>. Syaratnya, buku belum melewati tanggal jatuh tempo.
                    </p>
                </article>

                <!-- Langkah 4 -->
                <article class="rounded-[20px] bg-white/70 dark:bg-white/5 border border-white/85 dark:border-white/10 p-5 sm:p-6">
                    <div class="flex items-center gap-3 mb-2.5">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-slate-800 dark:bg-slate-600 text-white text-[14px] font-bold tabular-nums">4</span>
                        <h3 class="text-[17px] font-semibold text-slate-900">Bayar denda di ruang baca</h3>
                    </div>
                    <p class="text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed">
                        Cek estimasi denda keterlambatan lewat menu Pinjaman Saya. Pelunasan dibayar langsung ke petugas di meja sirkulasi saat pengembalian buku.
                    </p>
                </article>
            </div>
        </section>

        <!-- SECTION 2: JAMINAN KARTU FISIK & KUOTA -->
        <section id="jaminan-kartu" class="scroll-mt-32 glass-card rounded-[36px] p-6 sm:p-8 shadow-xl border border-white/85 dark:border-white/10 apple-reveal">
            <h2 class="text-[22px] sm:text-[25px] font-bold tracking-tight text-slate-900 leading-snug">
                Jaminan Kartu &amp; Kuota Pinjam
            </h2>
            <p class="mt-2 text-[15px] sm:text-[16px] text-slate-600 dark:text-slate-300 leading-relaxed max-w-3xl">
                Saat mengambil buku, <strong class="font-semibold text-slate-900">wajib titipkan salah satu kartu asli</strong> di bawah ini sebagai jaminan. Anda tidak perlu menyerahkan ketiganya sekaligus.
            </p>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-[20px] bg-white/70 dark:bg-white/5 border border-white/85 dark:border-white/10 p-5">
                    <h3 class="text-[16px] font-semibold text-slate-900">KTM Asli</h3>
                    <p class="mt-1.5 text-[14px] text-slate-600 dark:text-slate-300 leading-relaxed">
                        Kartu Tanda Mahasiswa Polines yang masih aktif.
                    </p>
                </div>

                <div class="rounded-[20px] bg-white/70 dark:bg-white/5 border border-white/85 dark:border-white/10 p-5">
                    <h3 class="text-[16px] font-semibold text-slate-900">Kartu Perpustakaan</h3>
                    <p class="mt-1.5 text-[14px] text-slate-600 dark:text-slate-300 leading-relaxed">
                        Kartu anggota resmi Perpustakaan Jurusan Akuntansi.
                    </p>
                </div>

                <div class="rounded-[20px] bg-white/70 dark:bg-white/5 border border-white/85 dark:border-white/10 p-5">
                    <h3 class="text-[16px] font-semibold text-slate-900">Kartu Asuransi</h3>
                    <p class="mt-1.5 text-[14px] text-slate-600 dark:text-slate-300 leading-relaxed">
                        Kartu asuransi mahasiswa Polines yang masih berlaku.
                    </p>
                </div>
            </div>

            <div class="mt-5 rounded-[18px] bg-amber-500/12 border border-amber-500/25 p-4 sm:p-5 flex items-start sm:items-center gap-3.5">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-amber-500 text-white">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </span>
                <p class="text-[14px] sm:text-[15px] text-slate-800 dark:text-slate-200 leading-relaxed">
                    <strong class="font-semibold text-amber-900 dark:text-amber-300">Batas kuota:</strong> Maksimal <strong class="font-semibold text-amber-900 dark:text-amber-300">2 buku aktif bersamaan</strong>, termasuk buku yang sedang di-booking dan dipinjam.
                </p>
            </div>
        </section>

        <!-- SECTION 3: DURASI PINJAM, TARIF DENDA & SANKSI -->
        <section id="tarif-denda" class="scroll-mt-32 glass-card rounded-[36px] p-6 sm:p-8 shadow-xl border border-white/85 dark:border-white/10 apple-reveal">
            <h2 class="text-[22px] sm:text-[25px] font-bold tracking-tight text-slate-900 leading-snug">
                Durasi Pinjam, Denda &amp; Sanksi
            </h2>
            <p class="mt-2 text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed max-w-3xl mb-6">
                Perhitungan durasi pinjam dan keterlambatan hanya menghitung hari kerja Senin sampai Jumat. Sabtu, Minggu, dan libur resmi tidak dihitung.
            </p>

            <div class="overflow-x-auto rounded-[20px] border border-white/75 dark:border-white/10 bg-white/55 dark:bg-slate-800/40">
                <table class="w-full min-w-[640px] text-left">
                    <thead>
                        <tr class="border-b border-slate-200/70 dark:border-white/10 bg-white/75 dark:bg-slate-800/75 text-[12px] font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                            <th class="py-4 px-5 sm:px-6">Jenis Koleksi</th>
                            <th class="py-4 px-5 sm:px-6">Durasi Pinjam</th>
                            <th class="py-4 px-5 sm:px-6">Perpanjangan</th>
                            <th class="py-4 px-5 sm:px-6">Denda Telat</th>
                            <th class="py-4 px-5 sm:px-6">Sanksi Hilang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/50 dark:divide-white/5 text-[14px] sm:text-[15px] font-medium text-slate-800 dark:text-slate-200 tabular-nums">
                        <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/60 transition-colors">
                            <td class="py-4 px-5 sm:px-6 font-semibold text-slate-900">Tugas Akhir &amp; Skripsi</td>
                            <td class="py-4 px-5 sm:px-6">14 hari kerja</td>
                            <td class="py-4 px-5 sm:px-6 text-emerald-700 dark:text-emerald-400">1 kali, +3 hari</td>
                            <td class="py-4 px-5 sm:px-6 font-semibold text-rose-600 dark:text-rose-400">Rp 300 / hari</td>
                            <td class="py-4 px-5 sm:px-6 text-slate-600 dark:text-slate-300">Rp 200.000</td>
                        </tr>
                        <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/60 transition-colors">
                            <td class="py-4 px-5 sm:px-6 font-semibold text-slate-900">Buku Umum</td>
                            <td class="py-4 px-5 sm:px-6">3 atau 7 hari kerja</td>
                            <td class="py-4 px-5 sm:px-6 text-emerald-700 dark:text-emerald-400">1 kali, +3 hari</td>
                            <td class="py-4 px-5 sm:px-6 font-semibold text-rose-600 dark:text-rose-400">Rp 200 / hari</td>
                            <td class="py-4 px-5 sm:px-6 text-slate-600 dark:text-slate-300">Ganti buku sama atau seharga</td>
                        </tr>
                        <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/60 transition-colors">
                            <td class="py-4 px-5 sm:px-6 font-semibold text-slate-900">Proposal &amp; Laporan PKL</td>
                            <td class="py-4 px-5 sm:px-6">7 hari kerja</td>
                            <td class="py-4 px-5 sm:px-6 text-emerald-700 dark:text-emerald-400">1 kali, +3 hari</td>
                            <td class="py-4 px-5 sm:px-6 font-semibold text-rose-600 dark:text-rose-400">Rp 200 / hari</td>
                            <td class="py-4 px-5 sm:px-6 text-slate-600 dark:text-slate-300">Rp 200.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- SECTION 4: JAM LAYANAN & KONTAK PUSTAKAWAN -->
        <section id="jam-layanan" class="scroll-mt-32 glass-card rounded-[36px] p-6 sm:p-8 shadow-xl border border-white/85 dark:border-white/10 apple-reveal">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h2 class="text-[22px] sm:text-[25px] font-bold tracking-tight text-slate-900 leading-snug">
                    Jam Layanan &amp; Kontak
                </h2>

                <span class="inline-flex items-center gap-2 rounded-full bg-white/75 dark:bg-white/10 border border-white/80 dark:border-white/10 px-4 py-1.5 text-[13px] font-semibold">
                    <span class="h-2 w-2 rounded-full {{ $isLibraryOpen ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' }}"></span>
                    <span>{{ $isLibraryOpen ? 'Ruang Baca Buka' : 'Ruang Baca Tutup' }}</span>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="rounded-[20px] bg-white/70 dark:bg-white/5 p-5 border border-white/85 dark:border-white/10 space-y-2.5 text-[15px] tabular-nums">
                    <div class="flex items-center justify-between py-2 border-b border-slate-200/50 dark:border-white/10">
                        <span class="font-medium text-slate-700 dark:text-slate-300">Senin – Kamis</span>
                        <span class="font-semibold text-slate-900">08.30 – 15.30 WIB</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-200/50 dark:border-white/10">
                        <span class="font-medium text-slate-700 dark:text-slate-300">Jumat</span>
                        <span class="font-semibold text-slate-900">08.30 – 14.00 WIB</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 text-slate-500">
                        <span class="font-medium">Sabtu &amp; Minggu</span>
                        <span class="font-semibold">Tutup</span>
                    </div>
                </div>

                <div class="rounded-[20px] bg-white/70 dark:bg-white/5 p-5 border border-white/85 dark:border-white/10 flex flex-col justify-between gap-4">
                    <div>
                        <h3 class="text-[17px] font-semibold text-slate-900">Kontak Pustakawan</h3>
                        <p class="mt-1.5 text-[14px] sm:text-[15px] text-slate-600 dark:text-slate-300 leading-relaxed">
                            Hubungi petugas untuk cetak modul, fotokopi literatur, denda, atau reset sandi akun.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="https://wa.me/62887895627873?text=Halo%20Admin%20Perpustakaan%20Akuntansi%20Polines" target="_blank"
                           class="btn-emerald inline-flex min-h-[44px] items-center gap-2 rounded-full px-5 text-[14px] font-semibold">
                            <span>WhatsApp 0887895627873</span>
                        </a>
                        <a href="{{ route('pinjaman') }}" class="btn-glass inline-flex min-h-[44px] items-center rounded-full px-5 text-[14px] font-semibold">
                            Pinjaman Saya
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
