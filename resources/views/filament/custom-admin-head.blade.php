<!-- Google Fonts: Inter & Plus Jakarta Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* =========================================================
       PUSTAKA AKUNTANSI - ADMIN PANEL THEME (Forest & Gold)
       Matching mockup_pustaka_akuntansi.html
       ========================================================= */

    /* Typography Overrides */
    body, .fi, .fi-body {
        font-family: 'Inter', sans-serif !important;
        background-color: #f9fafb !important;
        color: #111827 !important;
    }

    h1, h2, h3, .fi-header-heading, .font-display {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    .fi-header-heading {
        font-size: 1.75rem !important;
        font-weight: 800 !important;
        letter-spacing: -0.025em !important;
        color: #111827 !important;
    }

    /* Scrollbars */
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

    /* =========================================================
       FULL-WIDTH TOPBAR (PERSIS MOCKUP)
       ========================================================= */
    .fi-custom-full-header {
        background: linear-gradient(to right, #022c22, #064e3b, #047857) !important;
        border-bottom: 1px solid rgba(4, 120, 87, 0.8) !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12) !important;
        height: 4rem !important;
    }

    /* Hide standard filament topbar inside main content to prevent duplicates */
    div.fi-topbar, header.fi-topbar, .fi-topbar {
        display: none !important;
    }

    /* =========================================================
       SIDEBAR: Below Header, Clean White with Forest-50 Active State
       ========================================================= */
    aside.fi-sidebar, .fi-sidebar {
        top: 4rem !important;
        height: calc(100vh - 4rem) !important;
        background-color: #ffffff !important;
        border-right: 1px solid rgba(229, 231, 235, 0.9) !important;
        z-index: 30 !important;
    }

    /* Hide default sidebar header since brand is in full topbar */
    .fi-sidebar-header {
        display: none !important;
    }

    /* Sidebar Footer holds the Aturan Sirkulasi card at the bottom */
    .fi-sidebar-footer {
        padding: 0 !important;
        border-top: none !important;
        background: transparent !important;
    }

    /* Active Nav Item */
    .fi-sidebar-item-active > a,
    .fi-sidebar-item-active > button,
    .fi-sidebar-item-active .fi-sidebar-item-button {
        background-color: #ecfdf5 !important;
        color: #065f46 !important;
        box-shadow: inset 0 0 0 1px rgba(167, 243, 208, 0.9) !important;
        font-weight: 600 !important;
        border-radius: 0.5rem !important;
    }

    .fi-sidebar-item-active svg,
    .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #047857 !important;
    }

    /* Inactive Nav Item */
    .fi-sidebar-item-button:not(.fi-sidebar-item-active) {
        color: #4b5563 !important;
        font-weight: 500 !important;
        border-radius: 0.5rem !important;
        transition: all 0.15s ease;
    }

    .fi-sidebar-item-button:not(.fi-sidebar-item-active):hover {
        background-color: rgba(236, 253, 245, 0.7) !important;
        color: #065f46 !important;
    }

    .fi-sidebar-item-button:not(.fi-sidebar-item-active) svg,
    .fi-sidebar-item-button:not(.fi-sidebar-item-active) .fi-sidebar-item-icon {
        color: #9ca3af !important;
    }

    .fi-sidebar-item-button:not(.fi-sidebar-item-active):hover svg,
    .fi-sidebar-item-button:not(.fi-sidebar-item-active):hover .fi-sidebar-item-icon {
        color: #059669 !important;
    }

    /* Sidebar Badge (Gold pill matching mockup) */
    .fi-sidebar-item-badge,
    .fi-sidebar-item .fi-badge,
    .fi-sidebar-group-badge {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #fcd34d !important;
        font-weight: 700 !important;
        border-radius: 0.375rem !important;
        font-size: 11px !important;
        padding: 0.125rem 0.5rem !important;
    }

    /* =========================================================
       MAIN CONTENT: Cards, Tables & Sections
       ========================================================= */
    .fi-layout {
        padding-top: 4rem !important;
    }

    .fi-main {
        background-color: #f9fafb !important;
        padding-top: 1.5rem !important;
        padding-bottom: 3rem !important;
        max-width: 1440px !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .fi-section,
    .fi-ta-ctn {
        background-color: #ffffff !important;
        border: 1px solid rgba(229, 231, 235, 0.9) !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04) !important;
    }

    /* Tables */
    .fi-ta-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    .fi-ta-header-cell {
        background-color: rgba(249, 250, 251, 0.95) !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        color: #374151 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        border-bottom: 1px solid #e5e7eb !important;
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }

    .fi-ta-row:hover {
        background-color: rgba(236, 253, 245, 0.35) !important;
    }

    /* =========================================================
       BUTTON STYLES (Mockup Hierarchy)
       ========================================================= */
    /* Primary Dark Forest Button (Tambah Buku, Buat Data, dll) */
    .fi-btn-color-primary:not(.fi-btn-outlined) {
        background-color: #064e3b !important;
        color: #ffffff !important;
        border: 1px solid #047857 !important;
        font-weight: 700 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        transition: all 0.15s ease;
    }

    .fi-btn-color-primary:not(.fi-btn-outlined):hover {
        background-color: #065f46 !important;
        border-color: #059669 !important;
    }

    /* Warning / Gold Button (Catat Peminjaman, Buka Katalog, Aksi Cepat) */
    .fi-btn-color-warning:not(.fi-btn-outlined) {
        background-color: #fbbf24 !important;
        color: #022c22 !important;
        border: 1px solid #f59e0b !important;
        font-weight: 800 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        transition: all 0.15s ease;
    }

    .fi-btn-color-warning:not(.fi-btn-outlined):hover {
        background-color: #f59e0b !important;
    }

    /* Outlined / Gray Button (Download Template, Batal, Kembali) */
    .fi-btn-color-gray {
        background-color: #ffffff !important;
        color: #374151 !important;
        border: 1px solid #d1d5db !important;
        font-weight: 600 !important;
        border-radius: 0.5rem !important;
    }

    .fi-btn-color-gray:hover {
        background-color: #f9fafb !important;
        color: #111827 !important;
    }

    /* Badges */
    .fi-badge-color-primary, .fi-badge-color-success {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border: 1px solid #a7f3d0 !important;
    }

    .fi-badge-color-warning {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #fcd34d !important;
    }

    .fi-badge-color-danger {
        background-color: #ffe4e6 !important;
        color: #9f1239 !important;
        border: 1px solid #fecdd3 !important;
    }

    .fi-badge-color-info {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
    }

    /* Header action container alignment */
    .fi-header-actions {
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
    }
</style>
