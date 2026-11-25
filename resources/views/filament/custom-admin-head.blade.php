<!-- Google Fonts: Inter & Plus Jakarta Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    /* Skala root proporsional untuk layar laptop (setara zoom ~87.5%) */
    html {
        font-size: 13px !important;
    }
    /* =========================================================
       PERPUSTAKAAN JURUSAN AKUNTANSI - ADMIN PANEL THEME
       100% Kembar Identik dengan Portal User (Spacing Lega & Presisi)
       ========================================================= */

    body, .fi, .fi-body {
        font-family: 'Inter', sans-serif !important;
    }

    h1, h2, h3, .fi-header-heading, .font-display {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    .fi-header-heading {
        font-size: 1.85rem !important;
        font-weight: 800 !important;
        letter-spacing: -0.025em !important;
        margin-bottom: 0.35rem !important;
    }

    /* Scrollbars */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    html:not(.dark) ::-webkit-scrollbar-track { background: #f2f8f5; }
    html:not(.dark) ::-webkit-scrollbar-thumb { background: #b7dfd0; border-radius: 9999px; }
    html.dark ::-webkit-scrollbar-track { background: #09110e; }
    html.dark ::-webkit-scrollbar-thumb { background: #1e352d; border-radius: 9999px; }

    /* =========================================================
       LIGHT MODE (Soft Mint Radial Glow — Kembar Portal User)
       ========================================================= */
    html:not(.dark) body,
    html:not(.dark) .fi,
    html:not(.dark) .fi-body {
        background: radial-gradient(circle at 50% 0%, #e1f4ea 0%, #f3f8f5 48%, #f7faf9 100%) !important;
        color: #0f172a !important;
    }

    html:not(.dark) .fi-main { background: transparent !important; }
    html:not(.dark) .fi-header-heading { color: #0f172a !important; }
    html:not(.dark) .pa-brand-title { color: #064e3b !important; }
    html:not(.dark) .pa-brand-sub { color: #059669 !important; }

    html:not(.dark) .fi-topbar nav {
        background: rgba(255, 255, 255, 0.78) !important;
        backdrop-filter: blur(16px) !important;
        border-bottom: 1px solid rgba(16, 185, 129, 0.14) !important;
    }

    html:not(.dark) aside.fi-sidebar,
    html:not(.dark) .fi-sidebar {
        background: rgba(255, 255, 255, 0.88) !important;
        backdrop-filter: blur(14px) !important;
        border-right: 1px solid rgba(16, 185, 129, 0.14) !important;
    }

    html:not(.dark) .fi-sidebar-item-active > a,
    html:not(.dark) .fi-sidebar-item-active > button,
    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button {
        background-color: #e2f5ec !important;
        color: #065f46 !important;
        box-shadow: inset 0 0 0 1px rgba(16, 185, 129, 0.25) !important;
        font-weight: 700 !important;
        border-radius: 0.85rem !important;
    }

    html:not(.dark) .fi-sidebar-item-active svg,
    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #10b981 !important;
    }

    html:not(.dark) .fi-sidebar-item-button:not(.fi-sidebar-item-active) {
        color: #475569 !important;
        font-weight: 500 !important;
        border-radius: 0.85rem !important;
    }

    html:not(.dark) .fi-sidebar-item-button:not(.fi-sidebar-item-active):hover {
        background-color: rgba(226, 245, 236, 0.65) !important;
        color: #065f46 !important;
    }

    /* Light Mode Hero Banner & Cards */
    html:not(.dark) .pa-hero-banner {
        background: linear-gradient(135deg, #ffffff 0%, #f1fbf6 55%, #e5f6ed 100%) !important;
        border: 1px solid rgba(16, 185, 129, 0.22) !important;
        box-shadow: 0 14px 34px -8px rgba(16, 185, 129, 0.12) !important;
        color: #0f172a !important;
    }

    html:not(.dark) .pa-hero-banner .pa-hero-title { color: #0f172a !important; }
    html:not(.dark) .pa-hero-banner .pa-hero-highlight { color: #16a34a !important; }
    html:not(.dark) .pa-hero-banner .pa-hero-desc { color: #475569 !important; }

    html:not(.dark) .pa-hero-banner .pa-hero-pill {
        background: #fef9c3 !important;
        border: 1px solid #fde047 !important;
        color: #854d0e !important;
    }

    html:not(.dark) .pa-hero-banner .pa-hero-date {
        background: rgba(16, 185, 129, 0.1) !important;
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
        color: #065f46 !important;
    }

    html:not(.dark) .pa-hero-banner .pa-hero-secondary-btn {
        background: #ffffff !important;
        color: #065f46 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
    }

    html:not(.dark) .fi-section,
    html:not(.dark) .fi-ta-ctn,
    html:not(.dark) .fi-wi-stats-overview-stat,
    html:not(.dark) .pa-card {
        background: rgba(255, 255, 255, 0.94) !important;
        border: 1px solid rgba(16, 185, 129, 0.16) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 8px 24px -6px rgba(15, 23, 42, 0.05) !important;
        color: #0f172a !important;
    }

    html:not(.dark) .pa-sop-box {
        background: linear-gradient(135deg, #f0fdf4 0%, #fefce8 100%) !important;
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
    }

    html:not(.dark) .fi-ta-header-cell {
        background-color: rgba(240, 253, 244, 0.85) !important;
        color: #065f46 !important;
    }

    /* =========================================================
       DARK MODE (Deep Forest Radial Glow — Kembar Portal User)
       ========================================================= */
    html.dark body,
    html.dark .fi,
    html.dark .fi-body {
        background: radial-gradient(circle at 50% 0%, #152e24 0%, #0b1512 48%, #080f0d 100%) !important;
        color: #f1f5f9 !important;
    }

    html.dark .fi-main { background: transparent !important; }
    html.dark .fi-header-heading { color: #ffffff !important; }
    html.dark .pa-brand-title { color: #ffffff !important; }
    html.dark .pa-brand-sub { color: #34d399 !important; }

    html.dark .fi-topbar nav {
        background: rgba(12, 22, 18, 0.78) !important;
        backdrop-filter: blur(16px) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    html.dark aside.fi-sidebar,
    html.dark .fi-sidebar {
        background: rgba(11, 20, 17, 0.88) !important;
        backdrop-filter: blur(14px) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    html.dark .fi-sidebar-item-active > a,
    html.dark .fi-sidebar-item-active > button,
    html.dark .fi-sidebar-item-active .fi-sidebar-item-button {
        background: rgba(45, 138, 102, 0.24) !important;
        color: #6ee7b7 !important;
        box-shadow: inset 0 0 0 1px rgba(52, 211, 153, 0.32) !important;
        font-weight: 700 !important;
        border-radius: 0.85rem !important;
    }

    html.dark .fi-sidebar-item-active svg,
    html.dark .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #34d399 !important;
    }

    html.dark .fi-sidebar-item-button:not(.fi-sidebar-item-active) {
        color: #94a3b8 !important;
        border-radius: 0.85rem !important;
    }

    html.dark .fi-sidebar-item-button:not(.fi-sidebar-item-active):hover {
        background-color: rgba(255, 255, 255, 0.06) !important;
        color: #e2e8f0 !important;
    }

    /* Dark Mode Hero Banner & Cards */
    html.dark .pa-hero-banner {
        background: linear-gradient(135deg, rgba(21, 44, 35, 0.88) 0%, rgba(14, 28, 22, 0.94) 100%) !important;
        border: 1px solid rgba(52, 211, 153, 0.18) !important;
        box-shadow: 0 18px 38px -8px rgba(0, 0, 0, 0.5) !important;
        color: #ffffff !important;
    }

    html.dark .pa-hero-banner .pa-hero-title { color: #ffffff !important; }
    html.dark .pa-hero-banner .pa-hero-highlight { color: #fde047 !important; }
    html.dark .pa-hero-banner .pa-hero-desc { color: #cbd5e1 !important; }

    html.dark .pa-hero-banner .pa-hero-pill {
        background: rgba(250, 204, 21, 0.14) !important;
        border: 1px solid rgba(250, 204, 21, 0.35) !important;
        color: #fde047 !important;
    }

    html.dark .pa-hero-banner .pa-hero-date {
        background: rgba(255, 255, 255, 0.07) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        color: #e2e8f0 !important;
    }

    html.dark .pa-hero-banner .pa-hero-secondary-btn {
        background: rgba(255, 255, 255, 0.08) !important;
        color: #f1f5f9 !important;
        border: 1px solid rgba(255, 255, 255, 0.16) !important;
    }

    html.dark .fi-section,
    html.dark .fi-ta-ctn,
    html.dark .fi-wi-stats-overview-stat,
    html.dark .fi-modal-window,
    html.dark .pa-card {
        background: linear-gradient(145deg, rgba(20, 38, 31, 0.9) 0%, rgba(13, 25, 20, 0.96) 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.09) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.45) !important;
        color: #f1f5f9 !important;
    }

    html.dark .pa-card h3,
    html.dark .pa-card .pa-value { color: #ffffff !important; }
    html.dark .pa-card .pa-muted { color: #94a3b8 !important; }

    html.dark .pa-sop-box {
        background: rgba(10, 20, 16, 0.72) !important;
        border: 1px solid rgba(52, 211, 153, 0.18) !important;
        color: #cbd5e1 !important;
    }

    html.dark .fi-ta-header-cell {
        background-color: rgba(255, 255, 255, 0.04) !important;
        color: #94a3b8 !important;
    }

    /* =========================================================
       SPACING LEGA & ANTI-DEMPET (EXPLICIT LAYOUT SPACING)
       ========================================================= */
    .fi-main {
        padding: 2rem 2.25rem 3.5rem 2.25rem !important;
        max-width: 1380px !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .fi-wi {
        gap: 1.5rem !important;
    }

    .pa-hero-banner {
        padding: 2.25rem 2.5rem !important;
        border-radius: 1.5rem !important;
    }

    .pa-hero-top-row {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 0.75rem !important;
        margin-bottom: 1.15rem !important;
    }

    .pa-hero-title {
        font-size: 1.9rem !important;
        line-height: 1.25 !important;
        margin-bottom: 0.65rem !important;
    }

    .pa-hero-desc {
        font-size: 0.925rem !important;
        line-height: 1.65 !important;
        margin-bottom: 1.5rem !important;
    }

    .pa-hero-actions {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 0.85rem !important;
    }

    .pa-card {
        padding: 1.5rem 1.65rem !important;
    }

    .pa-sop-box {
        padding: 1.15rem 1.35rem !important;
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .pa-sop-box ol {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.55rem !important;
        margin-top: 0.65rem !important;
    }

    /* Pill Primary Button (Persis tombol "Jelajahi Seluruh Koleksi") */
    .fi-btn-color-primary:not(.fi-btn-outlined),
    .pa-pill-emerald {
        background: linear-gradient(135deg, #34a874 0%, #1e8256 100%) !important;
        color: #ffffff !important;
        border: 1px solid rgba(110, 231, 183, 0.35) !important;
        font-weight: 700 !important;
        border-radius: 9999px !important;
        padding: 0.65rem 1.4rem !important;
        box-shadow: 0 4px 16px rgba(30, 130, 86, 0.3) !important;
        transition: all 0.2s ease;
    }

    .fi-btn-color-primary:not(.fi-btn-outlined):hover,
    .pa-pill-emerald:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
    }

    .pa-hero-secondary-btn {
        border-radius: 9999px !important;
        padding: 0.65rem 1.4rem !important;
    }

    /* Pill Warning / Yellow Button (Persis tombol "Daftar") */
    .fi-btn-color-warning:not(.fi-btn-outlined),
    .pa-pill-yellow {
        background: linear-gradient(135deg, #fde047 0%, #facc15 100%) !important;
        color: #0f172a !important;
        border: 1px solid #fef08a !important;
        font-weight: 800 !important;
        border-radius: 9999px !important;
        box-shadow: 0 4px 14px rgba(250, 204, 21, 0.25) !important;
        transition: all 0.2s ease;
    }
</style>