@extends('front.layout')

@section('title', 'Tata Tertib & Ketentuan Layanan — Perpustakaan Jurusan Akuntansi')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
            <a href="{{ route('beranda') }}" class="hover:text-emerald-800 transition">Beranda</a>
            <span>&bull;</span>
            <span class="text-emerald-800 dark:text-emerald-400 font-bold">Tata Tertib & Ketentuan Layanan</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 leading-tight">
            Tata Tertib & Ketentuan Layanan
        </h1>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
            Perpustakaan Jurusan Akuntansi Politeknik Negeri Semarang
        </p>
    </div>

    <div class="space-y-8">
        <!-- CARD 1: JADWAL PELAYANAN RESMI -->
        <div class="glass-card rounded-[32px] p-6 sm:p-8 shadow-xl border border-white/80 dark:border-white/10">
            <span class="inline-flex items-center gap-1.5 rounded-full liquid-badge-emerald px-3 py-1 text-xs font-bold mb-3">
                Jadwal Pelayanan Resmi
            </span>
            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mb-6">
                Waktu Operasional Meja Sirkulasi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Col A: Hari & Jam Layanan -->
                <div class="rounded-2xl bg-white/60 dark:bg-slate-800/60 p-5 border border-white/70 dark:border-white/10 space-y-3">
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Hari & Jam Layanan:</span>
                    <ul class="space-y-2 text-sm font-medium text-slate-800 dark:text-slate-200">
                        <li class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                            <span><strong>Senin – Kamis:</strong> 08.30 – 15.30 WIB</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                            <span><strong>Jumat:</strong> 08.30 – 14.00 WIB</span>
                        </li>
                        <li class="flex items-center gap-2 text-slate-500">
                            <span class="h-2 w-2 rounded-full bg-rose-400"></span>
                            <span><strong>Sabtu & Minggu:</strong> Tutup</span>
                        </li>
                    </ul>
                </div>

                <!-- Col B: Kontak Pustakawan -->
                <div class="rounded-2xl bg-white/60 dark:bg-slate-800/60 p-5 border border-white/70 dark:border-white/10 space-y-3 flex flex-col justify-between">
                    <div>
                        <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Kontak WhatsApp Pustakawan:</span>
                        <p class="mt-1 text-xs sm:text-[13px] text-slate-600 dark:text-slate-300">
                            Tanyakan layanan perpustakaan, termasuk informasi cetak modul, print, dan fotokopi literatur.
                        </p>
                    </div>
                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <a href="https://wa.me/62887895627873?text=Halo%20Admin%20Perpustakaan%20Akuntansi%20Polines" target="_blank"
                           class="btn-emerald inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                            <span>Chat WA: 0887895627873</span>
                        </a>
                        <span class="text-xs text-slate-500 font-medium">Layanan Cepat</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 2: DURASI PINJAM & TARIF DENDA -->
        <div class="glass-card rounded-[32px] p-6 sm:p-8 shadow-xl border border-white/80 dark:border-white/10">
            <span class="inline-flex items-center gap-1.5 rounded-full liquid-badge-emerald px-3 py-1 text-xs font-bold mb-3">
                Aturan Peminjaman
            </span>
            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">
                Durasi Pinjam & Tarif Denda Keterlambatan
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-300 mb-6">
                Durasi pinjam dihitung berdasarkan <strong>hari kerja (Senin s.d. Jumat)</strong>. Sabtu dan Minggu tidak dihitung.
            </p>

            <div class="overflow-x-auto rounded-2xl border border-white/60 dark:border-white/10 bg-white/40 dark:bg-slate-800/40">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-slate-200/60 dark:border-white/10 bg-white/70 dark:bg-slate-800/70 text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <th class="py-3.5 px-4 sm:px-6">Jenis Koleksi</th>
                            <th class="py-3.5 px-4 sm:px-6">Batas Durasi Pinjam</th>
                            <th class="py-3.5 px-4 sm:px-6">Denda Telat</th>
                            <th class="py-3.5 px-4 sm:px-6">Sanksi Kehilangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/50 dark:divide-white/5 font-medium text-slate-800 dark:text-slate-200">
                        <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/60 transition">
                            <td class="py-4 px-4 sm:px-6 font-bold text-slate-900">
                                Tugas Akhir / Skripsi
                            </td>
                            <td class="py-4 px-4 sm:px-6">14 Hari Kerja</td>
                            <td class="py-4 px-4 sm:px-6 font-bold text-rose-600 dark:text-rose-400">Rp 300 / hari</td>
                            <td class="py-4 px-4 sm:px-6 text-slate-600 dark:text-slate-300">Rp 200.000</td>
                        </tr>
                        <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/60 transition">
                            <td class="py-4 px-4 sm:px-6 font-bold text-slate-900">
                                Buku Umum (Non-TA / Skripsi)
                            </td>
                            <td class="py-4 px-4 sm:px-6">7 Hari Kerja</td>
                            <td class="py-4 px-4 sm:px-6 font-bold text-rose-600 dark:text-rose-400">Rp 200 / hari</td>
                            <td class="py-4 px-4 sm:px-6 text-slate-600 dark:text-slate-300">Mengganti buku sama / seharga buku</td>
                        </tr>
                        <tr class="hover:bg-emerald-50/40 dark:hover:bg-slate-800/60 transition">
                            <td class="py-4 px-4 sm:px-6 font-bold text-slate-900">
                                Proposal TA, Proposal PKL, & Laporan PKL
                            </td>
                            <td class="py-4 px-4 sm:px-6">7 Hari Kerja</td>
                            <td class="py-4 px-4 sm:px-6 font-bold text-rose-600 dark:text-rose-400">Rp 200 / hari</td>
                            <td class="py-4 px-4 sm:px-6 text-slate-600 dark:text-slate-300">Rp 200.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CARD 3: JAMINAN FISIK & BATAS MAKSIMAL -->
        <div class="glass-card rounded-[32px] p-6 sm:p-8 shadow-xl border border-white/80 dark:border-white/10">
            <span class="inline-flex items-center gap-1.5 rounded-full liquid-badge-emerald px-3 py-1 text-xs font-bold mb-3">
                Jaminan Fisik & Batas Maksimal
            </span>
            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mb-6">
                Maksimal 2 Koleksi per Anggota
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Item 1 -->
                <div class="rounded-2xl bg-white/60 dark:bg-slate-800/60 p-5 border border-white/70 dark:border-white/10 space-y-1.5">
                    <span class="block text-sm font-bold text-slate-900">KTM Asli</span>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Kartu Tanda Mahasiswa aktif asli yang dititipkan selama masa peminjaman fisik buku.
                    </p>
                </div>
                <!-- Item 2 -->
                <div class="rounded-2xl bg-white/60 dark:bg-slate-800/60 p-5 border border-white/70 dark:border-white/10 space-y-1.5">
                    <span class="block text-sm font-bold text-slate-900">Kartu Perpustakaan</span>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Kartu fisik anggota yang diterbitkan resmi oleh perpustakaan jurusan akuntansi.
                    </p>
                </div>
                <!-- Item 3 -->
                <div class="rounded-2xl bg-white/60 dark:bg-slate-800/60 p-5 border border-white/70 dark:border-white/10 space-y-1.5">
                    <span class="block text-sm font-bold text-slate-900">Kartu Asuransi</span>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Kartu asuransi kemahasiswaan resmi yang masih berlaku di lingkungan kampus Polines.
                    </p>
                </div>
            </div>

            <!-- Callout Pill Banner (Batas Kuota Pinjam) -->
            <div class="rounded-2xl bg-amber-500/15 border border-amber-500/30 p-4 sm:p-5 flex items-center gap-3.5">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-amber-500 text-white shadow-xs">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </span>
                <p class="text-xs sm:text-sm text-slate-800 dark:text-slate-200 leading-relaxed">
                    <strong class="font-bold text-amber-900 dark:text-amber-300">Batas Kuota Pinjam:</strong> Setiap mahasiswa hanya diperkenankan meminjam <strong class="font-bold text-amber-900 dark:text-amber-300">maksimal 2 (dua) koleksi</strong> secara bersamaan.
                </p>
            </div>
        </div>
    </div>
@endsection
