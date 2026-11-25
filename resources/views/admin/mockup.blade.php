<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Jurusan Akuntansi - Perpustakaan Jurusan Akuntansi</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        forest: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        gold: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .view-section {
            animation: fadeSlide 0.2s ease-out;
        }
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased selection:bg-forest-200 selection:text-forest-950">

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

    <!-- TOP NAVBAR -->
    <header class="sticky top-0 z-30 h-16 bg-gradient-to-r from-forest-950 via-forest-900 to-forest-800 border-b border-forest-700/80 px-4 lg:px-6 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <!-- Mobile Sidebar Toggle -->
            <button id="mobile-menu-btn" type="button" class="lg:hidden p-2 rounded-lg text-forest-100 hover:bg-forest-800 focus:outline-none">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            <!-- Brand Title: Perpustakaan Jurusan Akuntansi -->
            <a href="#" onclick="switchSidebarMenu('dashboard'); return false;" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-gold-300 to-gold-500 text-forest-950 flex items-center justify-center shadow-sm ring-1 ring-gold-200/60 group-hover:scale-105 transition-transform">
                    <i data-lucide="landmark" class="w-5 h-5 stroke-[2.2]"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-display font-bold text-base sm:text-lg tracking-tight text-white leading-tight">
                        Perpustakaan Jurusan <span class="text-gold-300">Akuntansi</span>
                    </span>
                    <span class="text-[10px] font-medium text-forest-200 hidden sm:inline">Sistem Informasi Sirkulasi &amp; Koleksi • Pustaka Akuntansi</span>
                </div>
            </a>
        </div>

        <!-- Right Top Bar: Tanggal & Avatar 'AP' -->
        <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-forest-950/60 border border-forest-700/70 text-forest-100 text-xs font-medium">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-gold-400"></i>
                <span>{{ $formattedDate ?? 'Minggu, 22 November 2026' }}</span>
            </div>

            <div class="flex items-center gap-2.5">
                <span class="hidden sm:inline text-xs font-semibold text-forest-100">{{ $user->name ?? 'Admin Perpus' }}</span>
                <div class="w-9 h-9 rounded-full bg-gold-400 text-forest-950 font-extrabold text-sm flex items-center justify-center ring-2 ring-gold-300/60 shadow-xs cursor-pointer" title="{{ $user->email ?? 'Admin Perpustakaan' }}">
                    {{ strtoupper(substr($user->name ?? 'AP', 0, 2)) }}
                </div>
                <form action="{{ route('filament.admin.auth.logout') }}" method="POST" class="inline ml-1">
                    @csrf
                    <button type="submit" title="Keluar / Logout" class="text-forest-200 hover:text-white p-1.5 rounded-lg hover:bg-forest-800 transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="min-h-[calc(100vh-4rem)] flex">

        <!-- SIDEBAR KIRI (4 Menu Persis Seperti sirkulasi_2.html) -->
        <aside id="sidebar-drawer" class="fixed inset-y-0 left-0 top-16 z-20 w-64 bg-white border-r border-gray-200/90 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto transition-transform duration-200 ease-in-out flex flex-col justify-between shrink-0">
            <nav class="p-3.5 space-y-1">
                <!-- 1. Dashboard -->
                <a href="/admin" onclick="switchSidebarMenu('dashboard', event)" id="nav-dashboard" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold bg-forest-50 text-forest-800 ring-1 ring-forest-200/90 transition-colors">
                    <i data-lucide="home" class="w-4 h-4 text-forest-700"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Anggota -->
                <a href="/admin/anggota" onclick="switchSidebarMenu('anggota', event)" id="nav-anggota" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-forest-50/70 hover:text-forest-800 transition-colors">
                    <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                    <span>Anggota</span>
                </a>

                <!-- 3. Buku -->
                <a href="/admin/buku" onclick="switchSidebarMenu('buku', event)" id="nav-buku" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-forest-50/70 hover:text-forest-800 transition-colors">
                    <i data-lucide="book-open" class="w-4 h-4 text-gray-400"></i>
                    <span>Buku</span>
                </a>

                <!-- 4. Peminjaman & Pengembalian Buku -->
                <a href="/admin/peminjaman" onclick="switchSidebarMenu('sirkulasi', event)" id="nav-sirkulasi" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-forest-50/70 hover:text-forest-800 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <i data-lucide="arrow-left-right" class="w-4 h-4 text-gray-400 shrink-0"></i>
                        <span class="truncate">Peminjaman &amp; Pengembalian Buku</span>
                    </div>
                    <span id="sidebar-active-badge" class="ml-2 px-2 py-0.5 text-[11px] font-bold rounded-md bg-gold-100 text-amber-800 border border-gold-300 shrink-0">
                        2
                    </span>
                </a>
            </nav>

            <!-- Info Ringkas di Bawah Sidebar -->
            <div class="p-4 m-3 rounded-xl bg-gradient-to-br from-forest-50 to-gold-50/80 border border-forest-200/80">
                <div class="flex items-center gap-2 text-forest-900 font-bold text-xs">
                    <i data-lucide="shield-check" class="w-4 h-4 text-amber-600 shrink-0"></i>
                    <span>Aturan Sirkulasi</span>
                </div>
                <ul class="mt-2 space-y-1 text-[11px] text-gray-600">
                    <li>• Kode: <b>AKT-XXXX</b> (1×24 jam)</li>
                    <li>• Maks. <b>2 buku aktif</b> / NIM</li>
                    <li>• Denda: <b>Rp 200 – 300 / hari kerja</b></li>
                </ul>
            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 max-w-[1440px] mx-auto w-full">

            <!-- ===================================================================== -->
            <!-- VIEW 1: DASHBOARD (Utama)                                             -->
            <!-- ===================================================================== -->
            <div id="view-dashboard" class="view-section space-y-6">

                <!-- Header Halaman -->
                <div class="flex items-center justify-between">
                    <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                        Dashboard
                    </h1>
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium text-forest-700 bg-forest-50 border border-forest-200 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-forest-600 animate-pulse"></span>
                        Operasional Real-Time
                    </span>
                </div>

                <!-- Banner Sambutan Admin Perpus (Nuansa Gradien Forest-900/950 & Gold) -->
                <section class="rounded-2xl bg-gradient-to-r from-forest-950 via-forest-900 to-forest-800 border border-forest-700/80 p-5 sm:p-6 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4 text-white">
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-forest-950/70 border border-forest-700/80 text-gold-300 text-xs font-bold">
                                <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                                <span>Perpustakaan Jurusan Akuntansi</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-forest-800/80 border border-forest-700/60 text-forest-100 text-xs font-medium">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-gold-400"></i>
                                <span>{{ $formattedDate ?? 'Minggu, 22 November 2026' }}</span>
                            </span>
                        </div>
                        <h2 class="font-display text-xl sm:text-2xl font-extrabold tracking-tight text-white">
                            {{ $greeting ?? 'Selamat Datang' }}, <span class="text-gold-300">{{ $user->name ?? 'Admin Perpus' }}!</span> 👋
                        </h2>
                        <p class="text-xs sm:text-sm text-forest-100/90 max-w-3xl leading-relaxed">
                            Selamat datang di pusat kendali operasional Perpustakaan Jurusan Akuntansi. Pantau statistik sirkulasi, distribusi kategori referensi, dan ketersediaan stok buku secara real-time.
                        </p>
                    </div>

                    <div class="shrink-0">
                        <button type="button" onclick="switchSidebarMenu('buku')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gold-400 hover:bg-gold-500 active:scale-[0.99] text-forest-950 font-extrabold text-xs sm:text-sm shadow-sm border border-gold-300 transition-all">
                            <i data-lucide="book-open" class="w-4 h-4 stroke-[2.3]"></i>
                            <span>Buka Katalog Buku</span>
                        </button>
                    </div>
                </section>

                <!-- 4 KARTU STATISTIK UTAMA DASHBOARD -->
                <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <!-- Card 1: Total Kategori -->
                    <div onclick="switchSidebarMenu('buku')" class="cursor-pointer bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-forest-400 transition-all group">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-500">Total Kategori</p>
                            <span class="w-7 h-7 rounded-lg bg-forest-50 text-forest-700 flex items-center justify-center group-hover:bg-forest-700 group-hover:text-white transition-colors">
                                <i data-lucide="tags" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p id="dash-stat-kategori" class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">5 Kategori</p>
                        <p class="text-xs font-semibold text-forest-700 mt-1.5 flex items-center gap-1 group-hover:underline">
                            <span>Klik untuk kelola kategori</span>
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </p>
                    </div>

                    <!-- Card 2: Koleksi Judul Buku -->
                    <div onclick="switchSidebarMenu('buku')" class="cursor-pointer bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-gold-400 transition-all group">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-500">Koleksi Judul Buku</p>
                            <span class="w-7 h-7 rounded-lg bg-gold-100 text-amber-800 flex items-center justify-center group-hover:bg-gold-400 group-hover:text-forest-950 transition-colors">
                                <i data-lucide="library" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p id="dash-stat-judul" class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">4 Judul</p>
                        <p class="text-xs font-semibold text-amber-700 mt-1.5 flex items-center gap-1 group-hover:underline">
                            <span>Klik untuk lihat katalog buku</span>
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </p>
                    </div>

                    <!-- Card 3: Stok Eksemplar Tersedia -->
                    <div class="bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-emerald-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-500">Stok Eksemplar Tersedia</p>
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                                <i data-lucide="package-check" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p id="dash-stat-stok" class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">11 Buku</p>
                        <p class="text-xs font-medium text-emerald-700 mt-1.5 flex items-center gap-1.5">
                            <span>Siap dipinjam mahasiswa</span>
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                        </p>
                    </div>

                    <!-- Card 4: Sedang Dipinjam -->
                    <div onclick="switchSidebarMenu('sirkulasi')" class="cursor-pointer bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-rose-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-500">Sedang Dipinjam</p>
                            <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                                <i data-lucide="book-open-check" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p id="dash-stat-dipinjam" class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">2 Eksemplar</p>
                        <p id="dash-stat-overdue-note" class="text-xs font-medium text-rose-600 mt-1.5 flex items-center gap-1.5">
                            <span>Peringatan: 2 buku terlambat!</span>
                            <span>⚠️</span>
                        </p>
                    </div>
                </section>

                <!-- BARIS TENGAH 2 KOLOM: DONUT CHART & AKSI CEPAT + INFO PERPUSTAKAAN -->
                <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Kolom Kiri: Distribusi Koleksi Buku per Kategori -->
                    <div class="bg-white rounded-xl border border-gray-200/90 shadow-2xs p-5 sm:p-6 flex flex-col justify-between">
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div>
                                <h3 class="font-display text-base font-bold text-gray-900">
                                    Distribusi Koleksi Buku per Kategori
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Sebaran judul buku terdaftar (keseluruhan periode).
                                </p>
                            </div>
                            <select id="dash-chart-period" onchange="renderDashboardChart()" class="px-3 py-1.5 bg-gray-50 border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-forest-600">
                                <option value="all">Semua Periode</option>
                                <option value="semester">Semester Gasal 2026</option>
                            </select>
                        </div>

                        <div class="relative h-60 flex items-center justify-center my-2">
                            <canvas id="categoryDonutChart"></canvas>
                        </div>

                        <div id="dash-chart-legend" class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap items-center justify-center gap-x-4 gap-y-1.5 text-xs">
                            <!-- Legenda interaktif dirender JS -->
                        </div>
                    </div>

                    <!-- Kolom Kanan: Aksi Cepat & Info Perpustakaan -->
                    <div class="bg-white rounded-xl border border-gray-200/90 shadow-2xs p-5 sm:p-6 flex flex-col justify-between space-y-5">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-start gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-forest-50 border border-forest-200 text-forest-700 flex items-center justify-center shrink-0 mt-0.5">
                                    <i data-lucide="zap" class="w-4 h-4 text-amber-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-display text-base font-bold text-gray-900">
                                        Aksi Cepat &amp; Info Perpustakaan
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Perpustakaan Jurusan Akuntansi • Akses instan dan ringkasan panduan operasional layanan
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                SOP Aktif
                            </span>
                        </div>

                        <!-- 3 Tombol Aksi Cepat -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            <button type="button" onclick="switchSidebarMenu('sirkulasi'); openCreateLoanView();" class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl bg-gold-400 hover:bg-gold-500 text-forest-950 font-bold text-xs shadow-2xs border border-gold-500/40 transition-all">
                                <i data-lucide="plus" class="w-3.5 h-3.5 stroke-[2.5]"></i>
                                <span>Catat Peminjaman</span>
                            </button>
                            <button type="button" onclick="switchSidebarMenu('buku'); openCreateBookView();" class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl bg-forest-700 hover:bg-forest-800 text-white font-bold text-xs shadow-2xs transition-all">
                                <i data-lucide="plus" class="w-3.5 h-3.5 text-gold-300 stroke-[2.5]"></i>
                                <span>Tambah Buku</span>
                            </button>
                            <button type="button" onclick="openNewCategoryModal()" class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-950 text-white font-bold text-xs shadow-2xs ring-1 ring-gold-400/40 transition-all">
                                <i data-lucide="tag" class="w-3.5 h-3.5 text-gold-400"></i>
                                <span>Tambah Kategori</span>
                            </button>
                        </div>

                        <!-- Box Ringkasan Panduan Operasional -->
                        <div class="rounded-xl bg-gradient-to-br from-forest-50/90 to-gold-50/60 border border-forest-200/80 p-4 space-y-2.5">
                            <div class="flex items-center justify-between border-b border-forest-200/70 pb-2">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-forest-950">
                                    <i data-lucide="book-check" class="w-4 h-4 text-forest-700"></i>
                                    <span>Ringkasan Panduan Operasional</span>
                                </span>
                                <span class="text-[11px] font-bold text-amber-800 bg-gold-100 px-2 py-0.5 rounded border border-gold-300">SOP Sirkulasi</span>
                            </div>
                            <ol class="space-y-1.5 text-xs text-gray-700 leading-relaxed list-decimal list-inside">
                                <li><b>Ketentuan Peminjaman:</b> Maks. 2 buku aktif per NIM selama 7 hari kerja (TA/Skripsi 14 hari kerja). Perpanjangan mandiri 1×.</li>
                                <li><b>Standar Entri Koleksi:</b> Pastikan nomor ISBN valid, kategori spesifik akuntansi, dan isi deskripsi buku.</li>
                                <li><b>Verifikasi Stok Fisik:</b> Cek fisik buku dan cocokkan dengan angka stok sebelum memverifikasi pengembalian.</li>
                            </ol>
                        </div>

                        <!-- Footer Info Jam Layanan -->
                        <div class="flex flex-wrap items-center justify-between gap-2 pt-1 text-xs">
                            <div class="flex items-center gap-2 text-gray-600">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-forest-700"></i>
                                <span>Jam Layanan: Sen – Jum (08.00 – 16.00 WIB)</span>
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold text-[11px]">Buka</span>
                            </div>
                            <button type="button" onclick="switchSidebarMenu('buku')" class="font-bold text-forest-700 hover:text-forest-900 hover:underline inline-flex items-center gap-1">
                                <span>Katalog Lengkap</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- TABEL DASHBOARD 1: KOLEKSI BUKU TERBARU -->
                <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden">
                    <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="font-display text-base font-bold text-gray-900">Koleksi Buku Terbaru</h3>
                            <p class="text-xs text-gray-500">Daftar buku terbaru yang terdaftar di Pustaka Akuntansi</p>
                        </div>
                        <div class="relative">
                            <input type="text" id="dash-book-search" oninput="renderDashboardTables()" placeholder="Search buku..." class="w-full sm:w-60 pl-9 pr-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-2.5"></i>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/90 border-b border-gray-200 text-xs font-semibold text-gray-700">
                                    <th class="py-3 px-4">Kode Buku</th>
                                    <th class="py-3 px-4">Judul Buku</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4">Penulis</th>
                                    <th class="py-3 px-4">Stok</th>
                                    <th class="py-3 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="dash-books-tbody" class="divide-y divide-gray-100 text-xs sm:text-[13px]"></tbody>
                        </table>
                    </div>
                </section>

                <!-- TABEL DASHBOARD 2: SIRKULASI BUKU SEDANG DIPINJAM -->
                <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden">
                    <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="font-display text-base font-bold text-gray-900">Sirkulasi Buku Sedang Dipinjam</h3>
                            <p class="text-xs text-gray-500">Pemantauan peminjaman aktif dan status jatuh tempo mahasiswa</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative">
                                <input type="text" id="dash-loan-search" oninput="renderDashboardTables()" placeholder="Search peminjam..." class="w-48 sm:w-56 pl-9 pr-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-2.5"></i>
                            </div>
                            <button type="button" onclick="switchSidebarMenu('sirkulasi')" class="px-3 py-1.5 rounded-lg bg-forest-50 hover:bg-forest-100 text-forest-800 border border-forest-200 text-xs font-bold whitespace-nowrap">
                                Semua Sirkulasi →
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/90 border-b border-gray-200 text-xs font-semibold text-gray-700">
                                    <th class="py-3 px-4">Judul Buku</th>
                                    <th class="py-3 px-4">Nama Mahasiswa Peminjam</th>
                                    <th class="py-3 px-4">Tanggal Pinjam</th>
                                    <th class="py-3 px-4">Jatuh Tempo (SOP 7 Hari)</th>
                                    <th class="py-3 px-4">Status Sirkulasi</th>
                                </tr>
                            </thead>
                            <tbody id="dash-loans-tbody" class="divide-y divide-gray-100 text-xs sm:text-[13px]"></tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- ===================================================================== -->
            <!-- VIEW 2A: MENU ANGGOTA (Anggota > List)                                -->
            <!-- ===================================================================== -->
            <div id="view-anggota" class="hidden view-section space-y-5">
                <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                    <div>
                        <nav class="flex items-center gap-1.5 text-xs font-medium text-gray-500 mb-1">
                            <span>Anggota</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
                            <span class="text-forest-700 font-semibold">List</span>
                        </nav>
                        <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                            Anggota
                        </h1>
                    </div>

                    <!-- 3 Tombol Kanan Atas Sesuai Screenshot /admin/anggota -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <button type="button" onclick="openCreateAnggotaView()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-950 active:scale-[0.99] text-white font-bold text-xs sm:text-sm shadow-xs ring-1 ring-gold-400/50 transition-all">
                            <i data-lucide="user-plus" class="w-4 h-4 text-gold-400 stroke-[2.2]"></i>
                            <span>Tambah Anggota</span>
                        </button>
                        <button type="button" onclick="downloadAnggotaTemplate()" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white hover:bg-gray-50 active:scale-[0.99] text-gray-800 font-semibold text-xs sm:text-sm border border-gray-300 shadow-2xs transition-all">
                            <i data-lucide="download" class="w-4 h-4 text-gray-600"></i>
                            <span>Unduh Template Excel</span>
                        </button>
                        <button type="button" onclick="openAnggotaImportModal()" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-forest-600 hover:bg-forest-700 active:scale-[0.99] text-white font-bold text-xs sm:text-sm shadow-2xs transition-all">
                            <i data-lucide="upload" class="w-4 h-4 text-gold-200"></i>
                            <span>Impor Excel</span>
                        </button>
                    </div>
                </div>

                <!-- Tabel Anggota -->
                <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-visible">
                    <div class="p-3.5 sm:p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span id="anggota-bulk-info" class="hidden px-2.5 py-1 rounded-lg bg-gold-100 text-amber-900 border border-gold-300 text-xs font-bold">
                                0 anggota dipilih
                            </span>
                            <span class="text-xs text-gray-500 hidden sm:inline">
                                Database mahasiswa aktif Jurusan Akuntansi
                            </span>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-auto relative">
                            <div class="relative">
                                <input type="text" id="anggota-search-input" oninput="renderAnggotaTable()" placeholder="Cari (NIM / Nama...)" class="w-56 sm:w-64 pl-9 pr-3 py-2 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600">
                                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5"></i>
                            </div>

                            <div class="relative">
                                <button type="button" id="anggota-filter-btn" onclick="toggleAnggotaFilterPopover()" class="p-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-600 relative transition-colors" title="Filter Program Studi">
                                    <i data-lucide="filter" class="w-4 h-4"></i>
                                    <span id="anggota-filter-badge" class="hidden absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-400 text-forest-950 text-[10px] font-extrabold flex items-center justify-center border border-white">
                                        1
                                    </span>
                                </button>

                                <div id="anggota-filter-popover" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-200 p-3.5 z-30 space-y-3">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                        <span class="text-xs font-bold text-gray-900">Filter Program Studi</span>
                                        <button type="button" onclick="resetAnggotaFilters()" class="text-[11px] font-semibold text-rose-600 hover:underline">Reset</button>
                                    </div>
                                    <select id="anggota-filter-prodi" onchange="onAnggotaFilterChange()" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600">
                                        <option value="ALL">Semua Program Studi</option>
                                        <option value="Komputerisasi Akuntansi (S. Tr)">Komputerisasi Akuntansi (S. Tr)</option>
                                        <option value="Perbankan Syariah (S. Tr)">Perbankan Syariah (S. Tr)</option>
                                        <option value="Analis Keuangan (S. Tr)">Analis Keuangan (S. Tr)</option>
                                        <option value="Akuntansi Manajerial (S. Tr)">Akuntansi Manajerial (S. Tr)</option>
                                        <option value="Akuntansi (D3)">Akuntansi (D3)</option>
                                        <option value="Keuangan dan Perbankan (D3)">Keuangan dan Perbankan (D3)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/90 border-b border-gray-200 text-xs font-semibold text-gray-700">
                                    <th class="py-3.5 pl-4 pr-2 w-9">
                                        <input type="checkbox" id="anggota-select-all" onchange="toggleAnggotaSelectAll(this)" class="rounded border-gray-300 text-forest-700 focus:ring-forest-600">
                                    </th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">NIM / No. Anggota</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Nama</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Program Studi</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Kelas</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Angkatan</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">No. HP</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Email</th>
                                    <th class="py-3.5 pl-3 pr-4 text-right whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="anggota-table-body" class="divide-y divide-gray-100 text-xs sm:text-[13px]"></tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-gray-600 bg-white rounded-b-xl">
                        <div id="anggota-pagination-summary" class="font-medium">
                            Showing 1 to 10 of 11 results
                        </div>
                        <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white self-center sm:self-auto">
                            <span class="px-3 py-1.5 text-gray-500 border-r border-gray-200 bg-gray-50 font-medium">Per page</span>
                            <select id="anggota-per-page" onchange="renderAnggotaTable()" class="px-2.5 py-1.5 font-semibold text-gray-800 bg-white focus:outline-none">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </section>
            </div>

            <!-- ===================================================================== -->
            <!-- VIEW 2B: TAMBAH ANGGOTA (Anggota > Tambah)                            -->
            <!-- ===================================================================== -->
            <div id="view-create-anggota" class="hidden view-section space-y-6">
                <div>
                    <nav class="flex items-center gap-1.5 text-xs font-medium text-gray-500 mb-1">
                        <button type="button" onclick="closeCreateAnggotaView()" class="hover:text-forest-700 hover:underline transition-colors">
                            Anggota
                        </button>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span class="text-forest-700 font-semibold">Tambah</span>
                    </nav>
                    <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                        Tambah Anggota
                    </h1>
                </div>

                <form id="create-anggota-form" onsubmit="handleCreateAnggotaSubmit(event, false)" class="space-y-6">
                    <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-display text-sm sm:text-base font-bold text-gray-900">
                                Formulir Data Anggota Mahasiswa
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Lengkapi data identitas mahasiswa untuk aktivasi kartu peminjaman Pustaka Akuntansi.
                            </p>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="ca-nim" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        NIM / No. Anggota<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="ca-nim" required placeholder="Contoh: 4.41.24.0.11" class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>

                                <div>
                                    <label for="ca-nama" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Nama Lengkap<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="ca-nama" required placeholder="Nama lengkap mahasiswa..." class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="ca-prodi" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Program Studi<span class="text-rose-500">*</span>
                                    </label>
                                    <select id="ca-prodi" required class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                        <option value="">Select an option</option>
                                        <option value="Komputerisasi Akuntansi (S. Tr)">Komputerisasi Akuntansi (S. Tr)</option>
                                        <option value="Perbankan Syariah (S. Tr)">Perbankan Syariah (S. Tr)</option>
                                        <option value="Analis Keuangan (S. Tr)">Analis Keuangan (S. Tr)</option>
                                        <option value="Akuntansi Manajerial (S. Tr)">Akuntansi Manajerial (S. Tr)</option>
                                        <option value="Akuntansi (D3)">Akuntansi (D3)</option>
                                        <option value="Keuangan dan Perbankan (D3)">Keuangan dan Perbankan (D3)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="ca-kelas" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Kelas<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="ca-kelas" required placeholder="Contoh: KA-A / AK-1A" class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="ca-angkatan" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Tahun Angkatan<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" id="ca-angkatan" required min="2018" max="2030" placeholder="Contoh: 2024" class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>

                                <div>
                                    <label for="ca-hp" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        No. HP<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="ca-hp" required placeholder="0812xxxxxxxx" class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="ca-email" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Email<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="email" id="ca-email" required placeholder="nama@student.polines.ac.id" class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>
                            </div>

                            <div>
                                <label for="ca-alamat" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                    Alamat<span class="text-rose-500">*</span>
                                </label>
                                <textarea id="ca-alamat" rows="3" required placeholder="Alamat lengkap mahasiswa..." class="w-full p-3.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600"></textarea>
                            </div>
                        </div>
                    </section>

                    <!-- 3 Tombol: Simpan, Simpan & Buat Lagi, Batal -->
                    <div class="flex flex-wrap items-center gap-2.5 pt-1">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-forest-800 hover:bg-forest-900 active:scale-[0.99] text-white font-bold text-xs sm:text-sm shadow-2xs ring-1 ring-gold-400/40 transition-all">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-gold-400"></i>
                            <span>Simpan</span>
                        </button>
                        <button type="button" onclick="handleCreateAnggotaSubmit(event, true)" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-gold-100 hover:bg-gold-200 active:scale-[0.99] text-amber-950 font-bold text-xs sm:text-sm border border-gold-300 shadow-2xs transition-all">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-amber-800"></i>
                            <span>Simpan &amp; Buat Lagi</span>
                        </button>
                        <button type="button" onclick="closeCreateAnggotaView()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-white hover:bg-gray-50 active:scale-[0.99] text-gray-700 font-semibold text-xs sm:text-sm border border-gray-300 shadow-2xs transition-all">
                            <i data-lucide="x" class="w-4 h-4 text-gray-500"></i>
                            <span>Batal</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ===================================================================== -->
            <!-- VIEW 3A: MENU BUKU (Books > List)                                     -->
            <!-- ===================================================================== -->
            <div id="view-buku" class="hidden view-section space-y-5">
                <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                    <div>
                        <nav class="flex items-center gap-1.5 text-xs font-medium text-gray-500 mb-1">
                            <span>Books</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
                            <span class="text-forest-700 font-semibold">List</span>
                        </nav>
                        <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                            Books
                        </h1>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" onclick="downloadBookTemplate()" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white hover:bg-gray-50 text-gray-800 font-semibold text-xs border border-gray-300 shadow-2xs transition-all">
                            <i data-lucide="download" class="w-3.5 h-3.5 text-gray-600"></i>
                            <span>Download Template Excel/CSV</span>
                        </button>
                        <button type="button" onclick="openBookImportModal()" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-forest-700 hover:bg-forest-800 text-white font-semibold text-xs shadow-2xs transition-all">
                            <i data-lucide="upload" class="w-3.5 h-3.5 text-gold-300"></i>
                            <span>Import Buku (Excel/CSV)</span>
                        </button>
                        <button type="button" onclick="openNewCategoryModal()" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gold-400 hover:bg-gold-500 text-forest-950 font-bold text-xs shadow-2xs border border-gold-500/40 transition-all">
                            <i data-lucide="tag" class="w-3.5 h-3.5"></i>
                            <span>New Kategori</span>
                        </button>
                        <button type="button" onclick="openCreateBookView()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-forest-900 hover:bg-forest-950 text-white font-bold text-xs shadow-xs ring-1 ring-gold-400/50 transition-all">
                            <i data-lucide="plus" class="w-3.5 h-3.5 text-gold-400"></i>
                            <span>New book</span>
                        </button>
                    </div>
                </div>

                <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-visible">
                    <div class="p-3.5 sm:p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span id="book-bulk-info" class="hidden px-2.5 py-1 rounded-lg bg-gold-100 text-amber-900 border border-gold-300 text-xs font-bold">
                                0 buku dipilih
                            </span>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-auto relative">
                            <div class="relative">
                                <input type="text" id="book-search-input" oninput="renderBooksTable()" placeholder="Search" class="w-52 sm:w-60 pl-9 pr-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-2"></i>
                            </div>

                            <div class="relative">
                                <button type="button" id="book-filter-btn" onclick="toggleBookFilterPopover()" class="p-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-600 relative transition-colors" title="Filter Buku">
                                    <i data-lucide="filter" class="w-4 h-4"></i>
                                    <span id="book-filter-badge" class="hidden absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-400 text-forest-950 text-[10px] font-extrabold flex items-center justify-center border border-white">
                                        0
                                    </span>
                                </button>

                                <div id="book-filter-popover" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 p-3.5 z-30 space-y-3">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                        <span class="text-xs font-bold text-gray-900">Filter Kategori</span>
                                        <button type="button" onclick="resetBookFilters()" class="text-[11px] font-semibold text-rose-600 hover:underline">Reset</button>
                                    </div>
                                    <select id="book-filter-category" onchange="onBookFilterChange()" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600">
                                        <option value="ALL">Semua Kategori</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/90 border-b border-gray-200 text-xs font-semibold text-gray-700">
                                    <th class="py-3 pl-4 pr-2 w-9">
                                        <input type="checkbox" id="book-select-all" onchange="toggleBookSelectAll(this)" class="rounded border-gray-300 text-forest-700 focus:ring-forest-600">
                                    </th>
                                    <th class="py-3 px-3 whitespace-nowrap">Cover</th>
                                    <th class="py-3 px-3 whitespace-nowrap">Kode Buku</th>
                                    <th class="py-3 px-3 whitespace-nowrap">Judul Buku</th>
                                    <th class="py-3 px-3 whitespace-nowrap">Kategori</th>
                                    <th class="py-3 px-3 whitespace-nowrap">Penulis</th>
                                    <th class="py-3 px-3 whitespace-nowrap">Tahun</th>
                                    <th class="py-3 px-3 whitespace-nowrap">Stok</th>
                                    <th class="py-3 px-3 whitespace-nowrap">Dipinjam</th>
                                    <th class="py-3 px-3 whitespace-nowrap">Denda / Hari</th>
                                    <th class="py-3 pl-3 pr-4 text-right whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="books-table-body" class="divide-y divide-gray-100 text-xs sm:text-[13px]"></tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-gray-600 bg-white rounded-b-xl">
                        <div id="books-pagination-summary" class="font-medium">
                            Showing 1 to 4 of 4 results
                        </div>
                        <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white self-center sm:self-auto">
                            <span class="px-3 py-1.5 text-gray-500 border-r border-gray-200 bg-gray-50 font-medium">Per page</span>
                            <select id="books-per-page" onchange="renderBooksTable()" class="px-2.5 py-1.5 font-semibold text-gray-800 bg-white focus:outline-none">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </section>
            </div>

            <!-- ===================================================================== -->
            <!-- VIEW 3B: CREATE BOOK (Books > Create)                                 -->
            <!-- ===================================================================== -->
            <div id="view-create-book" class="hidden view-section space-y-6">
                <div>
                    <nav class="flex items-center gap-1.5 text-xs font-medium text-gray-500 mb-1">
                        <button type="button" onclick="closeCreateBookView()" class="hover:text-forest-700 hover:underline transition-colors">
                            Books
                        </button>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span class="text-forest-700 font-semibold">Create</span>
                    </nav>
                    <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                        Create Book
                    </h1>
                </div>

                <form id="create-book-form" onsubmit="handleCreateBookSubmit(event, false)" class="space-y-6">
                    <!-- Bagian 1: Klasifikasi & Identitas Buku -->
                    <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-display text-sm sm:text-base font-bold text-gray-900">
                                Klasifikasi &amp; Identitas Buku
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Pilih kategori buku, kode koleksi (bila non-ISBN), judul, dan penulis.
                            </p>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="create-book-category" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Kategori<span class="text-rose-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <select id="create-book-category" onchange="syncCategoryDenda(this.value, 'create-book-denda-pill')" required class="flex-1 px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                            <option value="">Select an option</option>
                                        </select>
                                        <button type="button" onclick="openNewCategoryModal()" title="Tambah Kategori Baru" class="p-2.5 rounded-lg bg-gold-100 hover:bg-gold-200 text-amber-950 border border-gold-300 transition-colors shrink-0">
                                            <i data-lucide="plus" class="w-4 h-4 stroke-[2.4]"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-xs text-gray-500 font-medium">Tarif Denda Otomatis:</span>
                                        <span id="create-book-denda-pill" class="px-2.5 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Rp 200 / hari (7 hari kerja)</span>
                                    </div>
                                </div>

                                <div>
                                    <label for="create-book-title" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Judul Buku<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="create-book-title" required placeholder="Masukkan judul buku..." class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="create-book-author" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Penulis<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="create-book-author" required placeholder="Nama penulis atau penyusun..." class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>

                                <div>
                                    <label for="create-book-code" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Kode Koleksi (Opsional / Non-ISBN)
                                    </label>
                                    <input type="text" id="create-book-code" placeholder="Contoh: AKT-BK-012" class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Bagian 2: Detail Terbit & Identitas ISBN -->
                    <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-display text-sm sm:text-base font-bold text-gray-900">
                                Detail Terbit &amp; Identitas ISBN
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Informasi penerbit, tahun terbit, dan pencarian data otomatis lewat ISBN.
                            </p>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="create-book-publisher" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Penerbit<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="create-book-publisher" required placeholder="Nama penerbit..." class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>

                                <div>
                                    <label for="create-book-year" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Tahun Terbit<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" id="create-book-year" required min="1950" max="2030" placeholder="Contoh: 2025" class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>

                                <div>
                                    <label for="create-book-isbn" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        ISBN (Opsional)
                                    </label>
                                    <div class="flex items-stretch">
                                        <input type="text" id="create-book-isbn" placeholder="978-xxx-xxx-xxx-x" class="flex-1 px-3.5 py-2.5 bg-white border border-gray-300 rounded-l-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                        <button type="button" onclick="lookupIsbnData()" title="Cari Metadata ISBN" class="px-3.5 bg-forest-50 hover:bg-gold-100 text-forest-800 hover:text-amber-950 border border-l-0 border-gray-300 rounded-r-lg transition-colors flex items-center justify-center">
                                            <i data-lucide="search" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1">
                                        Nomor ISBN standar internasional.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Bagian 3: Stok, Cover & Deskripsi Buku -->
                    <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-display text-sm sm:text-base font-bold text-gray-900">
                                Stok, Cover &amp; Deskripsi Buku
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Atur stok tersedia, gambar cover, dan deskripsi isi buku / skripsi.
                            </p>
                        </div>
                        <div class="p-5 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                                <div>
                                    <label for="create-book-stock" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Stok Tersedia<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" id="create-book-stock" required min="0" value="1" class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>

                                <div>
                                    <span class="block text-xs font-semibold text-gray-700 mb-1.5">Cover Buku</span>
                                    <label for="create-book-cover-file" id="create-book-dropzone" class="flex flex-col items-center justify-center w-full h-24 px-4 border-2 border-dashed border-gray-300 hover:border-forest-600 bg-gray-50/60 hover:bg-forest-50/40 rounded-xl cursor-pointer transition-colors text-center">
                                        <div id="cover-dropzone-empty" class="text-xs text-gray-500">
                                            Drag &amp; Drop your files or <span class="font-bold text-forest-700 hover:underline">Browse</span>
                                        </div>
                                        <div id="cover-dropzone-selected" class="hidden items-center gap-2 text-xs font-semibold text-forest-900">
                                            <i data-lucide="image" class="w-4 h-4 text-amber-600"></i>
                                            <span id="cover-file-name-label" class="truncate max-w-[220px]">-</span>
                                        </div>
                                        <input type="file" id="create-book-cover-file" accept="image/*" onchange="handleBookCoverFile(this.files[0])" class="hidden">
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="create-book-synopsis" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                    Deskripsi Buku
                                </label>
                                <textarea id="create-book-synopsis" rows="4" placeholder="Tulis deskripsi / ringkasan isi buku atau skripsi di sini..." class="w-full p-3.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600"></textarea>
                            </div>
                        </div>
                    </section>

                    <!-- 3 TOMBOL AKSI BAWAH FORM -->
                    <div class="flex flex-wrap items-center gap-2.5 pt-1">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-forest-800 hover:bg-forest-900 active:scale-[0.99] text-white font-bold text-xs sm:text-sm shadow-2xs ring-1 ring-gold-400/40 transition-all">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-gold-400"></i>
                            <span>Create</span>
                        </button>
                        <button type="button" onclick="handleCreateBookSubmit(event, true)" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-gold-100 hover:bg-gold-200 active:scale-[0.99] text-amber-950 font-bold text-xs sm:text-sm border border-gold-300 shadow-2xs transition-all">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-amber-800"></i>
                            <span>Create &amp; create another</span>
                        </button>
                        <button type="button" onclick="closeCreateBookView()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-white hover:bg-gray-50 active:scale-[0.99] text-gray-700 font-semibold text-xs sm:text-sm border border-gray-300 shadow-2xs transition-all">
                            <i data-lucide="x" class="w-4 h-4 text-gray-500"></i>
                            <span>Cancel</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ===================================================================== -->
            <!-- VIEW 4A: PEMINJAMAN & PENGEMBALIAN BUKU (Sirkulasi > List)            -->
            <!-- ===================================================================== -->
            <div id="view-sirkulasi" class="hidden view-section space-y-6">

                <!-- Breadcrumb & Header Halaman + 3 Tombol Aksi Kanan Atas -->
                <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                    <div>
                        <nav class="flex items-center gap-1.5 text-xs font-medium text-gray-500 mb-1">
                            <span>Peminjaman &amp; Pengembalian Buku</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
                            <span class="text-forest-700 font-semibold">List</span>
                        </nav>
                        <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                            Peminjaman &amp; Pengembalian Buku
                        </h1>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <button type="button" onclick="openLookupBookingModal()" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-forest-800 hover:bg-forest-900 active:scale-[0.99] text-gold-300 font-bold text-xs sm:text-sm border border-forest-700/80 shadow-2xs transition-all">
                            <i data-lucide="scan-line" class="w-4 h-4 text-gold-400 stroke-[2.2]"></i>
                            <span>Input / Scan Kode Booking</span>
                        </button>
                        <button type="button" onclick="exportExcel('denda')" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-gold-400 hover:bg-gold-500 active:scale-[0.99] text-forest-950 font-bold text-xs sm:text-sm shadow-2xs border border-gold-500/40 transition-all">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4 stroke-[2.2]"></i>
                            <span>Export Laporan Denda (Excel)</span>
                        </button>
                        <button type="button" onclick="exportExcel('sirkulasi')" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-forest-600 hover:bg-forest-700 active:scale-[0.99] text-white font-semibold text-xs sm:text-sm shadow-2xs transition-all">
                            <i data-lucide="download" class="w-4 h-4 text-gold-200 stroke-[2.2]"></i>
                            <span>Export Sirkulasi (Excel)</span>
                        </button>
                        <button type="button" onclick="openCreateLoanView()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-950 active:scale-[0.99] text-white font-bold text-xs sm:text-sm shadow-xs ring-1 ring-gold-400/50 transition-all">
                            <i data-lucide="plus" class="w-4 h-4 text-gold-400 stroke-[2.5]"></i>
                            <span>Catat Peminjaman Baru</span>
                        </button>
                    </div>
                </div>

                <!-- 4 KARTU STATISTIK SIRKULASI -->
                <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div onclick="filterByBookingStatus()" class="cursor-pointer bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-forest-300 transition-colors group">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-500 group-hover:text-forest-700">Booking Menunggu Diambil</p>
                            <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <i data-lucide="ticket" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p id="stat-booking" class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">0 Transaksi</p>
                        <p class="text-xs font-semibold text-forest-700 mt-1.5 flex items-center gap-1 group-hover:underline">
                            <span>Klik untuk lihat daftar booking</span>
                            <span>🎫</span>
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-gold-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-500">Sedang Dipinjam</p>
                            <span class="w-7 h-7 rounded-lg bg-gold-100 text-amber-700 flex items-center justify-center">
                                <i data-lucide="book-open" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p id="stat-borrowed" class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">2 Buku</p>
                        <p class="text-xs font-medium text-amber-700 mt-1.5 flex items-center gap-1.5">
                            <span>Buku yang berada di mahasiswa</span>
                            <span>📖</span>
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-rose-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-500">Melewati Jatuh Tempo</p>
                            <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p id="stat-overdue" class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">2 Transaksi</p>
                        <p class="text-xs font-medium text-rose-600 mt-1.5 flex items-center gap-1.5">
                            <span>Perlu penagihan pengembalian</span>
                            <span>⚠️</span>
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-emerald-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-500">Total Denda Terkumpul</p>
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                                <i data-lucide="banknote" class="w-4 h-4"></i>
                            </span>
                        </div>
                        <p id="stat-fine" class="font-display text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2">Rp 1.400</p>
                        <p class="text-xs font-medium text-emerald-700 mt-1.5 flex items-center gap-1.5">
                            <span>Dari transaksi selesai &amp; buku hilang</span>
                            <span>💵</span>
                        </p>
                    </div>
                </section>

                <!-- TABEL PEMINJAMAN & PENGEMBALIAN BUKU -->
                <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-visible">
                    <div class="p-3.5 sm:p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span id="bulk-selected-info" class="hidden px-2.5 py-1 rounded-lg bg-gold-100 text-amber-900 border border-gold-300 text-xs font-bold">
                                0 baris dipilih
                            </span>
                            <span class="text-xs font-medium text-gray-500 hidden sm:inline">
                                Klik <b class="text-forest-700">Kembalikan</b> untuk proses pengembalian &amp; denda.
                            </span>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-auto relative">
                            <div class="relative">
                                <input type="text" id="search-input" oninput="renderTable()" placeholder="Search" class="w-56 sm:w-64 pl-9 pr-3 py-2 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5"></i>
                            </div>

                            <div class="relative">
                                <button type="button" id="filter-toggle-btn" onclick="toggleFilterPopover()" class="p-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-600 relative transition-colors" title="Filter Transaksi">
                                    <i data-lucide="filter" class="w-4 h-4"></i>
                                    <span id="active-filter-badge" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-400 text-forest-950 text-[10px] font-extrabold flex items-center justify-center border border-white">
                                        0
                                    </span>
                                </button>

                                <div id="filter-popover" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-200 p-4 z-30 space-y-3.5">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                        <span class="text-sm font-bold text-gray-900">Filters</span>
                                        <button type="button" onclick="resetFilters()" class="text-xs font-semibold text-rose-600 hover:underline">Reset</button>
                                    </div>

                                    <div>
                                        <label for="filter-status" class="block text-xs font-semibold text-gray-700 mb-1">Filter Status</label>
                                        <select id="filter-status" onchange="onFilterChange()" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600">
                                            <option value="ALL">All (Semua Status)</option>
                                            <option value="Terlambat">Terlambat</option>
                                            <option value="Sedang Dipinjam">Sedang Dipinjam</option>
                                            <option value="Booking">Booking</option>
                                            <option value="Dikembalikan">Dikembalikan</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="filter-jaminan" class="block text-xs font-semibold text-gray-700 mb-1">Filter Jaminan</label>
                                        <select id="filter-jaminan" onchange="onFilterChange()" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600">
                                            <option value="ALL">All (KTM &amp; Kartu Perpus)</option>
                                            <option value="KTM">KTM</option>
                                            <option value="Kartu Perpustakaan">Kartu Perpustakaan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/90 border-b border-gray-200 text-xs font-semibold text-gray-700">
                                    <th class="py-3.5 pl-4 pr-2 w-10">
                                        <input type="checkbox" id="select-all-checkbox" onchange="toggleSelectAll(this)" class="rounded border-gray-300 text-forest-700 focus:ring-forest-600">
                                    </th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Kode Booking</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Peminjam (NIM, Kelas &amp; No. HP)</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Koleksi Buku</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Tgl Pinjam</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Jatuh Tempo / Batas Ambil</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Status</th>
                                    <th class="py-3.5 px-3 whitespace-nowrap">Denda</th>
                                    <th class="py-3.5 pl-3 pr-4 text-right whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sirkulasi-table-body" class="divide-y divide-gray-100 text-xs sm:text-[13px]"></tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-gray-600 bg-white rounded-b-xl">
                        <div id="pagination-summary" class="font-medium">
                            Showing 1 to 4 of 4 results
                        </div>

                        <div class="flex items-center gap-2 self-center sm:self-auto">
                            <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
                                <span class="px-3 py-1.5 text-gray-500 border-r border-gray-200 bg-gray-50 font-medium">Per page</span>
                                <select id="per-page-select" onchange="renderTable()" class="px-2.5 py-1.5 font-semibold text-gray-800 bg-white focus:outline-none">
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- ===================================================================== -->
            <!-- VIEW 4B: CREATE PEMINJAMAN (Sirkulasi > Create)                       -->
            <!-- ===================================================================== -->
            <div id="view-create-loan" class="hidden view-section space-y-6">
                <div>
                    <nav class="flex items-center gap-1.5 text-xs font-medium text-gray-500 mb-1">
                        <button type="button" onclick="closeCreateLoanView()" class="hover:text-forest-700 hover:underline transition-colors">
                            Peminjaman &amp; Pengembalian Buku
                        </button>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span class="text-forest-700 font-semibold">Create</span>
                    </nav>
                    <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">
                        Create Peminjaman
                    </h1>
                </div>

                <form id="create-loan-form" onsubmit="handleCreateLoanSubmit(event, false)" class="space-y-6">
                    <!-- Bagian 1: Cek Kode Booking Mahasiswa -->
                    <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-display text-sm sm:text-base font-bold text-gray-900">
                                Cek Kode Booking Mahasiswa (Dari Web Katalog)
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Pilih atau ketik kode <b>AKT-XXXX</b> (berlaku 1×24 jam) untuk isi otomatis.
                            </p>
                        </div>
                        <div class="p-5 space-y-3">
                            <div>
                                <label for="create-booking-code-input" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                    Cari Kode Booking (AKT-XXXX)
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="create-booking-code-input"
                                        list="web-catalog-booking-list"
                                        oninput="handleBookingCodeInput(this.value)"
                                        placeholder="Contoh: AKT-8X2P"
                                        class="w-full px-3.5 py-2.5 pr-10 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600"
                                    >
                                    <datalist id="web-catalog-booking-list"></datalist>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 absolute right-3.5 top-3 pointer-events-none"></i>
                                </div>

                                <div class="mt-2.5 flex flex-wrap items-center gap-2">
                                    <span class="text-[11px] font-medium text-gray-500">Booking aktif:</span>
                                    <div id="quick-booking-chips" class="flex flex-wrap items-center gap-1.5"></div>
                                </div>
                            </div>

                            <div id="booking-detected-banner" class="hidden p-3 rounded-lg bg-gold-50 border border-gold-300 flex items-center justify-between gap-2 text-xs">
                                <div class="flex items-center gap-2 text-amber-950">
                                    <i data-lucide="ticket" class="w-4 h-4 text-amber-600 shrink-0"></i>
                                    <span id="booking-detected-text" class="font-medium">Kode booking valid.</span>
                                </div>
                                <button type="button" onclick="clearBookingCodeSelection()" class="text-[11px] font-bold text-amber-800 hover:underline shrink-0">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </section>

                    <!-- Bagian 2: Identitas Peminjam -->
                    <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-display text-sm sm:text-base font-bold text-gray-900">
                                Identitas Peminjam (Anggota &amp; Kartu)
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Pilih Nama/NIM dan kartu jaminan (maks. 2 buku aktif/NIM).
                            </p>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="create-member-name" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Nama Anggota<span class="text-rose-500">*</span>
                                    </label>
                                    <select id="create-member-name" onchange="syncMemberByIndex(this.value)" required class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                        <option value="">Select an option</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="create-member-nim" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        NIM / No. Anggota<span class="text-rose-500">*</span>
                                    </label>
                                    <select id="create-member-nim" onchange="syncMemberByIndex(this.value)" required class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                        <option value="">Select an option</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="create-jaminan-type" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Kartu Jaminan<span class="text-rose-500">*</span>
                                    </label>
                                    <select id="create-jaminan-type" required class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                        <option value="KTM" selected>KTM</option>
                                        <option value="Kartu Perpustakaan">Kartu Perpustakaan</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <span class="block text-xs font-semibold text-gray-700 mb-1">Detail Anggota</span>
                                <p id="create-member-placeholder" class="text-xs italic text-gray-400">
                                    Pilih anggota atau kode booking.
                                </p>

                                <div id="create-member-detail-card" class="hidden mt-2 p-4 rounded-xl bg-gradient-to-r from-forest-50 to-gold-50/70 border border-forest-200 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
                                    <div>
                                        <span class="text-[11px] text-gray-500 block">Nama &amp; Kelas</span>
                                        <span id="detail-member-nama" class="font-bold text-forest-950 text-sm">-</span>
                                    </div>
                                    <div>
                                        <span class="text-[11px] text-gray-500 block">NIM &amp; No. HP</span>
                                        <span id="detail-member-nim-hp" class="font-semibold text-gray-800">-</span>
                                    </div>
                                    <div>
                                        <span class="text-[11px] text-gray-500 block">Status Anggota</span>
                                        <span class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px]">
                                            <i data-lucide="badge-check" class="w-3.5 h-3.5"></i> Aktif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-[11px] text-gray-500 block">Pinjaman Aktif</span>
                                        <span id="detail-member-quota" class="font-bold text-amber-800">0 / 2 Buku Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Bagian 3: Detail Buku & Masa Peminjaman -->
                    <section class="bg-white rounded-xl border border-gray-200/90 shadow-2xs overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <h2 class="font-display text-sm sm:text-base font-bold text-gray-900">
                                Detail Buku &amp; Masa Peminjaman
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Hari kerja (Sen–Jum): TA/Skripsi <b>14 hari</b>, Non-TA &amp; PKL <b>7 hari</b>.
                            </p>
                        </div>
                        <div class="p-5 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="create-book-select" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Buku<span class="text-rose-500">*</span>
                                    </label>
                                    <select id="create-book-select" onchange="updateWorkdayDueDatePreview()" required class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                        <option value="">Select an option</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="create-loan-date" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Tanggal Pinjam<span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" id="create-loan-date" value="2026-11-22" onchange="updateWorkdayDueDatePreview()" required class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                                </div>
                            </div>

                            <div>
                                <span class="block text-xs font-semibold text-gray-700 mb-1">Jatuh Tempo (Hari Kerja)</span>
                                <p id="create-due-placeholder" class="text-xs italic text-gray-400">
                                    Pilih buku untuk hitung jatuh tempo.
                                </p>

                                <div id="create-due-result-box" class="hidden mt-1.5 p-3.5 rounded-xl bg-forest-50 border border-forest-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-forest-800 text-gold-300 flex items-center justify-center shrink-0">
                                            <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-forest-950">
                                                Jatuh Tempo: <span id="due-date-formatted-text" class="text-forest-700 underline decoration-gold-400 decoration-2">-</span>
                                            </p>
                                            <p id="due-date-rule-note" class="text-[11px] text-gray-600 mt-0.5">-</p>
                                        </div>
                                    </div>
                                    <span id="due-workdays-badge" class="self-start sm:self-center px-2.5 py-1 rounded-md bg-gold-100 text-amber-900 border border-gold-300 text-[11px] font-bold">
                                        7 Hari Kerja
                                    </span>
                                </div>
                            </div>

                            <div>
                                <span class="block text-xs font-semibold text-gray-700 mb-2">
                                    Status Transaksi<span class="text-rose-500">*</span>
                                </span>
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <button type="button" id="status-pill-dipinjam" onclick="selectCreateStatus('Sedang Dipinjam')" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-gold-500 text-forest-950 font-bold text-xs shadow-2xs ring-2 ring-gold-400/60 transition-all">
                                        <i data-lucide="check-circle-2" class="w-4 h-4 stroke-[2.3]"></i>
                                        <span>Sudah Diambil (Dipinjam)</span>
                                    </button>
                                    <button type="button" id="status-pill-booking" onclick="selectCreateStatus('Booking')" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-semibold text-xs border border-gray-300 transition-all">
                                        <i data-lucide="clock" class="w-4 h-4 text-gray-500"></i>
                                        <span>Booking (Menunggu Diambil)</span>
                                    </button>
                                </div>
                                <input type="hidden" id="create-status-value" value="Sedang Dipinjam">
                            </div>

                            <div class="pt-1">
                                <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                                    <input type="checkbox" id="create-extend-toggle" onchange="updateWorkdayDueDatePreview()" class="sr-only peer">
                                    <div class="relative w-10 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-forest-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-forest-700"></div>
                                    <span class="text-xs font-semibold text-gray-800">Perpanjang (+3 Hari Kerja)</span>
                                </label>
                            </div>
                        </div>
                    </section>

                    <!-- 3 TOMBOL AKSI BAWAH FORM -->
                    <div class="flex flex-wrap items-center gap-2.5 pt-1">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-forest-800 hover:bg-forest-900 active:scale-[0.99] text-white font-bold text-xs sm:text-sm shadow-2xs ring-1 ring-gold-400/40 transition-all">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-gold-400"></i>
                            <span>Create</span>
                        </button>
                        <button type="button" onclick="handleCreateLoanSubmit(event, true)" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-gold-100 hover:bg-gold-200 active:scale-[0.99] text-amber-950 font-bold text-xs sm:text-sm border border-gold-300 shadow-2xs transition-all">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-amber-800"></i>
                            <span>Create &amp; create another</span>
                        </button>
                        <button type="button" onclick="closeCreateLoanView()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-white hover:bg-gray-50 active:scale-[0.99] text-gray-700 font-semibold text-xs sm:text-sm border border-gray-300 shadow-2xs transition-all">
                            <i data-lucide="x" class="w-4 h-4 text-gray-500"></i>
                            <span>Cancel</span>
                        </button>
                    </div>
                </form>
            </div>

        </main>
    </div>

    <!-- ===================================================================== -->
    <!-- MODALS                                                                -->
    <!-- ===================================================================== -->

    <!-- MODAL ANGGOTA: EDIT ANGGOTA -->
    <div id="modal-anggota-edit" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full border border-gray-200 shadow-2xl overflow-hidden">
            <div class="bg-forest-900 px-5 py-4 flex items-center justify-between text-white">
                <h3 class="font-display font-bold text-sm">Ubah Data Anggota</h3>
                <button type="button" onclick="closeModal('modal-anggota-edit')" class="text-forest-200 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form onsubmit="submitAnggotaEdit(event)" class="p-5 space-y-3 text-xs sm:text-sm">
                <input type="hidden" id="edit-anggota-id">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">NIM / No. Anggota*</label>
                        <input type="text" id="edit-anggota-nim" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kelas*</label>
                        <input type="text" id="edit-anggota-kelas" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap*</label>
                    <input type="text" id="edit-anggota-nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Program Studi*</label>
                    <select id="edit-anggota-prodi" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white">
                        <option value="Komputerisasi Akuntansi (S. Tr)">Komputerisasi Akuntansi (S. Tr)</option>
                        <option value="Perbankan Syariah (S. Tr)">Perbankan Syariah (S. Tr)</option>
                        <option value="Analis Keuangan (S. Tr)">Analis Keuangan (S. Tr)</option>
                        <option value="Akuntansi Manajerial (S. Tr)">Akuntansi Manajerial (S. Tr)</option>
                        <option value="Akuntansi (D3)">Akuntansi (D3)</option>
                        <option value="Keuangan dan Perbankan (D3)">Keuangan dan Perbankan (D3)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Angkatan*</label>
                        <input type="number" id="edit-anggota-angkatan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">No. HP*</label>
                        <input type="text" id="edit-anggota-hp" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email*</label>
                    <input type="email" id="edit-anggota-email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div class="pt-2 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-anggota-edit')" class="px-3.5 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest-800 hover:bg-forest-900 text-white font-bold text-xs">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL ANGGOTA: IMPORT CSV/EXCEL -->
    <div id="modal-anggota-import" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full border border-gray-200 shadow-2xl overflow-hidden">
            <div class="bg-forest-800 px-5 py-4 flex items-center justify-between text-white">
                <h3 class="font-display font-bold text-sm">Impor Data Anggota (Excel/CSV)</h3>
                <button type="button" onclick="closeModal('modal-anggota-import')" class="text-forest-200 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-5 space-y-3 text-xs">
                <p class="text-gray-600">Format baris: <code>NIM,Nama,Program Studi,Kelas,Angkatan,No HP,Email</code></p>
                <textarea id="anggota-import-csv" rows="4" class="w-full p-3 border border-gray-300 rounded-xl font-mono text-[11px]">4.41.24.0.12,Nadia Putri Maharani,Komputerisasi Akuntansi (S. Tr),KA-A,2024,081299887766,nadia.putri@student.polines.ac.id</textarea>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" onclick="closeModal('modal-anggota-import')" class="px-3.5 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold">Batal</button>
                    <button type="button" onclick="submitAnggotaImport()" class="px-4 py-2 rounded-xl bg-forest-700 hover:bg-forest-800 text-white font-bold">Impor Data</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL BUKU: TAMBAH / EDIT BUKU -->
    <div id="modal-book-form" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full border border-gray-200 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="bg-forest-900 px-5 py-4 flex items-center justify-between text-white shrink-0">
                <h3 id="book-modal-title" class="font-display font-bold text-sm">Edit Book</h3>
                <button type="button" onclick="closeModal('modal-book-form')" class="text-forest-200 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form onsubmit="submitBookForm(event)" class="p-5 space-y-3.5 text-xs sm:text-sm overflow-y-auto">
                <input type="hidden" id="book-edit-id">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="book-kode-input" class="block text-xs font-bold text-gray-700 mb-1">Kode Buku*</label>
                        <input type="text" id="book-kode-input" placeholder="Contoh: AKK-001" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label for="book-tahun-input" class="block text-xs font-bold text-gray-700 mb-1">Tahun Terbit*</label>
                        <input type="number" id="book-tahun-input" required value="2024" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                </div>

                <div>
                    <label for="book-judul-input" class="block text-xs font-bold text-gray-700 mb-1">Judul Buku*</label>
                    <input type="text" id="book-judul-input" required placeholder="Judul buku" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="book-kategori-input" class="block text-xs font-bold text-gray-700 mb-1">Kategori*</label>
                        <select id="book-kategori-input" onchange="syncModalBookDenda(this.value)" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white"></select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tarif Denda / Hari (Otomatis)</label>
                        <input type="text" id="book-denda-display" readonly value="Rp 200 / hari" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs font-bold text-forest-800">
                    </div>
                </div>

                <div>
                    <label for="book-penulis-input" class="block text-xs font-bold text-gray-700 mb-1">Penulis*</label>
                    <input type="text" id="book-penulis-input" required placeholder="Nama penulis" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="book-isbn-input" class="block text-xs font-bold text-gray-700 mb-1">ISBN (Opsional)</label>
                        <input type="text" id="book-isbn-input" placeholder="978-xxx-xxx-xxx-x" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label for="book-penerbit-input" class="block text-xs font-bold text-gray-700 mb-1">Penerbit</label>
                        <input type="text" id="book-penerbit-input" placeholder="Nama penerbit" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="book-stok-input" class="block text-xs font-bold text-gray-700 mb-1">Stok Tersedia*</label>
                        <input type="number" id="book-stok-input" min="0" required value="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label for="book-dipinjam-input" class="block text-xs font-bold text-gray-700 mb-1">Dipinjam</label>
                        <input type="number" id="book-dipinjam-input" min="0" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Cover Buku</label>
                    <div class="flex items-center gap-3 p-2.5 bg-gray-50/80 rounded-xl border border-gray-200">
                        <div id="book-edit-cover-preview-wrapper" class="w-12 h-16 rounded-lg border border-gray-200 bg-white flex items-center justify-center overflow-hidden shrink-0 shadow-2xs">
                            <img id="book-edit-cover-img" src="" alt="Cover" class="w-full h-full object-cover hidden">
                            <i id="book-edit-cover-placeholder" data-lucide="image" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <input type="file" id="book-cover-input" accept="image/*" onchange="previewEditBookCover(this)" class="block w-full text-xs text-gray-600 file:mr-2.5 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-forest-100 file:text-forest-900 hover:file:bg-forest-200 cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-0.5">JPG, PNG, WEBP (Maks. 3MB). Biarkan kosong jika tidak diubah.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="book-sinopsis-input" class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Buku</label>
                    <textarea id="book-sinopsis-input" rows="3" placeholder="Tulis deskripsi / ringkasan isi buku atau skripsi di sini..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs"></textarea>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end gap-2 shrink-0">
                    <button type="button" onclick="closeModal('modal-book-form')" class="px-3.5 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest-800 hover:bg-forest-900 text-white font-bold text-xs">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL BUKU: VIEW DETAIL -->
    <div id="modal-book-view" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full border border-gray-200 shadow-2xl overflow-hidden max-h-[85vh] flex flex-col">
            <div class="bg-forest-900 px-5 py-4 flex items-center justify-between text-white shrink-0">
                <h3 class="font-display font-bold text-sm">Detail Koleksi Buku</h3>
                <button type="button" onclick="closeModal('modal-book-view')" class="text-forest-200 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="book-view-body" class="p-5 space-y-2.5 text-xs overflow-y-auto"></div>
            <div class="px-5 py-3 border-t border-gray-100 flex justify-end shrink-0">
                <button type="button" onclick="closeModal('modal-book-view')" class="px-4 py-1.5 rounded-lg bg-forest-800 text-white font-bold text-xs">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL BUKU: NEW KATEGORI -->
    <div id="modal-new-category" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full border border-gray-200 shadow-2xl overflow-hidden">
            <div class="bg-forest-900 px-5 py-4 flex items-center justify-between text-white">
                <h3 class="font-display font-bold text-sm">New Kategori</h3>
                <button type="button" onclick="closeModal('modal-new-category')" class="text-forest-200 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form onsubmit="submitNewCategory(event)" class="p-5 space-y-3 text-xs">
                <div>
                    <label for="new-category-name" class="block text-xs font-bold text-gray-700 mb-1">Nama Kategori*</label>
                    <input type="text" id="new-category-name" required placeholder="Contoh: Akuntansi Keuangan" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-new-category')" class="px-3.5 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-gold-400 hover:bg-gold-500 text-forest-950 font-bold">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL BUKU: IMPORT CSV -->
    <div id="modal-book-import" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full border border-gray-200 shadow-2xl overflow-hidden">
            <div class="bg-forest-800 px-5 py-4 flex items-center justify-between text-white">
                <h3 class="font-display font-bold text-sm">Import Buku (Excel/CSV)</h3>
                <button type="button" onclick="closeModal('modal-book-import')" class="text-forest-200 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-5 space-y-3 text-xs">
                <p class="text-gray-600">Format baris: <code>Kode,Judul,Kategori,Penulis,Tahun,Stok,Dipinjam</code></p>
                <textarea id="book-import-csv" rows="4" class="w-full p-3 border border-gray-300 rounded-xl font-mono text-[11px]">BK-003,Akuntansi Biaya,Akuntansi,Mulyadi,2021,4,0</textarea>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" onclick="closeModal('modal-book-import')" class="px-3.5 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold">Batal</button>
                    <button type="button" onclick="submitBookImport()" class="px-4 py-2 rounded-xl bg-forest-700 hover:bg-forest-800 text-white font-bold">Import Data</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SIRKULASI: PROSES PENGEMBALIAN BUKU -->
    <div id="modal-return-book" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full border border-gray-200 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-forest-900 to-forest-800 px-5 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gold-400 text-forest-950 flex items-center justify-center">
                        <i data-lucide="rotate-ccw" class="w-4 h-4 stroke-[2.3]"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-sm sm:text-base">Konfirmasi Pengembalian Buku</h3>
                        <p class="text-[11px] text-forest-200">Verifikasi kondisi buku &amp; denda keterlambatan</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-return-book')" class="text-forest-200 hover:text-white p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-5 space-y-4 text-xs sm:text-sm">
                <input type="hidden" id="return-target-id">

                <div class="p-3.5 rounded-xl bg-forest-50/80 border border-forest-200 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span id="ret-modal-kode" class="px-2 py-0.5 rounded bg-forest-800 text-gold-300 font-bold text-xs">AKT-W0HG</span>
                        <span id="ret-modal-status-badge" class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 font-bold text-[11px]">Terlambat</span>
                    </div>
                    <p id="ret-modal-nama" class="font-bold text-gray-900 text-sm pt-1">Nur Intan Cahyani</p>
                    <p id="ret-modal-meta" class="text-xs text-gray-600">NIM: 4.41.24.0.24 • Kelas: KA-B • Jaminan: KTM</p>
                    <div class="pt-1.5 border-t border-forest-200/80">
                        <span class="text-[11px] text-gray-500 block">Buku yang dikembalikan:</span>
                        <span id="ret-modal-buku" class="font-bold text-forest-900">Hujan (Fiksi)</span>
                    </div>
                </div>

                <div>
                    <label for="ret-kondisi-select" class="block text-xs font-bold text-gray-700 mb-1">Kondisi Buku Saat Dikembalikan</label>
                    <select id="ret-kondisi-select" onchange="recalculateReturnFine()" class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600">
                        <option value="baik" selected>Baik / Lengkap (Normal)</option>
                        <option value="rusak">Rusak Ringan (+ Denda Rp 5.000)</option>
                        <option value="hilang">Buku Hilang (+ Denda Penggantian Rp 50.000)</option>
                    </select>
                </div>

                <div class="p-3.5 rounded-xl bg-gold-50 border border-gold-300 flex items-center justify-between gap-3">
                    <div>
                        <span class="text-xs font-bold text-amber-950 block">Total Denda yang Dibayarkan</span>
                        <span id="ret-modal-fine-note" class="text-[11px] text-amber-800">Otomatis dihitung dari keterlambatan</span>
                    </div>
                    <div class="flex items-center gap-1 bg-white px-2.5 py-1.5 rounded-lg border border-gold-400">
                        <span class="text-xs font-bold text-gray-500">Rp</span>
                        <input type="number" id="ret-modal-fine-input" min="0" step="100" value="0" class="w-20 text-right font-extrabold text-gray-900 focus:outline-none">
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-return-book')" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="button" onclick="confirmReturnAction()" class="px-4 py-2.5 rounded-xl bg-gold-400 hover:bg-gold-500 text-forest-950 font-extrabold inline-flex items-center gap-1.5 shadow-xs">
                        <i data-lucide="check-circle-2" class="w-4 h-4 stroke-[2.3]"></i>
                        <span>Selesaikan Pengembalian</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SIRKULASI: KONFIRMASI PENYERAHAN BUKU BOOKING (HANDOVER) -->
    <div id="modal-handover-book" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full border border-gray-200 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-forest-950 via-forest-900 to-forest-800 px-5 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center shadow-xs">
                        <i data-lucide="hand-helping" class="w-4 h-4 stroke-[2.3]"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-sm sm:text-base">Konfirmasi Penyerahan Buku</h3>
                        <p class="text-[11px] text-forest-200">Verifikasi jaminan fisik &amp; serahkan buku ke peminjam</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-handover-book')" class="text-forest-200 hover:text-white p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-5 space-y-4 text-xs sm:text-sm">
                <input type="hidden" id="handover-target-id">

                <!-- Ringkasan Info Booking -->
                <div class="p-3.5 rounded-xl bg-forest-50/80 border border-forest-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span id="handover-modal-kode" class="px-2.5 py-1 rounded-md bg-forest-900 text-gold-300 font-bold text-xs">AKT-TNTV</span>
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 font-bold text-[11px] flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                            Booking Aktif
                        </span>
                    </div>
                    <div>
                        <p id="handover-modal-nama" class="font-bold text-gray-900 text-sm">Ahmad Fauzi</p>
                        <p id="handover-modal-meta" class="text-xs text-gray-600">NIM: 4.41.24.0.01 • Kelas: KA-A • HP: 081234567890</p>
                    </div>
                    <div class="pt-2 border-t border-forest-200/80">
                        <span class="text-[11px] text-gray-500 block">Koleksi Buku yang Diambil:</span>
                        <p id="handover-modal-buku" class="font-bold text-forest-950 text-sm">Seporsi Mie Ayam Sebelum Mati</p>
                        <p id="handover-modal-kategori-rak" class="text-xs text-forest-700 font-medium mt-0.5">Kategori: Fiksi</p>
                    </div>
                </div>

                <!-- Pilihan Jaminan Fisik -->
                <div>
                    <label for="handover-jaminan-select" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Jaminan Fisik Mahasiswa yang Diterima <span class="text-rose-500">*</span>
                    </label>
                    <select id="handover-jaminan-select" class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg font-medium text-gray-800 text-xs focus:outline-none focus:ring-2 focus:ring-forest-600">
                        <option value="KTM" selected>KTM Asli Polines (Direkomendasikan)</option>
                        <option value="Kartu Perpustakaan">Kartu Perpustakaan Polines</option>
                        <option value="Kartu Asuransi">Kartu Asuransi Mahasiswa</option>
                    </select>
                    <p class="text-[11px] text-gray-500 mt-1">Jaminan fisik wajib disimpan di meja sirkulasi hingga buku dikembalikan.</p>
                </div>

                <!-- Durasi & Info Jatuh Tempo Otomatis -->
                <div class="p-3 rounded-lg bg-gray-50 border border-gray-200 text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Masa Peminjaman:</span>
                        <span id="handover-modal-durasi" class="font-bold text-gray-800">7 Hari Kerja</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal Pinjam:</span>
                        <span class="font-semibold text-gray-800">Hari ini ({{ $formattedDate ?? '22 Nov 2026' }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tarif Denda:</span>
                        <span id="handover-modal-denda-rate" class="font-bold text-rose-600">Rp 200 / hari kerja</span>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-2 flex justify-end gap-2.5">
                    <button type="button" onclick="closeModal('modal-handover-book')" class="px-3.5 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="button" onclick="confirmHandoverAction()" class="px-4 py-2 rounded-xl bg-forest-800 hover:bg-forest-900 active:scale-[0.99] text-white font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
                        <span>Konfirmasi Serah Buku</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SIRKULASI: SCAN / INPUT KODE BOOKING CEPAT -->
    <div id="modal-lookup-booking" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full border border-gray-200 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-forest-950 via-forest-900 to-forest-800 px-5 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="scan-line" class="w-4 h-4 text-gold-400"></i>
                    <h3 class="font-display font-bold text-sm">Input / Scan Kode Booking</h3>
                </div>
                <button type="button" onclick="closeModal('modal-lookup-booking')" class="text-forest-200 hover:text-white p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form onsubmit="handleLookupBookingSubmit(event)" class="p-5 space-y-4 text-xs sm:text-sm">
                <div>
                    <label for="lookup-booking-input" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Kode Booking Mahasiswa
                    </label>
                    <div class="relative">
                        <input type="text" id="lookup-booking-input" placeholder="Ketik kode (contoh: AKT-TNTV)..." class="w-full pl-3.5 pr-10 py-2.5 uppercase font-mono font-bold tracking-wider text-sm bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-forest-600 focus:border-forest-600">
                        <span class="absolute right-3 top-3 text-gray-400">
                            <i data-lucide="ticket" class="w-4 h-4"></i>
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1">Masukkan kode booking yang ditunjukkan mahasiswa di HP atau scan QR Code.</p>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-lookup-booking')" class="px-3 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs hover:bg-gray-50">
                        Tutup
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest-800 hover:bg-forest-900 text-white font-bold text-xs flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari &amp; Serahkan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SIRKULASI: PRATINJAU WHATSAPP REMINDER -->
    <div id="modal-wa" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full border border-gray-200 shadow-2xl overflow-hidden">
            <div class="bg-forest-800 px-5 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="message-circle" class="w-5 h-5 text-gold-400"></i>
                    <h3 id="wa-modal-title" class="font-display font-bold text-sm">Kirim Pengingat WhatsApp</h3>
                </div>
                <button type="button" onclick="closeModal('modal-wa')" class="text-forest-200 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-5 space-y-3 text-xs sm:text-sm">
                <div class="flex items-center justify-between text-xs text-gray-600">
                    <span>Tujuan Mahasiswa:</span>
                    <span id="wa-target-phone" class="font-bold text-forest-800">081228595410</span>
                </div>
                <textarea id="wa-message-box" rows="6" class="w-full p-3 rounded-xl bg-forest-50/60 border border-forest-200 text-xs text-gray-800 leading-relaxed focus:outline-none focus:ring-2 focus:ring-forest-600"></textarea>
                <div class="flex items-center justify-end gap-2 pt-1">
                    <button type="button" onclick="closeModal('modal-wa')" class="px-3.5 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs hover:bg-gray-50">
                        Tutup
                    </button>
                    <button type="button" onclick="sendWhatsAppSimulated()" class="px-4 py-2 rounded-xl bg-forest-700 hover:bg-forest-800 text-white font-bold text-xs inline-flex items-center gap-1.5">
                        <i data-lucide="send" class="w-3.5 h-3.5 text-gold-300"></i>
                        <span>Kirim Pesan WA</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SIRKULASI: EDIT TRANSAKSI -->
    <div id="modal-edit" class="hidden fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-2xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full border border-gray-200 shadow-2xl overflow-hidden">
            <div class="bg-forest-900 px-5 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i data-lucide="square-pen" class="w-4 h-4 text-gold-400"></i>
                    <h3 class="font-display font-bold text-sm">Edit Data Sirkulasi</h3>
                </div>
                <button type="button" onclick="closeModal('modal-edit')" class="text-forest-200 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form onsubmit="submitEditRow(event)" class="p-5 space-y-3.5 text-xs sm:text-sm">
                <input type="hidden" id="edit-row-id">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kode Booking</label>
                    <input type="text" id="edit-kode" readonly class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg font-bold text-forest-800">
                </div>
                <div>
                    <label for="edit-status" class="block text-xs font-bold text-gray-700 mb-1">Status Transaksi</label>
                    <select id="edit-status" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-forest-600">
                        <option value="Booking">Booking</option>
                        <option value="Sedang Dipinjam">Sedang Dipinjam</option>
                        <option value="Terlambat">Terlambat</option>
                        <option value="Dikembalikan">Dikembalikan</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="edit-jatuh-tempo" class="block text-xs font-bold text-gray-700 mb-1">Jatuh Tempo</label>
                        <input type="text" id="edit-jatuh-tempo" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-800">
                    </div>
                    <div>
                        <label for="edit-denda" class="block text-xs font-bold text-gray-700 mb-1">Nominal Denda (Rp)</label>
                        <input type="number" id="edit-denda" min="0" step="100" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg font-bold text-gray-800">
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modal-edit')" class="px-3.5 py-2 rounded-xl border border-gray-300 text-gray-700 font-semibold text-xs">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-forest-800 hover:bg-forest-900 text-white font-bold text-xs">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // =========================================================================
        // 1. DATABASE MASTER (ANGGOTA, KOLEKSI BUKU, & SIRKULASI)
        // =========================================================================
        let anggotaDatabase = {!! !empty($anggotaDb) ? json_encode($anggotaDb) : '[]' !!};
        let bookCategories = {!! !empty($categories) ? json_encode($categories) : '["Fiksi", "Akuntansi", "Perpajakan", "TA/Skripsi", "Laporan PKL"]' !!};
        let booksData = {!! !empty($booksDb) ? json_encode($booksDb) : '[]' !!};
        const webCatalogBookings = {!! !empty($webCatalogBookings) ? json_encode($webCatalogBookings) : '[]' !!};
        let sirkulasiData = {!! !empty($sirkulasiDb) ? json_encode($sirkulasiDb) : '[]' !!};

        let selectedRowIds = new Set();
        let selectedBookIds = new Set();
        let selectedAnggotaIds = new Set();
        let dashboardChartInstance = null;

        // =========================================================================
        // 2. UTILITAS FORMAT & NOTIFIKASI
        // =========================================================================
        function formatRupiahDecimal(angka) {
            return "Rp " + Number(angka).toLocaleString("id-ID", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatRupiahCard(angka) {
            return "Rp " + Number(angka).toLocaleString("id-ID", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        function generateKodeBooking() {
            const chars = "ABCDEFGHJKLMNPQRSTUVWXYZ0123456789";
            let suffix = "";
            for (let i = 0; i < 4; i++) {
                suffix += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return `AKT-${suffix}`;
        }

        function showToast(title, message, tone = "green") {
            const container = document.getElementById("toast-container");
            const el = document.createElement("div");
            const styleClass = tone === "gold"
                ? "bg-amber-950 border-gold-400"
                : tone === "red"
                    ? "bg-rose-950 border-rose-400"
                    : "bg-forest-950 border-gold-400";

            el.className = `${styleClass} text-white border-l-4 px-4 py-3 rounded-xl shadow-xl max-w-sm pointer-events-auto transition-all duration-300 opacity-0 translate-y-2 flex items-start gap-3`;
            el.innerHTML = `
                <i data-lucide="check-circle-2" class="w-4 h-4 text-gold-400 shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-xs font-bold">${title}</p>
                    <p class="text-[11px] text-gray-200 mt-0.5">${message}</p>
                </div>
            `;
            container.appendChild(el);
            lucide.createIcons();
            requestAnimationFrame(() => el.classList.remove("opacity-0", "translate-y-2"));
            setTimeout(() => {
                el.classList.add("opacity-0", "translate-y-2");
                setTimeout(() => el.remove(), 300);
            }, 3400);
        }

        function closeModal(id) {
            document.getElementById(id).classList.add("hidden");
        }

        // =========================================================================
        // 3. NAVIGASI SPA ANTAR MENU SIDEBAR
        // =========================================================================
        function switchSidebarMenu(key, event) {
            if (event && event.type === 'click' && !event.ctrlKey && !event.metaKey && !event.shiftKey) {
                event.preventDefault();
                let targetPath = '/admin';
                if (key === 'buku') targetPath = '/admin/buku';
                else if (key === 'anggota') targetPath = '/admin/anggota';
                else if (key === 'sirkulasi') targetPath = '/admin/peminjaman';

                if (window.location.pathname !== targetPath) {
                    history.pushState({ tab: key }, '', targetPath);
                }
            }

            const allViews = [
                "view-dashboard",
                "view-anggota",
                "view-create-anggota",
                "view-buku",
                "view-create-book",
                "view-sirkulasi",
                "view-create-loan"
            ];
            allViews.forEach(vid => document.getElementById(vid)?.classList.add("hidden"));

            ["dashboard", "anggota", "buku", "sirkulasi"].forEach(id => {
                const btn = document.getElementById(`nav-${id}`);
                if (!btn) return;
                if (id === key) {
                    btn.className = "w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-semibold bg-forest-50 text-forest-800 ring-1 ring-forest-200/90 transition-colors";
                    const icon = btn.querySelector("i");
                    if (icon) icon.classList.replace("text-gray-400", "text-forest-700");
                } else {
                    btn.className = "w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-forest-50/70 hover:text-forest-800 transition-colors";
                    const icon = btn.querySelector("i");
                    if (icon) icon.classList.replace("text-forest-700", "text-gray-400");
                }
            });

            if (key === "dashboard") {
                document.getElementById("view-dashboard").classList.remove("hidden");
                refreshDashboardStats();
                renderDashboardTables();
                renderDashboardChart();
            } else if (key === "anggota") {
                document.getElementById("view-anggota").classList.remove("hidden");
                renderAnggotaTable();
            } else if (key === "buku") {
                document.getElementById("view-buku").classList.remove("hidden");
                populateBookCategoryOptions();
                renderBooksTable();
            } else if (key === "sirkulasi") {
                document.getElementById("view-sirkulasi").classList.remove("hidden");
                refreshUI();
            }

            document.getElementById("sidebar-drawer").classList.add("-translate-x-full");
            window.scrollTo({ top: 0, behavior: "smooth" });
            lucide.createIcons();
        }

        window.addEventListener("popstate", (e) => {
            const p = window.location.pathname.toLowerCase();
            let tab = "dashboard";
            if (p.includes("/buku")) tab = "buku";
            else if (p.includes("/anggota")) tab = "anggota";
            else if (p.includes("/peminjaman") || p.includes("/sirkulasi")) tab = "sirkulasi";
            switchSidebarMenu(tab);
        });

        // =========================================================================
        // 4. LOGIKA DASHBOARD (KPI, CHART.JS DONUT, & 2 TABEL RINGKAS)
        // =========================================================================
        function refreshDashboardStats() {
            const totalKategori = bookCategories.length;
            const totalJudul = booksData.length;
            const totalStok = booksData.reduce((sum, b) => sum + (Number(b.stok) || 0), 0);
            const activeLoans = sirkulasiData.filter(d => d.status === "Sedang Dipinjam" || d.status === "Terlambat");
            const lateLoans = activeLoans.filter(d => d.status === "Terlambat");

            document.getElementById("dash-stat-kategori").textContent = `${totalKategori} Kategori`;
            document.getElementById("dash-stat-judul").textContent = `${totalJudul} Judul`;
            document.getElementById("dash-stat-stok").textContent = `${totalStok} Buku`;
            document.getElementById("dash-stat-dipinjam").textContent = `${activeLoans.length} Eksemplar`;
            document.getElementById("sidebar-active-badge").textContent = activeLoans.length;

            const overdueEl = document.getElementById("dash-stat-overdue-note");
            if (lateLoans.length > 0) {
                overdueEl.className = "text-xs font-medium text-rose-600 mt-1.5 flex items-center gap-1.5";
                overdueEl.innerHTML = `<span>Peringatan: ${lateLoans.length} buku terlambat!</span><span>⚠️</span>`;
            } else {
                overdueEl.className = "text-xs font-medium text-emerald-700 mt-1.5 flex items-center gap-1.5";
                overdueEl.innerHTML = `<span>Semua pinjaman tepat waktu</span><span>✅</span>`;
            }
        }

        function renderDashboardChart() {
            const ctx = document.getElementById("categoryDonutChart");
            if (!ctx) return;

            const period = document.getElementById("dash-chart-period")?.value || "all";
            const counts = bookCategories.map(cat => {
                const count = booksData.filter(b => b.kategori === cat).length;
                return period === "semester" ? Math.max(1, count) : (count || 1);
            });

            // Palet warna harmonis hijau forest & emas gold
            const palette = [
                "#065f46", // forest-800
                "#f59e0b", // gold-500
                "#059669", // forest-600
                "#d97706", // gold-600
                "#047857", // forest-700
                "#10b981", // forest-500
                "#fbbf24"  // gold-400
            ];
            const bgColors = bookCategories.map((_, idx) => palette[idx % palette.length]);

            if (dashboardChartInstance) {
                dashboardChartInstance.destroy();
            }

            dashboardChartInstance = new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: bookCategories,
                    datasets: [{
                        data: counts,
                        backgroundColor: bgColors,
                        borderColor: "#ffffff",
                        borderWidth: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "66%",
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: "#022c22",
                            titleColor: "#fcd34d",
                            bodyColor: "#ffffff",
                            padding: 10,
                            cornerRadius: 8
                        }
                    }
                }
            });

            const legendContainer = document.getElementById("dash-chart-legend");
            legendContainer.innerHTML = bookCategories.map((cat, idx) => `
                <div class="inline-flex items-center gap-1.5 text-gray-600">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: ${bgColors[idx]}"></span>
                    <span>${cat} <b class="text-gray-900">(${counts[idx]})</b></span>
                </div>
            `).join("");
        }

        function renderDashboardTables() {
            const bookSearch = (document.getElementById("dash-book-search")?.value || "").toLowerCase().trim();
            const loanSearch = (document.getElementById("dash-loan-search")?.value || "").toLowerCase().trim();

            // Tabel 1: Koleksi Buku Terbaru
            const booksTbody = document.getElementById("dash-books-tbody");
            const filteredBooks = booksData.filter(b =>
                `${b.kode} ${b.judul} ${b.kategori} ${b.penulis}`.toLowerCase().includes(bookSearch)
            );

            booksTbody.innerHTML = filteredBooks.slice(0, 5).map(b => `
                <tr class="hover:bg-forest-50/40 transition-colors">
                    <td class="py-3.5 px-4 font-mono text-gray-500 whitespace-nowrap">${b.kode}</td>
                    <td class="py-3.5 px-4 font-semibold text-gray-900">${b.judul}</td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-forest-50 text-forest-800 border border-forest-200">
                            ${b.kategori}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-gray-700 whitespace-nowrap">${b.penulis}</td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-xs">
                            ${b.stok}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                        <button type="button" onclick="switchSidebarMenu('buku'); openBookFormModal(${b.id});" class="inline-flex items-center gap-1 text-xs font-semibold text-forest-700 hover:text-forest-900 hover:underline">
                            <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                            <span>Detail / Edit</span>
                        </button>
                    </td>
                </tr>
            `).join("");

            // Tabel 2: Sirkulasi Buku Sedang Dipinjam
            const loansTbody = document.getElementById("dash-loans-tbody");
            const activeLoans = sirkulasiData.filter(l =>
                (l.status === "Sedang Dipinjam" || l.status === "Terlambat") &&
                `${l.judulBuku} ${l.nama} ${l.nim}`.toLowerCase().includes(loanSearch)
            );

            if (activeLoans.length === 0) {
                loansTbody.innerHTML = `<tr><td colspan="5" class="py-8 text-center text-gray-400">Tidak ada peminjaman aktif yang cocok.</td></tr>`;
            } else {
                loansTbody.innerHTML = activeLoans.map(l => {
                    const isLate = l.status === "Terlambat";
                    const statusBadge = isLate
                        ? `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200">
                            <i data-lucide="alert-triangle" class="w-3 h-3"></i> Terlambat | Overdue
                           </span>`
                        : `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gold-100 text-amber-800 border border-gold-300">
                            <i data-lucide="book-open" class="w-3 h-3"></i> Sedang Dipinjam
                           </span>`;

                    return `
                        <tr class="hover:bg-forest-50/40 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-gray-900">${l.judulBuku}</td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <p class="font-semibold text-gray-900">${l.nama}</p>
                                <p class="text-[11px] text-gray-500">${l.nim} • ${l.kelas}</p>
                            </td>
                            <td class="py-3.5 px-4 text-gray-700 whitespace-nowrap">${l.tglPinjam}</td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <p class="font-semibold ${isLate ? "text-rose-600" : "text-gray-800"}">${l.jatuhTempo}</p>
                                <p class="text-[11px] ${isLate ? "text-rose-500" : "text-gray-500"}">${l.catatanTempo || "Masa pinjam aktif"}</p>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">${statusBadge}</td>
                        </tr>
                    `;
                }).join("");
            }

            lucide.createIcons();
        }

        // =========================================================================
        // 5. LOGIKA MENU ANGGOTA & TAMBAH ANGGOTA
        // =========================================================================
        function renderAnggotaTable() {
            const tbody = document.getElementById("anggota-table-body");
            if (!tbody) return;

            const search = (document.getElementById("anggota-search-input")?.value || "").toLowerCase().trim();
            const prodiFilter = document.getElementById("anggota-filter-prodi")?.value || "ALL";
            const perPage = Number(document.getElementById("anggota-per-page")?.value) || 10;

            const filtered = anggotaDatabase.filter(a => {
                if (prodiFilter !== "ALL" && a.prodi !== prodiFilter) return false;
                if (search) {
                    return `${a.nim} ${a.nama} ${a.prodi} ${a.kelas} ${a.email}`.toLowerCase().includes(search);
                }
                return true;
            });

            const sliced = filtered.slice(0, perPage);
            document.getElementById("anggota-pagination-summary").textContent =
                `Showing ${filtered.length === 0 ? 0 : 1} to ${sliced.length} of ${filtered.length} results`;

            if (sliced.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="py-10 text-center text-gray-400">Data anggota tidak ditemukan.</td></tr>`;
                return;
            }

            tbody.innerHTML = sliced.map(a => `
                <tr class="hover:bg-forest-50/40 transition-colors">
                    <td class="py-3.5 pl-4 pr-2">
                        <input type="checkbox" data-id="${a.id}" ${selectedAnggotaIds.has(a.id) ? "checked" : ""} onchange="toggleAnggotaRowCheckbox(${a.id}, this.checked)" class="anggota-row-cb rounded border-gray-300 text-forest-700 focus:ring-forest-600">
                    </td>
                    <td class="py-3.5 px-3 font-mono font-semibold text-forest-900 whitespace-nowrap">${a.nim}</td>
                    <td class="py-3.5 px-3 font-semibold text-gray-900 whitespace-nowrap">${a.nama}</td>
                    <td class="py-3.5 px-3 text-gray-700 whitespace-nowrap">${a.prodi}</td>
                    <td class="py-3.5 px-3 whitespace-nowrap">
                        <span class="px-2 py-0.5 rounded bg-forest-50 text-forest-800 border border-forest-200 text-xs font-bold">${a.kelas}</span>
                    </td>
                    <td class="py-3.5 px-3 text-gray-700 whitespace-nowrap">${a.angkatan}</td>
                    <td class="py-3.5 px-3 text-gray-700 whitespace-nowrap">${a.hp}</td>
                    <td class="py-3.5 px-3 text-gray-500 whitespace-nowrap">${a.email}</td>
                    <td class="py-3.5 pl-3 pr-4 text-right whitespace-nowrap">
                        <div class="inline-flex items-center justify-end gap-3">
                            <button type="button" onclick="openAnggotaEditModal(${a.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-forest-700 hover:text-forest-900">
                                <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                                <span>Ubah</span>
                            </button>
                            <button type="button" onclick="deleteAnggotaRow(${a.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-800">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join("");

            lucide.createIcons();
        }

        function toggleAnggotaRowCheckbox(id, checked) {
            if (checked) selectedAnggotaIds.add(id);
            else selectedAnggotaIds.delete(id);
            updateAnggotaBulkBadge();
        }

        function toggleAnggotaSelectAll(master) {
            document.querySelectorAll(".anggota-row-cb").forEach(cb => {
                cb.checked = master.checked;
                const id = Number(cb.dataset.id);
                if (master.checked) selectedAnggotaIds.add(id);
                else selectedAnggotaIds.delete(id);
            });
            updateAnggotaBulkBadge();
        }

        function updateAnggotaBulkBadge() {
            const badge = document.getElementById("anggota-bulk-info");
            if (selectedAnggotaIds.size > 0) {
                badge.textContent = `${selectedAnggotaIds.size} anggota dipilih`;
                badge.classList.remove("hidden");
            } else {
                badge.classList.add("hidden");
            }
        }

        function toggleAnggotaFilterPopover() {
            document.getElementById("anggota-filter-popover").classList.toggle("hidden");
        }

        function onAnggotaFilterChange() {
            const val = document.getElementById("anggota-filter-prodi").value;
            const badge = document.getElementById("anggota-filter-badge");
            if (val !== "ALL") badge.classList.remove("hidden");
            else badge.classList.add("hidden");
            renderAnggotaTable();
        }

        function resetAnggotaFilters() {
            document.getElementById("anggota-filter-prodi").value = "ALL";
            onAnggotaFilterChange();
        }

        function openCreateAnggotaView() {
            document.getElementById("view-anggota").classList.add("hidden");
            document.getElementById("view-create-anggota").classList.remove("hidden");
            resetCreateAnggotaForm();
            window.scrollTo({ top: 0, behavior: "smooth" });
            lucide.createIcons();
        }

        function closeCreateAnggotaView() {
            document.getElementById("view-create-anggota").classList.add("hidden");
            document.getElementById("view-anggota").classList.remove("hidden");
            renderAnggotaTable();
            window.scrollTo({ top: 0, behavior: "smooth" });
        }

        function resetCreateAnggotaForm() {
            document.getElementById("ca-nim").value = "";
            document.getElementById("ca-nama").value = "";
            document.getElementById("ca-prodi").value = "";
            document.getElementById("ca-kelas").value = "";
            document.getElementById("ca-angkatan").value = "2024";
            document.getElementById("ca-hp").value = "";
            document.getElementById("ca-email").value = "";
            document.getElementById("ca-alamat").value = "";
        }

        function handleCreateAnggotaSubmit(e, createAnother) {
            if (e) e.preventDefault();

            const payload = {
                id: Date.now(),
                nim: document.getElementById("ca-nim").value.trim(),
                nama: document.getElementById("ca-nama").value.trim(),
                prodi: document.getElementById("ca-prodi").value,
                kelas: document.getElementById("ca-kelas").value.trim().toUpperCase(),
                angkatan: Number(document.getElementById("ca-angkatan").value) || 2024,
                hp: document.getElementById("ca-hp").value.trim(),
                email: document.getElementById("ca-email").value.trim(),
                alamat: document.getElementById("ca-alamat").value.trim()
            };

            if (!payload.nim || !payload.nama || !payload.prodi || !payload.kelas || !payload.hp || !payload.email) {
                showToast("Form Belum Lengkap", "Lengkapi semua kolom wajib bertanda bintang (*).", "red");
                return;
            }

            anggotaDatabase.unshift(payload);
            showToast("Anggota Tersimpan", `${payload.nama} (${payload.nim}) berhasil ditambahkan.`);

            fetch("{{ route('admin.api.anggota.save') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(payload)
            }).then(r => r.json()).then(res => {
                if (res.id) payload.id = res.id;
            }).catch(console.error);

            if (createAnother) {
                resetCreateAnggotaForm();
                window.scrollTo({ top: 0, behavior: "smooth" });
            } else {
                closeCreateAnggotaView();
            }
        }

        function openAnggotaEditModal(id) {
            const a = anggotaDatabase.find(x => x.id === id);
            if (!a) return;
            document.getElementById("edit-anggota-id").value = a.id;
            document.getElementById("edit-anggota-nim").value = a.nim;
            document.getElementById("edit-anggota-nama").value = a.nama;
            document.getElementById("edit-anggota-prodi").value = a.prodi;
            document.getElementById("edit-anggota-kelas").value = a.kelas;
            document.getElementById("edit-anggota-angkatan").value = a.angkatan;
            document.getElementById("edit-anggota-hp").value = a.hp;
            document.getElementById("edit-anggota-email").value = a.email;
            document.getElementById("modal-anggota-edit").classList.remove("hidden");
        }

        function submitAnggotaEdit(e) {
            e.preventDefault();
            const id = Number(document.getElementById("edit-anggota-id").value);
            const idx = anggotaDatabase.findIndex(x => x.id === id);
            if (idx === -1) return;

            anggotaDatabase[idx] = {
                ...anggotaDatabase[idx],
                nim: document.getElementById("edit-anggota-nim").value.trim(),
                nama: document.getElementById("edit-anggota-nama").value.trim(),
                prodi: document.getElementById("edit-anggota-prodi").value,
                kelas: document.getElementById("edit-anggota-kelas").value.trim().toUpperCase(),
                angkatan: Number(document.getElementById("edit-anggota-angkatan").value) || 2024,
                hp: document.getElementById("edit-anggota-hp").value.trim(),
                email: document.getElementById("edit-anggota-email").value.trim()
            };

            closeModal("modal-anggota-edit");
            renderAnggotaTable();
            showToast("Anggota Diperbarui", `Perubahan data ${anggotaDatabase[idx].nama} telah disimpan.`);
        }

        function deleteAnggotaRow(id) {
            const a = anggotaDatabase.find(x => x.id === id);
            if (!a) return;
            anggotaDatabase = anggotaDatabase.filter(x => x.id !== id);
            selectedAnggotaIds.delete(id);
            updateAnggotaBulkBadge();
            renderAnggotaTable();
            showToast("Anggota Dihapus", `Data ${a.nama} (${a.nim}) telah dihapus.`, "red");

            fetch(`/admin/api/anggota/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            }).catch(console.error);
        }

        function downloadAnggotaTemplate() {
            window.location.href = "{{ route('admin.anggota.download-template') }}";
            showToast("Template Diunduh", "Template_Import_Anggota_Akuntansi.xlsx siap diunduh.");
        }

        function openAnggotaImportModal() {
            document.getElementById("modal-anggota-import").classList.remove("hidden");
        }

        function submitAnggotaImport() {
            const lines = document.getElementById("anggota-import-csv").value.trim().split("\n");
            let added = 0;
            lines.forEach(line => {
                const cols = line.split(",").map(s => s.trim());
                if (cols.length >= 4 && cols[0] && cols[1]) {
                    anggotaDatabase.unshift({
                        id: Date.now() + added,
                        nim: cols[0],
                        nama: cols[1],
                        prodi: cols[2] || "Komputerisasi Akuntansi (S. Tr)",
                        kelas: cols[3] || "KA-A",
                        angkatan: Number(cols[4]) || 2024,
                        hp: cols[5] || "081200000000",
                        email: cols[6] || "mahasiswa@student.polines.ac.id",
                        alamat: "Semarang"
                    });
                    added++;
                }
            });
            closeModal("modal-anggota-import");
            renderAnggotaTable();
            showToast("Impor Selesai", `${added} data anggota baru berhasil diimpor.`);
        }

        // =========================================================================
        // 6. LOGIKA MENU BUKU & CREATE BOOK
        // =========================================================================
        function renderBooksTable() {
            const tbody = document.getElementById("books-table-body");
            if (!tbody) return;

            const search = (document.getElementById("book-search-input")?.value || "").toLowerCase().trim();
            const catFilter = document.getElementById("book-filter-category")?.value || "ALL";
            const perPage = Number(document.getElementById("books-per-page")?.value) || 10;

            const filtered = booksData.filter(b => {
                if (catFilter !== "ALL" && b.kategori !== catFilter) return false;
                if (search) {
                    return `${b.kode} ${b.judul} ${b.kategori} ${b.penulis} ${b.tahun}`.toLowerCase().includes(search);
                }
                return true;
            });

            const sliced = filtered.slice(0, perPage);
            document.getElementById("books-pagination-summary").textContent =
                `Showing ${filtered.length === 0 ? 0 : 1} to ${sliced.length} of ${filtered.length} results`;

            if (sliced.length === 0) {
                tbody.innerHTML = `<tr><td colspan="10" class="py-10 text-center text-gray-400">Tidak ada koleksi buku yang cocok.</td></tr>`;
                return;
            }

            tbody.innerHTML = sliced.map(b => `
                <tr class="hover:bg-forest-50/40 transition-colors">
                    <td class="py-3 pl-4 pr-2">
                        <input type="checkbox" data-id="${b.id}" ${selectedBookIds.has(b.id) ? "checked" : ""} onchange="toggleBookRowSelect(${b.id}, this.checked)" class="book-row-cb rounded border-gray-300 text-forest-700 focus:ring-forest-600">
                    </td>
                    <td class="py-3 px-3 whitespace-nowrap">
                        ${b.cover ? `
                            <img src="${b.cover}" alt="${b.judul}" class="w-9 h-12 rounded-md object-cover border border-forest-700/60 shadow-2xs">
                        ` : `
                            <div class="w-9 h-12 rounded-md bg-gradient-to-br from-forest-800 to-forest-950 text-gold-300 flex items-center justify-center border border-forest-700 shadow-2xs">
                                <i data-lucide="book" class="w-4 h-4"></i>
                            </div>
                        `}
                    </td>
                    <td class="py-3 px-3 font-mono text-gray-600 whitespace-nowrap">${b.kode}</td>
                    <td class="py-3 px-3 font-semibold text-gray-900">${b.judul}</td>
                    <td class="py-3 px-3 whitespace-nowrap">
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-forest-50 text-forest-800 border border-forest-200">${b.kategori}</span>
                    </td>
                    <td class="py-3 px-3 text-gray-700 whitespace-nowrap">${b.penulis}</td>
                    <td class="py-3 px-3 text-gray-600 whitespace-nowrap">${b.tahun}</td>
                    <td class="py-3 px-3 whitespace-nowrap">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold ${b.stok > 0 ? "bg-emerald-50 text-emerald-700 border border-emerald-200" : "bg-rose-50 text-rose-600 border border-rose-200"}">
                            ${b.stok}
                        </span>
                    </td>
                    <td class="py-3 px-3 text-gray-700 font-semibold whitespace-nowrap">${b.dipinjam}</td>
                    <td class="py-3 px-3 whitespace-nowrap">
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold ${b.isTA ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-50 text-emerald-800 border border-emerald-200'}">
                            Rp ${b.isTA ? '300' : '200'} / hari
                        </span>
                    </td>
                    <td class="py-3 pl-3 pr-4 text-right whitespace-nowrap">
                        <div class="inline-flex items-center justify-end gap-2.5">
                            <button type="button" onclick="openBookView(${b.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-gray-600 hover:text-forest-800">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>View</span>
                            </button>
                            <button type="button" onclick="openBookFormModal(${b.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-forest-700 hover:text-forest-900">
                                <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                                <span>Edit</span>
                            </button>
                            <button type="button" onclick="deleteBookRow(${b.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-800">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join("");

            lucide.createIcons();
        }

        function toggleBookRowSelect(id, checked) {
            if (checked) selectedBookIds.add(id);
            else selectedBookIds.delete(id);
            const badge = document.getElementById("book-bulk-info");
            if (selectedBookIds.size > 0) {
                badge.textContent = `${selectedBookIds.size} buku dipilih`;
                badge.classList.remove("hidden");
            } else {
                badge.classList.add("hidden");
            }
        }

        function populateBookCategoryOptions() {
            const filterSel = document.getElementById("book-filter-category");
            if (filterSel) {
                const currentFilter = filterSel.value;
                filterSel.innerHTML = `<option value="ALL">Semua Kategori</option>` + bookCategories.map(c =>
                    `<option value="${c}">${c}</option>`
                ).join("");
                filterSel.value = bookCategories.includes(currentFilter) || currentFilter === "ALL" ? currentFilter : "ALL";
            }

            const formSel = document.getElementById("book-kategori-input");
            if (formSel) {
                formSel.innerHTML = bookCategories.map(c => `<option value="${c}">${c}</option>`).join("");
            }

            const createCatSel = document.getElementById("create-book-category");
            if (createCatSel) {
                const prevCreateCat = createCatSel.value;
                createCatSel.innerHTML = `<option value="">Select an option</option>` + bookCategories.map(c =>
                    `<option value="${c}">${c}</option>`
                ).join("");
                if (bookCategories.includes(prevCreateCat)) {
                    createCatSel.value = prevCreateCat;
                }
            }
        }

        let selectedCreateCoverName = "";

        function openCreateBookView() {
            populateBookCategoryOptions();
            resetCreateBookForm();
            document.getElementById("view-dashboard").classList.add("hidden");
            document.getElementById("view-sirkulasi").classList.add("hidden");
            document.getElementById("view-create-loan").classList.add("hidden");
            document.getElementById("view-buku").classList.add("hidden");
            document.getElementById("view-create-book").classList.remove("hidden");
            window.scrollTo({ top: 0, behavior: "smooth" });
            lucide.createIcons();
        }

        function closeCreateBookView() {
            document.getElementById("view-create-book").classList.add("hidden");
            document.getElementById("view-buku").classList.remove("hidden");
            populateBookCategoryOptions();
            renderBooksTable();
            window.scrollTo({ top: 0, behavior: "smooth" });
        }

        function resetCreateBookForm() {
            document.getElementById("create-book-category").value = "";
            document.getElementById("create-book-title").value = "";
            document.getElementById("create-book-author").value = "";
            document.getElementById("create-book-code").value = "";
            document.getElementById("create-book-publisher").value = "";
            document.getElementById("create-book-year").value = "";
            document.getElementById("create-book-isbn").value = "";
            document.getElementById("create-book-stock").value = "1";
            document.getElementById("create-book-synopsis").value = "";
            document.getElementById("create-book-cover-file").value = "";
            selectedCreateCoverName = "";
            document.getElementById("cover-dropzone-empty").classList.remove("hidden");
            document.getElementById("cover-dropzone-selected").classList.add("hidden");
            document.getElementById("cover-dropzone-selected").classList.remove("flex");
        }

        function handleBookCoverFile(file) {
            if (!file) return;
            selectedCreateCoverName = file.name;
            document.getElementById("cover-file-name-label").textContent = file.name;
            document.getElementById("cover-dropzone-empty").classList.add("hidden");
            document.getElementById("cover-dropzone-selected").classList.remove("hidden");
            document.getElementById("cover-dropzone-selected").classList.add("flex");
            lucide.createIcons();
        }

        function lookupIsbnData() {
            const isbnVal = document.getElementById("create-book-isbn").value.trim();
            if (!isbnVal) {
                showToast("Masukkan ISBN", "Ketik nomor ISBN terlebih dahulu lalu klik ikon cari.", "gold");
                return;
            }
            if (!document.getElementById("create-book-publisher").value.trim()) {
                document.getElementById("create-book-publisher").value = "Salemba Empat";
            }
            if (!document.getElementById("create-book-year").value.trim()) {
                document.getElementById("create-book-year").value = "2024";
            }
            showToast("Pencarian ISBN", `Metadata untuk ISBN ${isbnVal} dicek dan siap dilengkapi.`, "gold");
        }

        function handleCreateBookSubmit(e, createAnother) {
            if (e) e.preventDefault();

            const kategori = document.getElementById("create-book-category").value;
            const judul = document.getElementById("create-book-title").value.trim();
            const penulis = document.getElementById("create-book-author").value.trim();
            const penerbit = document.getElementById("create-book-publisher").value.trim();
            const tahun = Number(document.getElementById("create-book-year").value);
            const stok = Number(document.getElementById("create-book-stock").value);
            const isbn = document.getElementById("create-book-isbn").value.trim();
            const sinopsis = document.getElementById("create-book-synopsis").value.trim();

            if (!kategori || !judul || !penulis || !penerbit || !tahun || Number.isNaN(stok)) {
                showToast("Field Wajib Belum Lengkap", "Lengkapi semua kolom bertanda bintang (*).", "red");
                return;
            }

            const kode = document.getElementById("create-book-code").value.trim() || `AKT-${booksData.length + 101}`;

            booksData.unshift({
                id: Date.now(),
                cover: "",
                coverFileName: selectedCreateCoverName,
                kode: kode,
                judul: judul,
                kategori: kategori,
                penulis: penulis,
                penerbit: penerbit,
                tahun: tahun,
                isbn: isbn,
                sinopsis: sinopsis,
                stok: stok,
                dipinjam: 0,
                isTA: isTACategory(kategori)
            });

            refreshDashboardStats();
            renderDashboardChart();
            showToast("Buku Berhasil Dibuat", `"${judul}" (${kategori}) telah ditambahkan ke daftar buku.`);

            const formData = new FormData();
            formData.append("judul", judul);
            formData.append("kategori", kategori);
            formData.append("penulis", penulis);
            formData.append("penerbit", penerbit);
            formData.append("tahun", tahun);
            formData.append("isbn", isbn);
            formData.append("sinopsis", sinopsis);
            formData.append("stok", stok);
            formData.append("kode", kode);

            const fileInput = document.getElementById("create-book-cover-file");
            if (fileInput && fileInput.files && fileInput.files[0]) {
                formData.append("cover_file", fileInput.files[0]);
            }

            fetch("{{ route('admin.api.books.save') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            }).then(r => r.json()).then(res => {
                if (res.id) booksData[0].id = res.id;
                if (res.cover_url) {
                    booksData[0].cover = res.cover_url;
                    renderBooksTable();
                }
            }).catch(console.error);

            if (createAnother) {
                resetCreateBookForm();
                window.scrollTo({ top: 0, behavior: "smooth" });
                lucide.createIcons();
            } else {
                closeCreateBookView();
            }
        }

        function toggleBookSelectAll(master) {
            document.querySelectorAll(".book-row-cb").forEach(cb => {
                cb.checked = master.checked;
                const id = Number(cb.dataset.id);
                toggleBookRowSelect(id, master.checked);
            });
        }

        function toggleBookFilterPopover() {
            document.getElementById("book-filter-popover").classList.toggle("hidden");
        }

        function onBookFilterChange() {
            const val = document.getElementById("book-filter-category").value;
            const badge = document.getElementById("book-filter-badge");
            if (val !== "ALL") {
                badge.textContent = "1";
                badge.classList.remove("hidden");
            } else {
                badge.classList.add("hidden");
            }
            renderBooksTable();
        }

        function resetBookFilters() {
            document.getElementById("book-filter-category").value = "ALL";
            onBookFilterChange();
        }

        function previewEditBookCover(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById("book-edit-cover-img");
                    const placeholder = document.getElementById("book-edit-cover-placeholder");
                    img.src = e.target.result;
                    img.classList.remove("hidden");
                    placeholder.classList.add("hidden");
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function isTACategory(val) {
            const s = (val || '').toLowerCase().trim();
            return s.includes('skripsi') || s.includes('tugas akhir') || s.startsWith('ta/') || s.startsWith('ta-') || s.startsWith('ta ') || s === 'ta' || s === 'ta/skripsi';
        }

        function syncCategoryDenda(val, pillId) {
            const isTA = isTACategory(val);
            const el = document.getElementById(pillId);
            if (el) {
                if (isTA) {
                    el.textContent = 'Rp 300 / hari (14 hari kerja)';
                    el.className = 'px-2.5 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300';
                } else {
                    el.textContent = 'Rp 200 / hari (7 hari kerja)';
                    el.className = 'px-2.5 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200';
                }
            }
        }

        function syncModalBookDenda(val) {
            const isTA = isTACategory(val);
            const input = document.getElementById('book-denda-display');
            if (input) {
                input.value = isTA ? 'Rp 300 / hari (TA/Skripsi)' : 'Rp 200 / hari (Buku Umum)';
            }
        }

        function openBookFormModal(bookId = null) {
            populateBookCategoryOptions();
            if (bookId) {
                const b = booksData.find(x => x.id === bookId);
                if (!b) return;
                document.getElementById("book-modal-title").textContent = "Edit Book";
                document.getElementById("book-edit-id").value = b.id;
                document.getElementById("book-kode-input").value = b.kode === "-" ? "" : b.kode;
                document.getElementById("book-judul-input").value = b.judul;
                document.getElementById("book-kategori-input").value = b.kategori;
                syncModalBookDenda(b.kategori);
                document.getElementById("book-penulis-input").value = b.penulis;
                document.getElementById("book-tahun-input").value = b.tahun;
                document.getElementById("book-isbn-input").value = (b.isbn && b.isbn !== "-") ? b.isbn : "";
                document.getElementById("book-penerbit-input").value = (b.penerbit && b.penerbit !== "-") ? b.penerbit : "";
                document.getElementById("book-stok-input").value = b.stok;
                document.getElementById("book-dipinjam-input").value = b.dipinjam;
                document.getElementById("book-sinopsis-input").value = b.sinopsis || "";

                // Cover Preview
                const img = document.getElementById("book-edit-cover-img");
                const placeholder = document.getElementById("book-edit-cover-placeholder");
                document.getElementById("book-cover-input").value = "";
                if (b.cover) {
                    img.src = b.cover;
                    img.classList.remove("hidden");
                    placeholder.classList.add("hidden");
                } else {
                    img.classList.add("hidden");
                    placeholder.classList.remove("hidden");
                }
            }
            document.getElementById("modal-book-form").classList.remove("hidden");
            lucide.createIcons();
        }

        function submitBookForm(e) {
            e.preventDefault();
            const editId = Number(document.getElementById("book-edit-id").value);
            const payload = {
                kode: document.getElementById("book-kode-input").value.trim() || "-",
                judul: document.getElementById("book-judul-input").value.trim(),
                kategori: document.getElementById("book-kategori-input").value,
                penulis: document.getElementById("book-penulis-input").value.trim(),
                tahun: Number(document.getElementById("book-tahun-input").value) || 2024,
                isbn: document.getElementById("book-isbn-input").value.trim(),
                penerbit: document.getElementById("book-penerbit-input").value.trim() || "Pustaka Akuntansi",
                stok: Number(document.getElementById("book-stok-input").value) || 0,
                dipinjam: Number(document.getElementById("book-dipinjam-input").value) || 0,
                sinopsis: document.getElementById("book-sinopsis-input").value.trim()
            };

            if (editId) {
                const idx = booksData.findIndex(x => x.id === editId);
                if (idx > -1) booksData[idx] = { ...booksData[idx], ...payload, isTA: isTACategory(payload.kategori) };
                showToast("Buku Diperbarui", `Data "${payload.judul}" tersimpan.`);

                const formData = new FormData();
                formData.append("id", editId);
                formData.append("kode", payload.kode);
                formData.append("judul", payload.judul);
                formData.append("kategori", payload.kategori);
                formData.append("penulis", payload.penulis);
                formData.append("tahun", payload.tahun);
                formData.append("isbn", payload.isbn);
                formData.append("penerbit", payload.penerbit);
                formData.append("stok", payload.stok);
                formData.append("dipinjam", payload.dipinjam);
                formData.append("sinopsis", payload.sinopsis);
                const coverInput = document.getElementById("book-cover-input");
                if (coverInput && coverInput.files && coverInput.files[0]) {
                    formData.append("cover_file", coverInput.files[0]);
                }

                fetch("{{ route('admin.api.books.save') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: formData
                }).then(r => r.json()).then(res => {
                    if (res.cover_url) {
                        const targetIdx = booksData.findIndex(x => x.id === editId);
                        if (targetIdx > -1) {
                            booksData[targetIdx].cover = res.cover_url;
                            renderBooksTable();
                        }
                    }
                }).catch(console.error);
            }

            closeModal("modal-book-form");
            renderBooksTable();
            refreshDashboardStats();
            renderDashboardTables();
        }

        function openBookView(bookId) {
            const b = booksData.find(x => x.id === bookId);
            if (!b) return;
            document.getElementById("book-view-body").innerHTML = `
                <div class="flex gap-4 pb-3 border-b border-gray-100 items-start">
                    <div class="w-20 h-28 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 shrink-0 shadow-sm flex items-center justify-center">
                        ${b.cover ? `
                            <img src="${b.cover}" alt="${b.judul}" class="w-full h-full object-cover">
                        ` : `
                            <div class="w-full h-full bg-gradient-to-br from-forest-800 to-forest-950 text-gold-300 flex flex-col items-center justify-center p-2 text-center">
                                <i data-lucide="book" class="w-6 h-6 mb-1"></i>
                                <span class="text-[9px] font-bold text-forest-100">Tanpa Cover</span>
                            </div>
                        `}
                    </div>
                    <div class="flex-1 min-w-0 space-y-1">
                        <span class="font-mono text-[11px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-700">${b.kode}</span>
                        <h4 class="font-display font-extrabold text-sm sm:text-base text-gray-900 leading-snug">${b.judul}</h4>
                        <p class="text-xs text-gray-500 font-medium">${b.penulis}</p>
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-forest-50 text-forest-800 border border-forest-200">${b.kategori}</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs py-1">
                    <p><span class="text-gray-500">ISBN:</span> <b>${b.isbn && b.isbn !== '-' ? b.isbn : '(Non-ISBN)'}</b></p>
                    <p><span class="text-gray-500">Penerbit:</span> <b>${b.penerbit || 'Pustaka Akuntansi'}</b></p>
                    <p><span class="text-gray-500">Tahun:</span> <b>${b.tahun}</b></p>
                    <p><span class="text-gray-500">Stok:</span> <b class="text-emerald-700">Tersedia ${b.stok}</b> • Dipinjam: <b class="text-amber-700">${b.dipinjam}</b></p>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <p class="text-[11px] font-bold text-gray-700 mb-1">Deskripsi Buku:</p>
                    <p class="text-xs text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-200/80 leading-relaxed max-h-36 overflow-y-auto whitespace-pre-line">${b.sinopsis || 'Tidak ada deskripsi untuk buku ini.'}</p>
                </div>
            `;
            document.getElementById("modal-book-view").classList.remove("hidden");
            lucide.createIcons();
        }

        function deleteBookRow(bookId) {
            const b = booksData.find(x => x.id === bookId);
            if (!b) return;
            booksData = booksData.filter(x => x.id !== bookId);
            selectedBookIds.delete(bookId);
            renderBooksTable();
            refreshDashboardStats();
            renderDashboardChart();
            showToast("Buku Dihapus", `"${b.judul}" dihapus dari daftar.`, "red");

            fetch(`/admin/api/books/${bookId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            }).catch(console.error);
        }

        function openNewCategoryModal() {
            document.getElementById("new-category-name").value = "";
            document.getElementById("modal-new-category").classList.remove("hidden");
        }

        function submitNewCategory(e) {
            e.preventDefault();
            const name = document.getElementById("new-category-name").value.trim();
            if (name && !bookCategories.includes(name)) {
                bookCategories.push(name);
                populateBookCategoryOptions();
                const createCatSel = document.getElementById("create-book-category");
                if (createCatSel && !document.getElementById("view-create-book").classList.contains("hidden")) {
                    createCatSel.value = name;
                }
                refreshDashboardStats();
                renderDashboardChart();
                showToast("Kategori Ditambahkan", `Kategori "${name}" tersedia.`, "gold");
            }
            closeModal("modal-new-category");
        }

        function downloadBookTemplate() {
            showToast("Template Diunduh", "Template_Import_Buku_Akuntansi.csv siap dipakai.");
        }

        function openBookImportModal() {
            document.getElementById("modal-book-import").classList.remove("hidden");
        }

        function submitBookImport() {
            const lines = document.getElementById("book-import-csv").value.trim().split("\n");
            let added = 0;
            lines.forEach(line => {
                const cols = line.split(",").map(s => s.trim());
                if (cols.length >= 4 && cols[1]) {
                    const cat = cols[2] || "Akuntansi";
                    if (!bookCategories.includes(cat)) bookCategories.push(cat);
                    booksData.push({
                        id: Date.now() + added,
                        cover: "",
                        kode: cols[0] || "-",
                        judul: cols[1],
                        kategori: cat,
                        penulis: cols[3] || "-",
                        tahun: Number(cols[4]) || 2024,
                        stok: Number(cols[5]) || 1,
                        dipinjam: Number(cols[6]) || 0,
                        isTA: isTACategory(cat)
                    });
                    added++;
                }
            });
            closeModal("modal-book-import");
            populateBookCategoryOptions();
            renderBooksTable();
            refreshDashboardStats();
            renderDashboardChart();
            showToast("Import Selesai", `${added} data buku ditambahkan.`);
        }

        // =========================================================================
        // 7. LOGIKA SIRKULASI & CREATE PEMINJAMAN LENGKAP
        // =========================================================================
        function openCreateLoanView() {
            document.getElementById("view-dashboard").classList.add("hidden");
            document.getElementById("view-sirkulasi").classList.add("hidden");
            document.getElementById("view-create-loan").classList.remove("hidden");
            populateCreateLoanOptions();
            resetCreateLoanForm();
            window.scrollTo({ top: 0, behavior: "smooth" });
            lucide.createIcons();
        }

        function closeCreateLoanView() {
            document.getElementById("view-create-loan").classList.add("hidden");
            document.getElementById("view-sirkulasi").classList.remove("hidden");
            refreshUI();
            window.scrollTo({ top: 0, behavior: "smooth" });
        }

        function populateCreateLoanOptions() {
            // Dropdown Anggota & NIM
            const nameSel = document.getElementById("create-member-name");
            const nimSel = document.getElementById("create-member-nim");
            nameSel.innerHTML = `<option value="">Select an option</option>` + anggotaDatabase.map((m, idx) =>
                `<option value="${idx}">${m.nama} (${m.kelas})</option>`
            ).join("");
            nimSel.innerHTML = `<option value="">Select an option</option>` + anggotaDatabase.map((m, idx) =>
                `<option value="${idx}">${m.nim} — ${m.nama}</option>`
            ).join("");

            // Dropdown Koleksi Buku
            const bookSel = document.getElementById("create-book-select");
            bookSel.innerHTML = `<option value="">Select an option</option>` + booksData.map((b, idx) =>
                `<option value="${idx}">${b.judul} (${b.kategori}) • Stok: ${b.stok}</option>`
            ).join("");

            // Datalist & Chip Booking Aktif
            const datalist = document.getElementById("web-catalog-booking-list");
            const chipsBox = document.getElementById("quick-booking-chips");
            datalist.innerHTML = webCatalogBookings.map(wb => `<option value="${wb.kode}">`).join("");
            chipsBox.innerHTML = webCatalogBookings.map(wb => `
                <button type="button" onclick="applyWebBookingCode('${wb.kode}')" class="px-2 py-0.5 rounded-md bg-gold-100 hover:bg-gold-200 text-amber-950 border border-gold-300 text-[11px] font-bold transition-colors">
                    ${wb.kode}
                </button>
            `).join("");
        }

        function resetCreateLoanForm() {
            document.getElementById("create-booking-code-input").value = "";
            document.getElementById("booking-detected-banner").classList.add("hidden");
            document.getElementById("create-member-name").value = "";
            document.getElementById("create-member-nim").value = "";
            document.getElementById("create-jaminan-type").value = "KTM";
            document.getElementById("create-member-placeholder").classList.remove("hidden");
            document.getElementById("create-member-detail-card").classList.add("hidden");
            document.getElementById("create-book-select").value = "";
            document.getElementById("create-loan-date").value = "2026-11-22";
            document.getElementById("create-due-placeholder").classList.remove("hidden");
            document.getElementById("create-due-result-box").classList.add("hidden");
            document.getElementById("create-extend-toggle").checked = false;
            selectCreateStatus("Sedang Dipinjam");
        }

        function applyWebBookingCode(code) {
            document.getElementById("create-booking-code-input").value = code;
            handleBookingCodeInput(code);
        }

        function handleBookingCodeInput(val) {
            const clean = val.trim().toUpperCase();
            const found = webCatalogBookings.find(b => b.kode === clean);
            const banner = document.getElementById("booking-detected-banner");
            if (found) {
                banner.classList.remove("hidden");
                const m = anggotaDatabase[found.memberIdx];
                const b = booksData[found.bookIdx];
                document.getElementById("booking-detected-text").textContent =
                    `Kode ${found.kode} ditemukan (${found.waktuPesan}): ${m?.nama || "-"} memesan "${b?.judul || "-"}".`;
                syncMemberByIndex(String(found.memberIdx));
                document.getElementById("create-jaminan-type").value = found.jaminan;
                document.getElementById("create-book-select").value = String(found.bookIdx);
                updateWorkdayDueDatePreview();
            } else {
                banner.classList.add("hidden");
            }
        }

        function clearBookingCodeSelection() {
            document.getElementById("create-booking-code-input").value = "";
            document.getElementById("booking-detected-banner").classList.add("hidden");
        }

        function syncMemberByIndex(idxStr) {
            document.getElementById("create-member-name").value = idxStr;
            document.getElementById("create-member-nim").value = idxStr;

            const placeholder = document.getElementById("create-member-placeholder");
            const card = document.getElementById("create-member-detail-card");

            if (idxStr === "") {
                placeholder.classList.remove("hidden");
                card.classList.add("hidden");
                return;
            }

            const member = anggotaDatabase[Number(idxStr)];
            if (!member) return;

            const activeCount = sirkulasiData.filter(s =>
                s.nim === member.nim && (s.status === "Sedang Dipinjam" || s.status === "Terlambat")
            ).length;

            placeholder.classList.add("hidden");
            card.classList.remove("hidden");
            document.getElementById("detail-member-nama").textContent = `${member.nama} (${member.kelas})`;
            document.getElementById("detail-member-nim-hp").textContent = `${member.nim} • ${member.hp}`;
            document.getElementById("detail-member-quota").textContent = `${activeCount} / 2 Buku Aktif`;
            lucide.createIcons();
        }

        function addBusinessDays(dateStr, businessDays) {
            const parts = dateStr.split("-");
            const date = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
            let added = 0;
            while (added < businessDays) {
                date.setDate(date.getDate() + 1);
                const day = date.getDay();
                if (day !== 0 && day !== 6) {
                    added++;
                }
            }
            return date;
        }

        function formatIndoShortDate(dateObj) {
            const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            const d = String(dateObj.getDate()).padStart(2, "0");
            const m = months[dateObj.getMonth()];
            const y = dateObj.getFullYear();
            return `${d} ${m} ${y}`;
        }

        function updateWorkdayDueDatePreview() {
            const bookIdxStr = document.getElementById("create-book-select").value;
            const loanDateStr = document.getElementById("create-loan-date").value;
            const isExtended = document.getElementById("create-extend-toggle").checked;

            const placeholder = document.getElementById("create-due-placeholder");
            const box = document.getElementById("create-due-result-box");

            if (bookIdxStr === "" || !loanDateStr) {
                placeholder.classList.remove("hidden");
                box.classList.add("hidden");
                return;
            }

            const book = booksData[Number(bookIdxStr)];
            const baseDays = book.isTA ? 14 : 7;
            const totalDays = baseDays + (isExtended ? 3 : 0);
            const dueDate = addBusinessDays(loanDateStr, totalDays);

            placeholder.classList.add("hidden");
            box.classList.remove("hidden");
            document.getElementById("due-date-formatted-text").textContent = formatIndoShortDate(dueDate);
            document.getElementById("due-date-rule-note").textContent = book.isTA
                ? `Koleksi TA/Skripsi (14 hari kerja${isExtended ? " + 3 hari perpanjangan" : ""})`
                : `Koleksi Umum / Non-TA (7 hari kerja${isExtended ? " + 3 hari perpanjangan" : ""})`;
            document.getElementById("due-workdays-badge").textContent = `${totalDays} Hari Kerja`;
            lucide.createIcons();
        }

        function selectCreateStatus(statusVal) {
            document.getElementById("create-status-value").value = statusVal;
            const btnDipinjam = document.getElementById("status-pill-dipinjam");
            const btnBooking = document.getElementById("status-pill-booking");

            if (statusVal === "Sedang Dipinjam") {
                btnDipinjam.className = "inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-gold-500 text-forest-950 font-bold text-xs shadow-2xs ring-2 ring-gold-400/60 transition-all";
                btnBooking.className = "inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-semibold text-xs border border-gray-300 transition-all";
            } else {
                btnBooking.className = "inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-forest-800 text-white font-bold text-xs shadow-2xs ring-2 ring-gold-400/60 transition-all";
                btnDipinjam.className = "inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-semibold text-xs border border-gray-300 transition-all";
            }
        }

        function handleCreateLoanSubmit(e, createAnother) {
            if (e) e.preventDefault();

            const memberIdxStr = document.getElementById("create-member-name").value;
            const bookIdxStr = document.getElementById("create-book-select").value;
            const loanDateStr = document.getElementById("create-loan-date").value;

            if (memberIdxStr === "" || bookIdxStr === "" || !loanDateStr) {
                showToast("Form Belum Lengkap", "Pilih anggota, buku, dan tanggal pinjam terlebih dahulu.", "red");
                return;
            }

            const student = anggotaDatabase[Number(memberIdxStr)];
            const book = booksData[Number(bookIdxStr)];
            const isExtended = document.getElementById("create-extend-toggle").checked;
            const baseDays = book.isTA ? 14 : 7;
            const totalDays = baseDays + (isExtended ? 7 : 0);
            const dueDateObj = addBusinessDays(loanDateStr, totalDays);

            const parts = loanDateStr.split("-");
            const loanDateObj = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
            const kodeBooking = (document.getElementById("create-booking-code-input").value.trim().toUpperCase()) || generateKodeBooking();
            const statusVal = document.getElementById("create-status-value").value || "Sedang Dipinjam";
            const jaminanVal = document.getElementById("create-jaminan-type").value || "KTM";

            if (statusVal === "Sedang Dipinjam" && book.stok > 0) {
                book.stok -= 1;
                book.dipinjam += 1;
            }

            sirkulasiData.unshift({
                id: Date.now(),
                kodeBooking: kodeBooking,
                nama: student.nama,
                nim: student.nim,
                kelas: student.kelas,
                hp: student.hp,
                jaminan: jaminanVal,
                judulBuku: book.judul,
                kategoriBuku: book.kategori,
                tglPinjam: formatIndoShortDate(loanDateObj),
                jatuhTempo: formatIndoShortDate(dueDateObj),
                catatanTempo: statusVal === "Booking" ? "Menunggu pengambilan" : (isExtended ? "Diperpanjang +3 hari kerja" : ""),
                hariTelat: 0,
                status: statusVal,
                waType: "Ingatkan WA",
                denda: 0
            });

            refreshUI();
            refreshDashboardStats();
            showToast(
                "Peminjaman Disimpan",
                `Kode ${kodeBooking} untuk ${student.nama} (${book.judul}) berhasil dicatat.`
            );

            fetch("{{ route('admin.api.loans.save') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    member_nim: student.nim,
                    book_id: book.id,
                    kode_booking: kodeBooking,
                    status: statusVal === "Sedang Dipinjam" ? "dipinjam" : "booked",
                    jaminan: jaminanVal,
                    tanggal_pinjam: loanDateStr
                })
            }).then(r => r.json()).then(res => {
                if (res.id) sirkulasiData[0].id = res.id;
            }).catch(console.error);

            if (createAnother) {
                resetCreateLoanForm();
                window.scrollTo({ top: 0, behavior: "smooth" });
                lucide.createIcons();
            } else {
                closeCreateLoanView();
            }
        }

        // Modal Pengembalian ('Kembalikan')
        function openReturnModal(rowId) {
            const item = sirkulasiData.find(r => r.id === rowId);
            if (!item) return;

            document.getElementById("return-target-id").value = item.id;
            document.getElementById("ret-modal-kode").textContent = item.kodeBooking;
            document.getElementById("ret-modal-nama").textContent = item.nama;
            document.getElementById("ret-modal-meta").textContent = `NIM: ${item.nim} • Kelas: ${item.kelas} • Jaminan: ${item.jaminan}`;
            document.getElementById("ret-modal-buku").textContent = `${item.judulBuku} (${item.kategoriBuku})`;

            const badge = document.getElementById("ret-modal-status-badge");
            if (item.status === "Terlambat") {
                badge.className = "px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 font-bold text-[11px]";
                badge.textContent = `Terlambat (${item.hariTelat} hari)`;
            } else {
                badge.className = "px-2.5 py-0.5 rounded-full bg-forest-100 text-forest-800 font-bold text-[11px]";
                badge.textContent = item.status;
            }

            document.getElementById("ret-kondisi-select").value = "baik";
            recalculateReturnFine();
            document.getElementById("modal-return-book").classList.remove("hidden");
        }

        function recalculateReturnFine() {
            const rowId = Number(document.getElementById("return-target-id").value);
            const item = sirkulasiData.find(r => r.id === rowId);
            if (!item) return;

            const kondisi = document.getElementById("ret-kondisi-select").value;
            const dendaPerHari = Number(item.dendaPerHari) || (isTACategory(item.kategoriBuku) ? 300 : 200);
            const lateFine = item.status === "Terlambat" ? (item.hariTelat || 1) * dendaPerHari : 0;
            let extraFine = 0;

            if (kondisi === "rusak") extraFine = 5000;
            else if (kondisi === "hilang") extraFine = isTACategory(item.kategoriBuku) ? 200000 : 50000;

            const total = lateFine + extraFine;
            document.getElementById("ret-modal-fine-input").value = total;
            document.getElementById("ret-modal-fine-note").textContent = lateFine > 0
                ? `Telat ${item.hariTelat} hari × Rp ${dendaPerHari.toLocaleString("id-ID")} = Rp ${lateFine.toLocaleString("id-ID")}`
                : "Tepat waktu (Denda keterlambatan Rp 0)";
        }

        function confirmReturnAction() {
            const rowId = Number(document.getElementById("return-target-id").value);
            const item = sirkulasiData.find(r => r.id === rowId);
            if (!item) return;

            const kondisi = document.getElementById("ret-kondisi-select").value;
            const finalFine = Number(document.getElementById("ret-modal-fine-input").value) || 0;

            item.status = "Dikembalikan";
            item.denda = finalFine;
            item.catatanTempo = kondisi === "hilang" ? "Buku hilang (diganti denda)" : "";

            // Kembalikan stok buku jika tidak hilang
            if (kondisi !== "hilang") {
                const book = booksData.find(b => b.judul.toLowerCase() === item.judulBuku.toLowerCase());
                if (book) {
                    book.stok += 1;
                    book.dipinjam = Math.max(0, book.dipinjam - 1);
                }
            }

            closeModal("modal-return-book");
            refreshUI();
            refreshDashboardStats();
            showToast("Pengembalian Selesai", `Buku "${item.judulBuku}" telah dikembalikan (${formatRupiahCard(finalFine)}).`);

            fetch(`/admin/api/loans/${rowId}/return`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    kondisi: kondisi,
                    denda: finalFine
                })
            }).catch(console.error);
        }

        // =========================================================================
        // HANDOVER & BOOKING CONFIRMATION WORKFLOW
        // =========================================================================
        function filterByBookingStatus() {
            document.getElementById("filter-status").value = "Booking";
            onFilterChange();
            const tableEl = document.getElementById("sirkulasi-table-body");
            if (tableEl) tableEl.scrollIntoView({ behavior: "smooth", block: "center" });
        }

        function openLookupBookingModal() {
            document.getElementById("lookup-booking-input").value = "";
            document.getElementById("modal-lookup-booking").classList.remove("hidden");
            setTimeout(() => document.getElementById("lookup-booking-input").focus(), 100);
            lucide.createIcons();
        }

        function handleLookupBookingSubmit(e) {
            e.preventDefault();
            const code = document.getElementById("lookup-booking-input").value.trim().toUpperCase();
            if (!code) return;

            // 1. Cek di sirkulasiData lokal
            const found = sirkulasiData.find(r => r.kodeBooking.toUpperCase() === code || r.kodeBooking.toUpperCase().includes(code));
            if (found) {
                closeModal("modal-lookup-booking");
                if (found.status === "Booking") {
                    openHandoverModal(found.id);
                } else if (found.status === "Sedang Dipinjam") {
                    showToast("Sudah Dipinjam", `Buku pada kode ${found.kodeBooking} sudah diserahkan (${found.tglPinjam}) dan sedang dipinjam oleh ${found.nama}.`);
                } else {
                    showToast("Status Transaksi", `Kode ${found.kodeBooking} berstatus "${found.status}".`);
                }
                return;
            }

            // 2. Jika tidak ada di lokal, cari ke backend API
            fetch("/admin/api/loans/lookup-booking", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ code: code })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.loan) {
                    closeModal("modal-lookup-booking");
                    if (data.loan.isBooked) {
                        openHandoverModal(data.loan.id, data.loan);
                    } else {
                        showToast("Info Booking", `Kode ${code} berstatus "${data.loan.status}".`);
                    }
                } else {
                    showToast("Tidak Ditemukan", data.message || `Kode booking "${code}" tidak ditemukan.`);
                }
            })
            .catch(err => {
                console.error(err);
                showToast("Gagal Mencari", "Terjadi kesalahan saat mencari kode booking.");
            });
        }

        function openHandoverModal(rowId, fallbackItem = null) {
            let item = sirkulasiData.find(r => r.id === rowId) || fallbackItem;
            if (!item) return;

            document.getElementById("handover-target-id").value = item.id;
            document.getElementById("handover-modal-kode").textContent = item.kodeBooking;
            document.getElementById("handover-modal-nama").textContent = item.nama;
            document.getElementById("handover-modal-meta").textContent = `NIM: ${item.nim} • Kelas: ${item.kelas || '-'} • HP: ${item.hp || '-'}`;
            document.getElementById("handover-modal-buku").textContent = item.judulBuku;
            document.getElementById("handover-modal-kategori-rak").textContent = `Kategori: ${item.kategoriBuku}`;

            const isTA = isTACategory(item.kategoriBuku);
            document.getElementById("handover-modal-durasi").textContent = isTA ? "14 Hari Kerja (TA/Skripsi)" : "7 Hari Kerja (Buku Umum)";
            document.getElementById("handover-modal-denda-rate").textContent = isTA ? "Rp 300 / hari kerja" : "Rp 200 / hari kerja";
            document.getElementById("handover-jaminan-select").value = "KTM";

            document.getElementById("modal-handover-book").classList.remove("hidden");
            lucide.createIcons();
        }

        function confirmHandoverAction() {
            const rowId = Number(document.getElementById("handover-target-id").value);
            const item = sirkulasiData.find(r => r.id === rowId);
            const jaminan = document.getElementById("handover-jaminan-select").value;

            closeModal("modal-handover-book");

            fetch(`/admin/api/loans/${rowId}/confirm-handover`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ jaminan: jaminan })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (item) {
                        item.status = "Sedang Dipinjam";
                        item.tglPinjam = data.loan.tglPinjam;
                        item.jatuhTempo = data.loan.jatuhTempo;
                        item.catatanTempo = "Masa peminjaman aktif";
                        item.jaminan = jaminan;
                    } else if (data.loan) {
                        sirkulasiData.unshift({
                            id: data.loan.id,
                            kodeBooking: document.getElementById("handover-modal-kode").textContent,
                            status: "Sedang Dipinjam",
                            tglPinjam: data.loan.tglPinjam,
                            jatuhTempo: data.loan.jatuhTempo,
                            catatanTempo: "Masa peminjaman aktif",
                            nama: document.getElementById("handover-modal-nama").textContent,
                            nim: "-",
                            kelas: "-",
                            hp: "-",
                            judulBuku: document.getElementById("handover-modal-buku").textContent,
                            kategoriBuku: "Akuntansi",
                            jaminan: jaminan,
                            denda: 0
                        });
                    }
                    updateStatCards();
                    renderTable();
                    refreshDashboardStats();
                    showToast("Buku Berhasil Diserahkan!", data.message || "Peminjaman aktif telah dicatat.");
                } else {
                    showToast("Gagal", data.message || "Terjadi kesalahan.");
                }
            })
            .catch(err => {
                console.error(err);
                showToast("Gagal", "Tidak dapat menghubungi server.");
            });
        }

        function cancelBookingAdmin(rowId) {
            const item = sirkulasiData.find(r => r.id === rowId);
            const name = item ? item.nama : "mahasiswa";
            if (!confirm(`Batalkan pesanan booking ini untuk ${name}?`)) return;

            fetch(`/admin/api/loans/${rowId}/cancel-booking`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    sirkulasiData = sirkulasiData.filter(r => r.id !== rowId);
                    updateStatCards();
                    renderTable();
                    refreshDashboardStats();
                    showToast("Booking Dibatalkan", "Pesanan booking telah dibatalkan.");
                }
            })
            .catch(console.error);
        }

        // Modal WhatsApp Reminder
        function openWaModal(rowId) {
            const item = sirkulasiData.find(r => r.id === rowId);
            if (!item) return;

            const isLate = item.status === "Terlambat";
            document.getElementById("wa-modal-title").textContent = isLate
                ? "Kirim Notifikasi WA Terlambat"
                : "Kirim Pengingat WhatsApp";
            document.getElementById("wa-target-phone").textContent = `${item.nama} (${item.hp})`;

            const msg = isLate
                ? `Halo ${item.nama} (NIM: ${item.nim}, Kelas: ${item.kelas}),\n\nKami dari Perpustakaan Jurusan Akuntansi menginformasikan bahwa peminjaman buku "${item.judulBuku}" (Kode: ${item.kodeBooking}) telah melewati jatuh tempo pada ${item.jatuhTempo} (${item.catatanTempo}).\n\nMohon segera mengembalikan buku tersebut ke perpustakaan. Terima kasih.`
                : `Halo ${item.nama} (NIM: ${item.nim}),\n\nPengingat ramah dari Perpustakaan Jurusan Akuntansi untuk peminjaman buku "${item.judulBuku}" (Kode: ${item.kodeBooking}) dengan batas waktu ${item.jatuhTempo}. Terima kasih.`;

            document.getElementById("wa-message-box").value = msg;
            document.getElementById("modal-wa").classList.remove("hidden");
        }

        function sendWhatsAppSimulated() {
            const target = document.getElementById("wa-target-phone").textContent;
            closeModal("modal-wa");
            showToast("Pesan WhatsApp Terkirim", `Notifikasi pengingat berhasil dikirim ke ${target}.`);
        }

        // Modal Edit
        function openEditModal(rowId) {
            const item = sirkulasiData.find(r => r.id === rowId);
            if (!item) return;

            document.getElementById("edit-row-id").value = item.id;
            document.getElementById("edit-kode").value = item.kodeBooking;
            document.getElementById("edit-status").value = item.status;
            document.getElementById("edit-jatuh-tempo").value = item.jatuhTempo;
            document.getElementById("edit-denda").value = item.denda;
            document.getElementById("modal-edit").classList.remove("hidden");
        }

        function submitEditRow(e) {
            e.preventDefault();
            const rowId = Number(document.getElementById("edit-row-id").value);
            const item = sirkulasiData.find(r => r.id === rowId);
            if (!item) return;

            item.status = document.getElementById("edit-status").value;
            item.jatuhTempo = document.getElementById("edit-jatuh-tempo").value;
            item.denda = Number(document.getElementById("edit-denda").value) || 0;
            if (item.status === "Dikembalikan") item.catatanTempo = "";

            closeModal("modal-edit");
            refreshUI();
            refreshDashboardStats();
            showToast("Data Diperbarui", `Perubahan pada kode ${item.kodeBooking} telah disimpan.`);
        }

        function deleteRow(rowId) {
            const item = sirkulasiData.find(r => r.id === rowId);
            if (!item) return;
            sirkulasiData = sirkulasiData.filter(r => r.id !== rowId);
            selectedRowIds.delete(rowId);
            refreshUI();
            refreshDashboardStats();
            showToast("Transaksi Dihapus", `Data ${item.kodeBooking} telah dihapus dari daftar.`, "red");
        }

        function exportExcel(type) {
            const label = type === "denda" ? "Laporan_Denda_Akuntansi.xlsx" : "Laporan_Sirkulasi_Akuntansi.xlsx";
            showToast("Export Excel Berhasil", `File ${label} siap diunduh.`, type === "denda" ? "gold" : "green");
        }

        function toggleFilterPopover() {
            document.getElementById("filter-popover").classList.toggle("hidden");
        }

        function onFilterChange() {
            const st = document.getElementById("filter-status").value;
            const jm = document.getElementById("filter-jaminan").value;
            let count = 0;
            if (st !== "ALL") count++;
            if (jm !== "ALL") count++;
            document.getElementById("active-filter-badge").textContent = count;
            renderTable();
        }

        function resetFilters() {
            document.getElementById("filter-status").value = "ALL";
            document.getElementById("filter-jaminan").value = "ALL";
            onFilterChange();
        }

        function toggleRowCheckbox(id, checked) {
            if (checked) selectedRowIds.add(id);
            else selectedRowIds.delete(id);
            updateBulkBadge();
        }

        function toggleSelectAll(master) {
            const checkboxes = document.querySelectorAll(".row-checkbox");
            checkboxes.forEach(cb => {
                cb.checked = master.checked;
                const id = Number(cb.dataset.id);
                if (master.checked) selectedRowIds.add(id);
                else selectedRowIds.delete(id);
            });
            updateBulkBadge();
        }

        function updateBulkBadge() {
            const badge = document.getElementById("bulk-selected-info");
            if (selectedRowIds.size > 0) {
                badge.textContent = `${selectedRowIds.size} baris dipilih`;
                badge.classList.remove("hidden");
            } else {
                badge.classList.add("hidden");
            }
        }

        function updateStatCards() {
            const bookingCount = sirkulasiData.filter(d => d.status === "Booking").length;
            const borrowedCount = sirkulasiData.filter(d => d.status === "Sedang Dipinjam" || d.status === "Terlambat").length;
            const overdueCount = sirkulasiData.filter(d => d.status === "Terlambat").length;
            const totalFine = sirkulasiData.reduce((sum, d) => sum + (Number(d.denda) || 0), 0);

            document.getElementById("stat-booking").textContent = `${bookingCount} Transaksi`;
            document.getElementById("stat-borrowed").textContent = `${borrowedCount} Buku`;
            document.getElementById("stat-overdue").textContent = `${overdueCount} Transaksi`;
            document.getElementById("stat-fine").textContent = formatRupiahCard(totalFine);
            document.getElementById("sidebar-active-badge").textContent = borrowedCount;
        }

        function renderTable() {
            const tbody = document.getElementById("sirkulasi-table-body");
            if (!tbody) return;

            const search = document.getElementById("search-input").value.toLowerCase().trim();
            const statusFilter = document.getElementById("filter-status").value;
            const jaminanFilter = document.getElementById("filter-jaminan").value;
            const perPage = Number(document.getElementById("per-page-select").value) || 10;

            const filtered = sirkulasiData.filter(row => {
                if (statusFilter !== "ALL" && row.status !== statusFilter) return false;
                if (jaminanFilter !== "ALL" && row.jaminan !== jaminanFilter) return false;
                if (search) {
                    const hay = `${row.kodeBooking} ${row.nama} ${row.nim} ${row.kelas} ${row.judulBuku}`.toLowerCase();
                    return hay.includes(search);
                }
                return true;
            });

            const sliced = filtered.slice(0, perPage);
            const startNum = filtered.length === 0 ? 0 : 1;
            document.getElementById("pagination-summary").textContent = `Showing ${startNum} to ${sliced.length} of ${filtered.length} results`;

            if (sliced.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="py-10 text-center text-gray-400 text-sm">
                            Tidak ada data peminjaman atau pengembalian yang cocok.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = sliced.map(row => {
                const isBooking = row.status === "Booking";
                const isLate = row.status === "Terlambat";
                const isReturned = row.status === "Dikembalikan";

                const kodeBadge = `<span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold tracking-wide ${isBooking ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-forest-50 text-forest-800 border border-forest-200'}">${row.kodeBooking}</span>`;

                const tempoHtml = isBooking
                    ? `<div>
                        <span class="font-bold text-amber-900 block">${row.jatuhTempo}</span>
                        <span class="text-[11px] text-amber-700 font-medium">${row.catatanTempo || 'Batas ambil di perpus'}</span>
                       </div>`
                    : (isLate
                        ? `<div>
                            <span class="font-semibold text-rose-600 block">${row.jatuhTempo}</span>
                            <span class="text-[11px] text-rose-500 font-medium">${row.catatanTempo}</span>
                           </div>`
                        : `<div>
                            <span class="font-medium text-gray-800 block">${row.jatuhTempo}</span>
                            ${row.catatanTempo ? `<span class="text-[11px] text-amber-700">${row.catatanTempo}</span>` : ""}
                           </div>`);

                let statusPill = "";
                if (row.status === "Terlambat") {
                    statusPill = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200">
                        <i data-lucide="alert-triangle" class="w-3 h-3"></i> Terlambat
                    </span>`;
                } else if (row.status === "Dikembalikan") {
                    statusPill = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i data-lucide="check-circle-2" class="w-3 h-3"></i> Dikembalikan
                    </span>`;
                } else if (row.status === "Booking") {
                    statusPill = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                        Booking (Siap Diambil)
                    </span>`;
                } else {
                    statusPill = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-forest-50 text-forest-800 border border-forest-200">
                        <i data-lucide="book-open" class="w-3 h-3"></i> Sedang Dipinjam
                    </span>`;
                }

                const dendaHtml = row.denda > 0
                    ? `<span class="font-bold text-rose-600">${formatRupiahDecimal(row.denda)}</span>`
                    : `<span class="text-gray-600">${formatRupiahDecimal(0)}</span>`;

                let actionButtons = "";
                if (isBooking) {
                    actionButtons = `
                        <div class="inline-flex items-center justify-end gap-2">
                            <button type="button" onclick="openHandoverModal(${row.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 active:scale-[0.99] text-white font-bold text-xs shadow-xs transition-all">
                                <i data-lucide="hand-helping" class="w-3.5 h-3.5 text-emerald-300"></i>
                                <span>Serahkan Buku</span>
                            </button>
                            <button type="button" onclick="cancelBookingAdmin(${row.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-800">
                                <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                                <span>Batal</span>
                            </button>
                            <button type="button" onclick="deleteRow(${row.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-gray-400 hover:text-rose-800">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    `;
                } else if (!isReturned) {
                    const waBtn = row.waType === "WA Terlambat"
                        ? `<button type="button" onclick="openWaModal(${row.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-800">
                            <i data-lucide="message-square-warning" class="w-3.5 h-3.5"></i> WA Terlambat
                           </button>`
                        : `<button type="button" onclick="openWaModal(${row.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:text-emerald-900">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5"></i> Ingatkan WA
                           </button>`;

                    actionButtons = `
                        <div class="inline-flex items-center justify-end gap-2.5">
                            ${waBtn}
                            <button type="button" onclick="openReturnModal(${row.id})" class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-gold-100 hover:bg-gold-200 text-amber-900 border border-gold-300 text-xs font-bold transition-colors">
                                <i data-lucide="undo-2" class="w-3.5 h-3.5"></i> Kembalikan
                            </button>
                            <button type="button" onclick="openEditModal(${row.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-forest-700 hover:text-forest-900">
                                <i data-lucide="square-pen" class="w-3.5 h-3.5"></i> Edit
                            </button>
                            <button type="button" onclick="deleteRow(${row.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-800">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                            </button>
                        </div>
                    `;
                } else {
                    actionButtons = `
                        <div class="inline-flex items-center justify-end gap-3">
                            <button type="button" onclick="openEditModal(${row.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-forest-700 hover:text-forest-900">
                                <i data-lucide="square-pen" class="w-3.5 h-3.5"></i> Edit
                            </button>
                            <button type="button" onclick="deleteRow(${row.id})" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-800">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                            </button>
                        </div>
                    `;
                }

                return `
                    <tr class="hover:bg-forest-50/40 transition-colors">
                        <td class="py-3.5 pl-4 pr-2">
                            <input type="checkbox" data-id="${row.id}" ${selectedRowIds.has(row.id) ? "checked" : ""} onchange="toggleRowCheckbox(${row.id}, this.checked)" class="row-checkbox rounded border-gray-300 text-forest-700 focus:ring-forest-600">
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">${kodeBadge}</td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <p class="font-semibold text-gray-900">${row.nama}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">NIM: ${row.nim} • Kelas: ${row.kelas} • HP: ${row.hp} (${row.jaminan})</p>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <p class="font-semibold text-gray-900">${row.judulBuku}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">${row.kategoriBuku}</p>
                        </td>
                        <td class="py-3.5 px-3 text-gray-700 whitespace-nowrap">${row.tglPinjam}</td>
                        <td class="py-3.5 px-3 whitespace-nowrap">${tempoHtml}</td>
                        <td class="py-3.5 px-3 whitespace-nowrap">${statusPill}</td>
                        <td class="py-3.5 px-3 whitespace-nowrap">${dendaHtml}</td>
                        <td class="py-3.5 pl-3 pr-4 text-right whitespace-nowrap">${actionButtons}</td>
                    </tr>
                `;
            }).join("");

            lucide.createIcons();
        }

        function refreshUI() {
            updateStatCards();
            renderTable();
            updateBulkBadge();
        }

        // =========================================================================
        // 8. EVENT LISTENERS GLOBAL & INISIALISASI
        // =========================================================================
        document.addEventListener("click", (e) => {
            const pop = document.getElementById("filter-popover");
            const btn = document.getElementById("filter-toggle-btn");
            if (pop && btn && !pop.contains(e.target) && !btn.contains(e.target)) {
                pop.classList.add("hidden");
            }

            const bookPop = document.getElementById("book-filter-popover");
            const bookBtn = document.getElementById("book-filter-btn");
            if (bookPop && bookBtn && !bookPop.contains(e.target) && !bookBtn.contains(e.target)) {
                bookPop.classList.add("hidden");
            }

            const angPop = document.getElementById("anggota-filter-popover");
            const angBtn = document.getElementById("anggota-filter-btn");
            if (angPop && angBtn && !angPop.contains(e.target) && !angBtn.contains(e.target)) {
                angPop.classList.add("hidden");
            }
        });

        document.getElementById("mobile-menu-btn").addEventListener("click", () => {
            document.getElementById("sidebar-drawer").classList.toggle("-translate-x-full");
        });

        const bookDropzone = document.getElementById("create-book-dropzone");
        if (bookDropzone) {
            bookDropzone.addEventListener("dragover", (e) => {
                e.preventDefault();
                bookDropzone.classList.add("border-forest-600", "bg-forest-50");
            });
            bookDropzone.addEventListener("dragleave", () => {
                bookDropzone.classList.remove("border-forest-600", "bg-forest-50");
            });
            bookDropzone.addEventListener("drop", (e) => {
                e.preventDefault();
                bookDropzone.classList.remove("border-forest-600", "bg-forest-50");
                const file = e.dataTransfer?.files?.[0];
                if (file) handleBookCoverFile(file);
            });
        }

        window.addEventListener("DOMContentLoaded", () => {
            lucide.createIcons();
            populateBookCategoryOptions();
            refreshUI();
            refreshDashboardStats();
            renderDashboardTables();
            renderDashboardChart();
            renderAnggotaTable();
            renderBooksTable();

            let currentTab = "{{ $activeTab ?? '' }}";
            if (!currentTab) {
                const path = window.location.pathname.toLowerCase();
                if (path.includes('/buku')) currentTab = 'buku';
                else if (path.includes('/anggota')) currentTab = 'anggota';
                else if (path.includes('/peminjaman') || path.includes('/sirkulasi')) currentTab = 'sirkulasi';
                else currentTab = 'dashboard';
            }
            switchSidebarMenu(currentTab);

            @if(!empty($openCategoryModal))
                setTimeout(() => openKategoriModal(), 150);
            @endif
        });
    </script>
</body>
</html>