<x-filament-widgets::widget>
    <div class="bg-white rounded-xl border border-gray-200/90 shadow-2xs p-5 sm:p-6 flex flex-col justify-between space-y-5">
        <div class="flex items-start justify-between gap-2">
            <div class="flex items-start gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #d97706;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Aksi Cepat &amp; Info Perpustakaan
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Pustaka Akuntansi • Akses instan dan ringkasan panduan operasional layanan
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold shrink-0 border" style="background: #ecfdf5; color: #047857; border-color: #a7f3d0;">
                <span class="w-1.5 h-1.5 rounded-full" style="background: #059669;"></span>
                SOP Aktif
            </span>
        </div>

        {{-- 3 Tombol Aksi Cepat Persis Mockup --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
            <a
                href="{{ route('filament.admin.resources.peminjaman.create') }}"
                class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl font-bold text-xs shadow-2xs transition-all hover:brightness-105 active:scale-[0.99]"
                style="background: #fbbf24; color: #022c22; border: 1px solid rgba(245, 158, 11, 0.4);"
            >
                <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Catat Peminjaman</span>
            </a>
            <a
                href="{{ route('filament.admin.resources.buku.create') }}"
                class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl font-bold text-xs shadow-2xs transition-all hover:brightness-105 active:scale-[0.99]"
                style="background: #047857; color: #ffffff;"
            >
                <svg class="w-3.5 h-3.5 stroke-[2.5]" style="color: #fcd34d;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Buku</span>
            </a>
            <a
                href="{{ route('filament.admin.resources.kategori.create') }}"
                class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl font-bold text-xs shadow-2xs transition-all hover:brightness-105 active:scale-[0.99]"
                style="background: #064e3b; color: #ffffff; box-shadow: inset 0 0 0 1px rgba(251, 191, 36, 0.4);"
            >
                <svg class="w-3.5 h-3.5" style="color: #fbbf24;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                </svg>
                <span>Tambah Kategori</span>
            </a>
        </div>

        {{-- Box Ringkasan Panduan Operasional SOP Persis Mockup --}}
        <div class="rounded-xl border p-4 space-y-2.5" style="background: linear-gradient(135deg, rgba(236, 253, 245, 0.9) 0%, rgba(254, 243, 199, 0.6) 100%); border-color: rgba(167, 243, 208, 0.8);">
            <div class="flex items-center justify-between border-b pb-2" style="border-color: rgba(167, 243, 208, 0.7);">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold" style="color: #022c22;">
                    <svg class="w-4 h-4" style="color: #047857;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Ringkasan Panduan Operasional</span>
                </span>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded border" style="background: #fef3c7; color: #92400e; border-color: #fcd34d;">
                    SOP Sirkulasi
                </span>
            </div>
            <ol class="space-y-1.5 text-xs text-gray-700 leading-relaxed list-decimal list-inside">
                <li><b>Ketentuan Peminjaman:</b> Maks. 2 buku aktif per NIM selama 7 hari kerja (TA/Skripsi 14 hari kerja). Perpanjangan mandiri 1×.</li>
                <li><b>Standar Entri Koleksi:</b> Pastikan nomor ISBN valid, kategori spesifik akuntansi, dan isi ringkasan sinopsis/abstrak.</li>
                <li><b>Verifikasi Stok Fisik:</b> Cek fisik buku di rak dan cocokkan dengan angka stok sebelum memverifikasi pengembalian.</li>
            </ol>
        </div>

        {{-- Footer Info Jam Layanan Persis Mockup --}}
        <div class="flex flex-wrap items-center justify-between gap-2 pt-1 text-xs">
            <div class="flex items-center gap-2 text-gray-600">
                <svg class="w-3.5 h-3.5" style="color: #047857;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Jam Layanan: Sen – Jum (08.00 – 16.00 WIB)</span>
                <span class="px-2 py-0.5 rounded font-semibold text-[11px] border" style="background: #ecfdf5; color: #047857; border-color: #a7f3d0;">
                    Buka
                </span>
            </div>
            <a
                href="{{ route('filament.admin.resources.buku.index') }}"
                class="font-bold inline-flex items-center gap-1 hover:underline"
                style="color: #047857;"
            >
                <span>Katalog Lengkap</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
</x-filament-widgets::widget>
