<x-filament-widgets::widget>
    <x-filament::section class="h-full">
        <x-slot name="heading">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-950/60 dark:text-primary-400">
                    <x-filament::icon
                        icon="heroicon-o-bolt"
                        class="h-5 w-5"
                    />
                </div>
                <span class="text-base font-semibold">Aksi Cepat & Info Perpustakaan</span>
            </div>
        </x-slot>

        <x-slot name="description">
            Pustaka Akuntansi &bull; Akses instan dan ringkasan panduan operasional layanan
        </x-slot>

        <x-slot name="headerActions">
            <x-filament::badge color="success" icon="heroicon-m-check-circle">
                SOP Aktif
            </x-filament::badge>
        </x-slot>

        <div class="space-y-4 pt-1">
            {{-- Tombol Cepat: Berjejer Horizontal 3 Kolom --}}
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                {{-- Tombol 1: Catat Peminjaman --}}
                <x-filament::button
                    tag="a"
                    href="{{ route('filament.admin.resources.loans.create') }}"
                    icon="heroicon-m-clipboard-document-check"
                    color="warning"
                    size="md"
                    class="w-full justify-center shadow-sm font-semibold"
                >
                    + Catat Peminjaman
                </x-filament::button>

                {{-- Tombol 2: Tambah Buku --}}
                <x-filament::button
                    tag="a"
                    href="{{ route('filament.admin.resources.books.create') }}"
                    icon="heroicon-m-plus-circle"
                    color="primary"
                    size="md"
                    class="w-full justify-center shadow-sm font-semibold"
                >
                    + Tambah Buku
                </x-filament::button>

                {{-- Tombol 3: Tambah Kategori --}}
                <x-filament::button
                    tag="a"
                    href="{{ route('filament.admin.resources.categories.create') }}"
                    icon="heroicon-m-tag"
                    color="info"
                    size="md"
                    class="w-full justify-center shadow-sm font-semibold"
                >
                    + Tambah Kategori
                </x-filament::button>
            </div>

            {{-- Ringkasan Panduan Operasional Perpustakaan (SOP) dengan Kontras Tinggi --}}
            <div class="rounded-xl border border-gray-200 bg-gray-50/90 p-4 dark:border-gray-700/80 dark:bg-gray-800/90">
                <div class="mb-3 flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-800 dark:text-gray-100">
                        📋 Ringkasan Panduan Operasional
                    </span>
                    <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-300">
                        SOP Sirkulasi
                    </span>
                </div>

                <ul class="space-y-2.5 text-xs text-gray-700 dark:text-gray-200">
                    <li class="flex items-start gap-2.5">
                        <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-[10px] dark:bg-blue-950 dark:text-blue-300">
                            1
                        </span>
                        <span class="leading-relaxed">
                            <strong class="text-gray-950 dark:text-white">Ketentuan Peminjaman:</strong> Maks. 3 eksemplar per mahasiswa selama 7 hari kalender. Perpanjangan mandiri 1x.
                        </span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-bold text-[10px] dark:bg-indigo-950 dark:text-indigo-300">
                            2
                        </span>
                        <span class="leading-relaxed">
                            <strong class="text-gray-950 dark:text-white">Standar Entri Koleksi:</strong> Pastikan nomor ISBN valid, kategori spesifik akuntansi, dan isi ringkasan sinopsis/abstrak.
                        </span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700 font-bold text-[10px] dark:bg-amber-950 dark:text-amber-300">
                            3
                        </span>
                        <span class="leading-relaxed">
                            <strong class="text-gray-950 dark:text-white">Verifikasi Stok Fisik:</strong> Cek fisik buku di rak dan cocokkan dengan angka stok sebelum memverifikasi pengembalian.
                        </span>
                    </li>
                </ul>
            </div>

            {{-- Footer Info & Jam Layanan Dinamis --}}
            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 pt-3 text-xs dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1.5 text-xs font-medium text-gray-700 dark:text-gray-200">
                        <x-filament::icon icon="heroicon-o-clock" class="h-4 w-4 text-gray-400 dark:text-gray-400" />
                        Jam Layanan: Sen &ndash; Jum (08.00 &ndash; 16.00 WIB)
                    </span>
                    @if ($isServiceOpen)
                        <x-filament::badge color="success" size="sm" icon="heroicon-m-check-circle">
                            Layanan Buka
                        </x-filament::badge>
                    @else
                        <x-filament::badge color="gray" size="sm" icon="heroicon-m-x-circle">
                            Tutup
                        </x-filament::badge>
                    @endif
                </div>

                <x-filament::link
                    href="{{ route('filament.admin.resources.books.index') }}"
                    icon="heroicon-m-arrow-right"
                    icon-position="after"
                    size="sm"
                >
                    Katalog Lengkap
                </x-filament::link>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
