<x-filament-widgets::widget>
    <style>
        .pa-qa-card {
            --qa-title: #111827;
            --qa-subtitle: #4b5563;
            --qa-divider: rgba(17, 24, 39, 0.09);
            --qa-btn-ghost-bg: #f3f4f6;
            --qa-btn-ghost-text: #111827;
            --qa-btn-ghost-border: #d1d5db;
            --qa-sop-bg: #f8fafc;
            --qa-sop-border: #e2e8f0;
            --qa-sop-heading: #111827;
            --qa-sop-text: #374151;
            --qa-sop-strong: #111827;
            --qa-emerald-hl: #047857;
            --qa-amber-hl: #b45309;
            --qa-rose-hl: #be123c;
            --qa-badge-sop-bg: #fef3c7;
            --qa-badge-sop-text: #b45309;
            --qa-badge-sop-border: #fcd34d;
            --qa-footer-text: #374151;
        }

        .dark .pa-qa-card {
            --qa-title: #ffffff;
            --qa-subtitle: #9ca3af;
            --qa-divider: rgba(255, 255, 255, 0.1);
            --qa-btn-ghost-bg: rgba(255, 255, 255, 0.06);
            --qa-btn-ghost-text: #ffffff;
            --qa-btn-ghost-border: rgba(255, 255, 255, 0.16);
            --qa-sop-bg: rgba(0, 0, 0, 0.28);
            --qa-sop-border: rgba(255, 255, 255, 0.09);
            --qa-sop-heading: #ffffff;
            --qa-sop-text: #d1d5db;
            --qa-sop-strong: #ffffff;
            --qa-emerald-hl: #6ee7b7;
            --qa-amber-hl: #fcd34d;
            --qa-rose-hl: #fda4af;
            --qa-badge-sop-bg: rgba(245, 158, 11, 0.16);
            --qa-badge-sop-text: #fbbf24;
            --qa-badge-sop-border: rgba(245, 158, 11, 0.3);
            --qa-footer-text: #d1d5db;
        }

        .pa-qa-actions-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        @media (max-width: 640px) {
            .pa-qa-actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="pa-card pa-qa-card rounded-2xl" style="padding: 26px; display: flex; flex-direction: column; gap: 20px;">
        {{-- Header Card --}}
        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; padding-bottom: 16px; border-bottom: 1px solid var(--qa-divider);">
            <div style="display: flex; align-items: flex-start; gap: 13px;">
                <span style="width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px; background-color: rgba(16, 185, 129, 0.14); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3);">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                </span>
                <div>
                    <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 17px; font-weight: 800; color: var(--qa-title); margin: 0; line-height: 1.35;">
                        Aksi Cepat &amp; Info Perpustakaan
                    </h3>
                    <p style="font-size: 13px; line-height: 1.55; margin: 4px 0 0 0; color: var(--qa-subtitle);">
                        Perpustakaan Jurusan Akuntansi &bull; Koleksi TA/Skripsi, PKL, Buku Umum, serta Layanan Print &amp; Fotocopy
                    </p>
                </div>
            </div>

            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; flex-shrink: 0; background-color: rgba(16, 185, 129, 0.14); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3);">
                <span style="width: 7px; height: 7px; border-radius: 9999px; background-color: #10b981;"></span>
                <span>SOP Aktif</span>
            </span>
        </div>

        {{-- 3 Tombol Aksi Cepat Sejajar 1 Baris --}}
        <div class="pa-qa-actions-grid">
            <a
                href="{{ \App\Filament\Resources\LoanResource::getUrl('create') }}"
                style="display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 12px 14px; border-radius: 11px; font-weight: 700; font-size: 13px; white-space: nowrap; text-decoration: none; background-color: #fbbf24; color: #111827; box-shadow: 0 1px 3px rgba(0,0,0,0.12);"
            >
                <svg style="width: 15px; height: 15px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Catat Peminjaman</span>
            </a>

            <a
                href="{{ \App\Filament\Resources\BookResource::getUrl('create') }}"
                style="display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 12px 14px; border-radius: 11px; font-weight: 700; font-size: 13px; white-space: nowrap; text-decoration: none; background-color: #2f7a59; color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.12);"
            >
                <svg style="width: 15px; height: 15px; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Buku</span>
            </a>

            <a
                href="{{ \App\Filament\Resources\AnggotaResource::getUrl('create') }}"
                style="display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 12px 14px; border-radius: 11px; font-weight: 700; font-size: 13px; white-space: nowrap; text-decoration: none; background-color: var(--qa-btn-ghost-bg); color: var(--qa-btn-ghost-text); border: 1px solid var(--qa-btn-ghost-border);"
            >
                <svg style="width: 15px; height: 15px; flex-shrink: 0; color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
                <span>Tambah Anggota</span>
            </a>
        </div>

        {{-- Box Ketentuan & SOP Sirkulasi --}}
        <div style="border-radius: 14px; padding: 18px 20px; background-color: var(--qa-sop-bg); border: 1px solid var(--qa-sop-border); display: flex; flex-direction: column; gap: 14px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--qa-divider);">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 17px; height: 17px; color: #10b981; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span style="font-size: 14px; font-weight: 700; color: var(--qa-sop-heading);">Ketentuan &amp; Batas Peminjaman</span>
                </div>
                <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em; background-color: var(--qa-badge-sop-bg); color: var(--qa-badge-sop-text); border: 1px solid var(--qa-badge-sop-border);">
                    SOP Sirkulasi
                </span>
            </div>

            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 11px; font-size: 13px; line-height: 1.65; color: var(--qa-sop-text);">
                <li style="display: flex; align-items: flex-start; gap: 10px;">
                    <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #10b981; margin-top: 8px; flex-shrink: 0;"></span>
                    <span>
                        <strong style="color: var(--qa-sop-strong); font-weight: 700;">Durasi &amp; Kuota:</strong> TA/Skripsi <strong style="color: var(--qa-emerald-hl); font-weight: 700;">14 hari kerja</strong>; Non-TA/Skripsi, Proposal TA/PKL &amp; Laporan PKL <strong style="color: var(--qa-emerald-hl); font-weight: 700;">7 hari kerja</strong> (1 kartu perpustakaan/KTM/kartu asuransi maks. <strong style="color: var(--qa-sop-strong); font-weight: 700;">2 buku</strong>).
                    </span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 10px;">
                    <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #f59e0b; margin-top: 8px; flex-shrink: 0;"></span>
                    <span>
                        <strong style="color: var(--qa-sop-strong); font-weight: 700;">Denda Keterlambatan:</strong> TA/Skripsi <strong style="color: var(--qa-amber-hl); font-weight: 700;">Rp 300/hari</strong>; Non-TA/Skripsi, Proposal TA/PKL &amp; Laporan PKL <strong style="color: var(--qa-amber-hl); font-weight: 700;">Rp 200/hari</strong>.
                    </span>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 10px;">
                    <span style="width: 6px; height: 6px; border-radius: 9999px; background-color: #f43f5e; margin-top: 8px; flex-shrink: 0;"></span>
                    <span>
                        <strong style="color: var(--qa-sop-strong); font-weight: 700;">Denda Kehilangan:</strong> TA/Skripsi &amp; Proposal/Laporan PKL <strong style="color: var(--qa-rose-hl); font-weight: 700;">Rp 200.000</strong>; Non-TA/Skripsi wajib mengganti buku yang sama atau seharga buku yang dihilangkan.
                    </span>
                </li>
            </ul>
        </div>

        {{-- Footer Jam Layanan & Kontak WA --}}
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; padding-top: 14px; border-top: 1px solid var(--qa-divider); font-size: 12.5px;">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px; color: var(--qa-footer-text);">
                <svg style="width: 16px; height: 16px; color: #10b981; flex-shrink: 0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Sen–Kam: <strong style="color: var(--qa-sop-strong);">08.30–15.30</strong></span>
                <span style="opacity: 0.35;">|</span>
                <span>Jum: <strong style="color: var(--qa-sop-strong);">08.30–14.00 WIB</strong></span>
                <span style="padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; background-color: rgba(16, 185, 129, 0.16); color: #059669;">
                    Buka
                </span>
            </div>

            <div style="font-weight: 700; color: #059669;">
                WA: 0895-3363-84919
            </div>
        </div>
    </div>
</x-filament-widgets::widget>