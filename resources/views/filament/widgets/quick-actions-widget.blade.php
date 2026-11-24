<x-filament-widgets::widget>
    <div class="pa-card rounded-2xl p-5 sm:p-6 flex flex-col justify-between space-y-5">
        <div class="flex items-start justify-between gap-2">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #f59e0b;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Aksi Cepat &amp; Info Perpustakaan
                    </h3>
                    <p class="pa-muted text-xs text-gray-500 mt-0.5">
                        Perpustakaan Jurusan Akuntansi • Koleksi TA/Skripsi, PKL, Buku Umum, serta Layanan Print &amp; Fotocopy
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold shrink-0" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">
                <span class="w-1.5 h-1.5 rounded-full" style="background: #10b981;"></span>
                SOP Aktif
            </span>
        </div>

        {{-- 3 Tombol Aksi Cepat --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
            <a
                href="{{ \App\Filament\Resources\LoanResource::getUrl('create') }}"
                class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl font-bold text-xs shadow-sm transition-all hover:brightness-105"
                style="background: #fbbf24; color: #022c22; border: 1px solid rgba(245, 158, 11, 0.4);"
            >
                <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Catat Peminjaman</span>
            </a>
            <a
                href="{{ \App\Filament\Resources\BookResource::getUrl('create') }}"
                class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl font-bold text-xs shadow-sm transition-all hover:brightness-105"
                style="background: #059669; color: #ffffff;"
            >
                <svg class="w-3.5 h-3.5 stroke-[2.5]" style="color: #fcd34d;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Buku</span>
            </a>
            <a
                href="{{ \App\Filament\Resources\AnggotaResource::getUrl('create') }}"
                class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl font-bold text-xs shadow-sm transition-all hover:brightness-105"
                style="background: #064e3b; color: #ffffff; box-shadow: inset 0 0 0 1px rgba(251, 191, 36, 0.4);"
            >
                <svg class="w-3.5 h-3.5" style="color: #fbbf24;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
                <span>Tambah Anggota</span>
            </a>
        </div>

        {{-- Box Ringkasan Panduan Operasional SOP --}}
        <div class="pa-sop-box rounded-xl p-4 space-y-2.5">
            <div class="flex items-center justify-between border-b pb-2" style="border-color: rgba(16, 185, 129, 0.25);">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold">
                    <svg class="w-4 h-4" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Ketentuan &amp; Batas Peminjaman</span>
                </span>
                <span class="text-[11px] font-bold px-2 py-0.5 rounded" style="background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.35);">
                    SOP Sirkulasi
                </span>
            </div>
            <ol class="space-y-1.5 text-xs leading-relaxed list-decimal list-inside">
                <li><b>Durasi &amp; Kuota:</b> TA/Skripsi <b>14 hari kerja</b>; Non-TA/Skripsi, Proposal TA/PKL &amp; Laporan PKL <b>7 hari kerja</b> (1 kartu perpustakaan/KTM/kartu asuransi maks. <b>2 buku</b>).</li>
                <li><b>Denda Keterlambatan:</b> TA/Skripsi <b>Rp 300/hari</b>; Non-TA/Skripsi, Proposal TA/PKL &amp; Laporan PKL <b>Rp 200/hari</b>.</li>
                <li><b>Denda Kehilangan:</b> TA/Skripsi &amp; Proposal/Laporan PKL <b>Rp 200.000</b>; Non-TA/Skripsi mengganti buku yang sama atau seharga buku yang dihilangkan.</li>
            </ol>
        </div>

        {{-- Footer Info Jam Layanan & WA Perpustakaan --}}
        <div class="flex flex-wrap items-center justify-between gap-2 pt-1 text-xs">
            <div class="pa-muted flex flex-wrap items-center gap-1.5 text-gray-600">
                <svg class="w-3.5 h-3.5 shrink-0" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Sen–Kam: 08.30–15.30 | Jum: 08.30–14.00 WIB</span>
                @if ($isServiceOpen)
                    <span class="px-2 py-0.5 rounded font-semibold text-[11px]" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">
                        Buka
                    </span>
                @else
                    <span class="px-2 py-0.5 rounded font-semibold text-[11px]" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">
                        Tutup
                    </span>
                @endif
            </div>
            <a
                href="https://wa.me/62895336384919"
                target="_blank"
                rel="noopener noreferrer"
                class="font-bold inline-flex items-center gap-1 hover:underline"
                style="color: #10b981;"
            >
                <span>WA: 0895-3363-84919</span>
            </a>
        </div>
    </div>
</x-filament-widgets::widget>