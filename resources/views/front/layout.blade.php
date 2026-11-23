<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pustaka Akuntansi — Ruang Baca Jurusan Akuntansi Polines')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-hmj-akuntansi.jpg') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Pre-load theme & sidebar state to prevent layout/theme flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('perpus_theme');
            const systemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            if (localStorage.getItem('perpus_sidebar_collapsed') === '1') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                    }
                }
            }
        }
    </script>

    <style>
        /* ========================================================
           APPLE LIQUID GLASS DESIGN SYSTEM (LIGHT MODE)
           ======================================================== */
        :root {
            --app-bg: radial-gradient(1100px 650px at 85% -5%, rgba(52, 211, 153, 0.28), transparent 60%),
                      radial-gradient(900px 550px at 75% 12%, rgba(254, 240, 138, 0.22), transparent 55%),
                      radial-gradient(950px 600px at -5% 35%, rgba(167, 243, 208, 0.38), transparent 55%),
                      radial-gradient(1200px 750px at 65% 105%, rgba(110, 231, 183, 0.30), transparent 60%),
                      radial-gradient(850px 550px at 25% 75%, rgba(209, 250, 229, 0.45), transparent 55%),
                      linear-gradient(180deg, #f0fdf4 0%, #edf9f2 50%, #f4fbf7 100%);
        }

        body {
            background: var(--app-bg);
            background-attachment: fixed;
            color: #0f172a;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            overflow-x: hidden;
            transition: background 0.4s ease, color 0.3s ease;
        }

        /* Apple Liquid Glass Panels (Water-Clear Refraction + Specular Rim Lighting) */
        .glass-panel {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.64) 0%, rgba(240, 253, 244, 0.38) 50%, rgba(255, 255, 255, 0.56) 100%);
            backdrop-filter: blur(36px) saturate(220%) brightness(103%) contrast(102%);
            -webkit-backdrop-filter: blur(36px) saturate(220%) brightness(103%) contrast(102%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-top: 1.5px solid #ffffff;
            border-left: 1.2px solid rgba(255, 255, 255, 0.95);
            box-shadow: inset 0 1.5px 1px 0 #ffffff,
                        inset 0 -1px 1px 0 rgba(6, 78, 59, 0.05),
                        0 24px 60px -20px rgba(6, 78, 59, 0.16),
                        0 6px 18px -4px rgba(6, 78, 59, 0.07);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-topbar {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.68) 0%, rgba(240, 253, 244, 0.42) 50%, rgba(255, 255, 255, 0.68) 100%);
            backdrop-filter: blur(36px) saturate(220%) brightness(103%);
            -webkit-backdrop-filter: blur(36px) saturate(220%) brightness(103%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1.5px 1px 0 #ffffff,
                        0 16px 40px -16px rgba(6, 78, 59, 0.13),
                        0 4px 12px -4px rgba(6, 78, 59, 0.06);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card {
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.68) 0%, rgba(246, 252, 248, 0.38) 60%, rgba(255, 255, 255, 0.58) 100%);
            backdrop-filter: blur(32px) saturate(210%) brightness(102%);
            -webkit-backdrop-filter: blur(32px) saturate(210%) brightness(102%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-top: 1.5px solid #ffffff;
            border-left: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: inset 0 1.5px 1px 0 #ffffff,
                        inset 0 -1px 1px 0 rgba(6, 78, 59, 0.04),
                        0 20px 48px -18px rgba(6, 78, 59, 0.12),
                        0 4px 14px -3px rgba(6, 78, 59, 0.04);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card-hover:hover {
            transform: translateY(-3px);
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 26px 54px -16px rgba(6, 78, 59, 0.20);
        }

        .pearl-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1px 1px 0 #ffffff, 0 4px 12px -4px rgba(6, 78, 59, 0.08);
            transition: all 0.3s ease;
        }

        .glass-field {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.52) 0%, rgba(240, 249, 244, 0.40) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-top: 1px solid rgba(255, 255, 255, 0.7);
            border-bottom: 1.5px solid #ffffff;
            box-shadow: inset 0 1.5px 3px 0 rgba(6, 78, 59, 0.06),
                        inset 0 0 0 1px rgba(255, 255, 255, 0.65),
                        0 2px 8px -2px rgba(6, 78, 59, 0.05);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-field:focus-within {
            background: rgba(255, 255, 255, 0.88);
            border-color: #34d399;
            border-top-color: #6ee7b7;
            box-shadow: inset 0 1px 2px 0 rgba(6, 78, 59, 0.04),
                        0 0 0 3px rgba(16, 185, 129, 0.22);
        }

        .segment-track {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.55) 0%, rgba(240, 248, 244, 0.38) 100%);
            backdrop-filter: blur(24px) saturate(200%);
            -webkit-backdrop-filter: blur(24px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1.5px 2px 0 rgba(6, 78, 59, 0.05),
                        inset 0 0 0 1px rgba(255, 255, 255, 0.6),
                        0 4px 16px -4px rgba(6, 78, 59, 0.08);
            padding: 5px;
            transition: all 0.3s ease;
        }

        /* Apple Emerald Liquid Glass Pill Button (High Gloss Specular Gemstone with Golden Warm Rim) */
        .btn-emerald {
            background: linear-gradient(180deg, 
                rgba(52, 211, 153, 0.95) 0%, 
                rgba(16, 185, 129, 0.90) 45%, 
                rgba(5, 150, 105, 0.96) 100%) !important;
            backdrop-filter: blur(20px) saturate(200%);
            -webkit-backdrop-filter: blur(20px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.45) !important;
            border-top: 1.5px solid rgba(254, 240, 138, 0.85) !important;
            box-shadow: inset 0 1.5px 1.5px 0 rgba(254, 240, 138, 0.7),
                        inset 0 -1.5px 1px 0 rgba(0, 0, 0, 0.2),
                        0 12px 28px -6px rgba(16, 185, 129, 0.5),
                        0 0 12px -2px rgba(234, 179, 8, 0.2) !important;
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
            position: relative;
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-emerald::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.45) 0%, rgba(255, 255, 255, 0.02) 100%);
            border-radius: inherit;
            pointer-events: none;
        }

        .btn-emerald:hover {
            filter: brightness(1.08);
            transform: translateY(-1.5px);
            box-shadow: inset 0 1.5px 1.5px 0 #ffffff,
                        inset 0 -1.5px 1px 0 rgba(0, 0, 0, 0.2),
                        0 16px 36px -6px rgba(16, 185, 129, 0.65),
                        0 0 18px -2px rgba(234, 179, 8, 0.35) !important;
        }

        .btn-emerald:active {
            transform: translateY(0) scale(0.98);
            box-shadow: inset 0 1.5px 2px 0 rgba(0, 0, 0, 0.2),
                        0 6px 16px -4px rgba(5, 150, 105, 0.4) !important;
        }

        /* Apple Liquid Glass Neutral Pill Button */
        .btn-glass {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.75) 0%, rgba(240, 253, 244, 0.45) 100%);
            backdrop-filter: blur(24px) saturate(200%);
            -webkit-backdrop-filter: blur(24px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1.5px 1px 0 #ffffff,
                        inset 0 -1px 1px 0 rgba(6, 78, 59, 0.04),
                        0 8px 20px -8px rgba(6, 78, 59, 0.12);
            color: #0f172a;
            position: relative;
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-glass::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.55) 0%, rgba(255, 255, 255, 0.02) 100%);
            border-radius: inherit;
            pointer-events: none;
        }

        .btn-glass:hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.90) 0%, rgba(240, 253, 244, 0.65) 100%);
            border-color: rgba(52, 211, 153, 0.6);
            border-top-color: #ffffff;
            transform: translateY(-1px);
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 10px 22px -6px rgba(6, 78, 59, 0.16);
        }

        /* Liquid Glass Category Chips & Filter Pills */
        .liquid-chip {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.72) 0%, rgba(240, 253, 244, 0.42) 100%);
            backdrop-filter: blur(20px) saturate(200%);
            -webkit-backdrop-filter: blur(20px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1px 1px 0 #ffffff,
                        0 4px 12px -3px rgba(6, 78, 59, 0.08);
            color: #1e293b;
            position: relative;
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .liquid-chip::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.02) 100%);
            border-radius: inherit;
            pointer-events: none;
        }

        .liquid-chip:hover {
            background: linear-gradient(180deg, rgba(209, 250, 229, 0.90) 0%, rgba(167, 243, 208, 0.70) 100%);
            border-color: rgba(52, 211, 153, 0.75);
            border-top-color: #ffffff;
            color: #064e3b;
            transform: translateY(-1.5px);
            box-shadow: inset 0 1px 1px 0 #ffffff, 0 8px 22px -4px rgba(16, 185, 129, 0.32);
        }

        /* Liquid Glass Icon Buttons (Theme toggle, sidebar toggle, etc.) */
        .liquid-icon-btn {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.78) 0%, rgba(240, 253, 244, 0.50) 100%);
            backdrop-filter: blur(24px) saturate(200%);
            -webkit-backdrop-filter: blur(24px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 4px 14px -4px rgba(6, 78, 59, 0.12);
            color: #334155;
            position: relative;
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .liquid-icon-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.6) 0%, transparent 100%);
            border-radius: inherit;
            pointer-events: none;
        }

        .liquid-icon-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.04);
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 8px 18px -4px rgba(6, 78, 59, 0.18);
        }

        /* Liquid Glass Emerald Badge */
        .liquid-badge-emerald {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.20) 0%, rgba(5, 150, 105, 0.08) 100%);
            backdrop-filter: blur(16px) saturate(200%);
            -webkit-backdrop-filter: blur(16px) saturate(200%);
            border: 1px solid rgba(52, 211, 153, 0.45);
            border-top: 1.5px solid rgba(110, 231, 183, 0.9);
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.7), 0 4px 12px -3px rgba(5, 150, 105, 0.15);
            color: #064e3b;
            position: relative;
            overflow: hidden;
        }

        .liquid-badge-emerald::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.45) 0%, transparent 100%);
            border-radius: inherit;
            pointer-events: none;
        }

        /* ========================================================
           APPLE LIQUID BRIGHT LEMON-YELLOW (Light, Sunny, Non-Dark)
           ======================================================== */
        .btn-yellow,
        .btn-amber-gold {
            background: linear-gradient(180deg, 
                #ffffff 0%, 
                #fef9c3 30%, 
                #fef08a 68%, 
                #fde047 100%) !important;
            backdrop-filter: blur(20px) saturate(200%);
            -webkit-backdrop-filter: blur(20px) saturate(200%);
            border: 1px solid rgba(250, 204, 21, 0.45) !important;
            border-top: 1.5px solid #ffffff !important;
            box-shadow: inset 0 1.5px 1.5px 0 #ffffff,
                        inset 0 -1px 1px 0 rgba(161, 98, 7, 0.08),
                        0 6px 16px -4px rgba(250, 204, 21, 0.35) !important;
            color: #1e293b !important;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-yellow::before,
        .btn-amber-gold::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.05) 100%);
            border-radius: inherit;
            pointer-events: none;
        }

        .btn-yellow:hover,
        .btn-amber-gold:hover {
            filter: brightness(1.04);
            transform: translateY(-1.5px);
            box-shadow: inset 0 1.5px 1.5px 0 #ffffff,
                        0 10px 22px -4px rgba(250, 204, 21, 0.45) !important;
        }

        .btn-yellow:active,
        .btn-amber-gold:active {
            transform: translateY(0) scale(0.98);
        }

        /* Apple Liquid Bright Yellow Pill Badge */
        .liquid-badge-amber {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(254, 249, 195, 0.80) 100%);
            backdrop-filter: blur(18px) saturate(200%);
            -webkit-backdrop-filter: blur(18px) saturate(200%);
            border: 1px solid rgba(250, 204, 21, 0.4);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1px 1px 0 #ffffff,
                        0 4px 12px -3px rgba(250, 204, 21, 0.2);
            color: #854d0e;
            position: relative;
            overflow: hidden;
        }

        .liquid-badge-amber::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.85) 0%, rgba(255, 255, 255, 0) 100%);
            border-radius: inherit;
            pointer-events: none;
        }

        /* Active Navigation item in Light Mode */
        nav a.bg-white\/95 {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(240, 253, 244, 0.82) 100%) !important;
            border: 1px solid rgba(167, 243, 208, 0.85) !important;
            border-top: 1.5px solid #ffffff !important;
            box-shadow: inset 0 1.5px 1.5px 0 #ffffff, 0 4px 14px -4px rgba(6, 78, 59, 0.16) !important;
            color: #065f46 !important;
            position: relative;
            overflow: hidden;
        }

        nav a.bg-white\/95::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.55) 0%, transparent 100%);
            border-radius: inherit;
            pointer-events: none;
        }

        nav a:not(.bg-white\/95):hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.70) 0%, rgba(240, 253, 244, 0.40) 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            border-top: 1.5px solid #ffffff !important;
            box-shadow: inset 0 1px 1px 0 #ffffff, 0 4px 12px -4px rgba(6, 78, 59, 0.08) !important;
            color: #064e3b !important;
        }

        .segment-track a.bg-white {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(240, 253, 244, 0.85) 100%) !important;
            border: 1px solid rgba(167, 243, 208, 0.9) !important;
            border-top: 1.5px solid #ffffff !important;
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 4px 14px -4px rgba(6, 78, 59, 0.16) !important;
            color: #065f46 !important;
        }

        nav a:not(.bg-white\/95):hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.70) 0%, rgba(240, 253, 244, 0.40) 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            border-top: 1.5px solid #ffffff !important;
            box-shadow: inset 0 1px 1px 0 #ffffff, 0 4px 12px -4px rgba(6, 78, 59, 0.08) !important;
            color: #064e3b !important;
        }

        .segment-track a.bg-white {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(240, 253, 244, 0.85) 100%) !important;
            border: 1px solid rgba(167, 243, 208, 0.9) !important;
            border-top: 1.5px solid #ffffff !important;
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 4px 14px -4px rgba(6, 78, 59, 0.16) !important;
            color: #065f46 !important;
        }

        .book-shadow {
            box-shadow: 0 14px 30px -14px rgba(15, 23, 42, 0.38), 0 2px 6px rgba(15, 23, 42, 0.1);
        }

        /* Generated covers */
        .cover-art-0 { background: linear-gradient(160deg, #1e293b, #0f172a); color: #f8fafc; }
        .cover-art-1 { background: linear-gradient(160deg, #065f46, #022c22); color: #f0fdf4; }
        .cover-art-2 { background: linear-gradient(160deg, #9a3412, #431407); color: #fff7ed; }
        .cover-art-3 { background: linear-gradient(160deg, #1e3a8a, #172554); color: #eff6ff; }
        .cover-art-4 { background: linear-gradient(160deg, #831843, #500724); color: #fdf2f8; }
        .cover-art-5 { background: linear-gradient(160deg, #374151, #111827); color: #f9fafb; }

        @keyframes riseIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-rise {
            animation: riseIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* COLLAPSIBLE SIDEBAR RULES */
        .sidebar-transition {
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                        left 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                        padding 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                        opacity 0.2s ease;
        }

        html.sidebar-collapsed #appSidebar,
        body.sidebar-collapsed #appSidebar {
            width: 76px !important;
            padding-left: 0.625rem !important;
            padding-right: 0.625rem !important;
        }

        html.sidebar-collapsed #appSidebar .sidebar-text,
        body.sidebar-collapsed #appSidebar .sidebar-text {
            display: none !important;
        }

        html.sidebar-collapsed #appSidebar .sidebar-header,
        body.sidebar-collapsed #appSidebar .sidebar-header {
            flex-direction: column !important;
            gap: 10px !important;
            align-items: center !important;
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        html.sidebar-collapsed #appSidebar nav a,
        body.sidebar-collapsed #appSidebar nav a {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            width: 48px !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        html.sidebar-collapsed #appSidebar .sidebar-badge,
        body.sidebar-collapsed #appSidebar .sidebar-badge {
            position: absolute !important;
            top: 2px !important;
            right: 2px !important;
            height: 16px !important;
            min-width: 16px !important;
            padding: 0 4px !important;
            font-size: 10px !important;
        }

        html.sidebar-collapsed #appHeader,
        body.sidebar-collapsed #appHeader {
            left: 116px !important;
        }

        html.sidebar-collapsed #appMain,
        body.sidebar-collapsed #appMain {
            padding-left: 116px !important;
        }

        /* ========================================================
           APPLE DARK LIQUID GLASS MODE (GENUINE LIQUID TRANSLUCENCY)
           ======================================================== */
        html.dark {
            color-scheme: dark;
        }

        html.dark body {
            background: radial-gradient(1200px 700px at 75% -10%, rgba(16, 185, 129, 0.14), transparent 60%),
                        radial-gradient(900px 550px at 75% 15%, rgba(245, 158, 11, 0.08), transparent 55%),
                        radial-gradient(1100px 650px at -10% 40%, rgba(20, 184, 166, 0.10), transparent 60%),
                        radial-gradient(1100px 800px at 50% 100%, rgba(5, 150, 105, 0.14), transparent 65%),
                        linear-gradient(180deg, #06110c 0%, #07150f 50%, #050f0a 100%) !important;
            background-attachment: fixed !important;
            color: #f1f5f9 !important;
        }

        /* Liquid Glass Panels in Dark Mode (Crystal Translucency + Specular Highlights) */
        html.dark .glass-panel {
            background: linear-gradient(170deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 60%, rgba(16, 185, 129, 0.03) 100%),
                        rgba(10, 22, 17, 0.55) !important;
            backdrop-filter: blur(32px) saturate(200%);
            -webkit-backdrop-filter: blur(32px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: inset 0 1.5px 1px 0 rgba(255, 255, 255, 0.25),
                        inset 0 -1px 1px 0 rgba(0, 0, 0, 0.3),
                        0 24px 60px -20px rgba(0, 0, 0, 0.7) !important;
        }

        html.dark .glass-topbar {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.09) 0%, rgba(255, 255, 255, 0.03) 50%, rgba(255, 255, 255, 0.09) 100%),
                        rgba(10, 22, 17, 0.55) !important;
            backdrop-filter: blur(32px) saturate(200%);
            -webkit-backdrop-filter: blur(32px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: inset 0 1.5px 1px 0 rgba(255, 255, 255, 0.28),
                        0 20px 50px -15px rgba(0, 0, 0, 0.65) !important;
        }

        html.dark .glass-card {
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.07) 0%, rgba(255, 255, 255, 0.02) 60%, rgba(16, 185, 129, 0.03) 100%),
                        rgba(11, 23, 18, 0.5) !important;
            backdrop-filter: blur(24px) saturate(190%);
            -webkit-backdrop-filter: blur(24px) saturate(190%);
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.25) !important;
            box-shadow: inset 0 1.5px 1px 0 rgba(255, 255, 255, 0.2),
                        0 20px 50px -20px rgba(0, 0, 0, 0.6) !important;
        }

        html.dark .glass-card-hover:hover {
            box-shadow: inset 0 1.5px 1px 0 rgba(255, 255, 255, 0.3), 0 24px 60px -16px rgba(0, 0, 0, 0.8) !important;
            border-color: rgba(52, 211, 153, 0.35) !important;
        }

        html.dark .pearl-card {
            background: rgba(14, 24, 20, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.25) !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4) !important;
        }

        html.dark .glass-field {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.3) 0%, rgba(255, 255, 255, 0.02) 100%),
                        rgba(8, 18, 14, 0.55) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            box-shadow: inset 0 1.5px 3px 0 rgba(0, 0, 0, 0.4),
                        inset 0 -1px 0 0 rgba(255, 255, 255, 0.08) !important;
            color: #f1f5f9 !important;
        }

        html.dark .glass-field input,
        html.dark .glass-field select {
            color: #f8fafc !important;
        }

        html.dark .glass-field input::placeholder {
            color: #64748b !important;
        }

        html.dark .glass-field:focus-within {
            background: rgba(12, 26, 20, 0.8) !important;
            border-color: #34d399 !important;
            border-top-color: #6ee7b7 !important;
            box-shadow: inset 0 1px 2px 0 rgba(0, 0, 0, 0.3),
                        0 0 0 3px rgba(16, 185, 129, 0.25) !important;
        }

        html.dark .segment-track {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.3) 0%, rgba(255, 255, 255, 0.02) 100%),
                        rgba(8, 18, 14, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: inset 0 1.5px 3px 0 rgba(0, 0, 0, 0.3) !important;
        }

        html.dark .btn-glass {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0.04) 100%) !important;
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.35) !important;
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.3),
                        0 6px 16px -6px rgba(0, 0, 0, 0.4) !important;
            color: #f8fafc !important;
        }

        html.dark .btn-glass:hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.08) 100%) !important;
            border-color: rgba(255, 255, 255, 0.28) !important;
            border-top-color: rgba(255, 255, 255, 0.5) !important;
            color: #ffffff !important;
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.45), 0 8px 22px -6px rgba(0, 0, 0, 0.6) !important;
        }

        html.dark .liquid-icon-btn {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0.04) 100%) !important;
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.16) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.35) !important;
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.35), 0 4px 12px -4px rgba(0, 0, 0, 0.4) !important;
        }

        html.dark .liquid-icon-btn:hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.08) 100%) !important;
            border-color: rgba(255, 255, 255, 0.28) !important;
            border-top-color: rgba(255, 255, 255, 0.5) !important;
        }

        html.dark .liquid-chip {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.11) 0%, rgba(255, 255, 255, 0.03) 100%) !important;
            backdrop-filter: blur(14px) saturate(180%);
            -webkit-backdrop-filter: blur(14px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.13) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.25), 0 4px 12px -4px rgba(0, 0, 0, 0.35) !important;
            color: #e2e8f0 !important;
        }

        html.dark .liquid-chip:hover {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.28) 0%, rgba(5, 150, 105, 0.14) 100%) !important;
            border-color: rgba(52, 211, 153, 0.45) !important;
            border-top-color: rgba(110, 231, 183, 0.7) !important;
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.45), 0 8px 20px -4px rgba(16, 185, 129, 0.4) !important;
            color: #6ee7b7 !important;
        }

        html.dark .liquid-badge-emerald {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.25) 0%, rgba(5, 150, 105, 0.12) 100%) !important;
            border: 1px solid rgba(52, 211, 153, 0.35) !important;
            border-top: 1.5px solid rgba(110, 231, 183, 0.65) !important;
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.35), 0 4px 12px -4px rgba(16, 185, 129, 0.25) !important;
            color: #6ee7b7 !important;
        }

        /* Liquid Bright Lemon-Yellow in Dark Mode */
        html.dark .btn-yellow,
        html.dark .btn-amber-gold {
            background: linear-gradient(180deg, 
                #ffffff 0%, 
                #fef9c3 30%, 
                #fef08a 68%, 
                #fde047 100%) !important;
            border: 1px solid rgba(254, 240, 138, 0.7) !important;
            border-top: 1.5px solid #ffffff !important;
            box-shadow: inset 0 1.5px 1px 0 rgba(255, 255, 255, 0.6),
                        0 8px 20px -4px rgba(250, 204, 21, 0.4) !important;
            color: #0f172a !important;
        }

        html.dark .liquid-badge-amber {
            background: linear-gradient(180deg, rgba(254, 249, 195, 0.22) 0%, rgba(250, 204, 21, 0.12) 100%) !important;
            border: 1px solid rgba(254, 240, 138, 0.45) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.6) !important;
            box-shadow: inset 0 1px 1px 0 rgba(254, 240, 138, 0.3),
                        0 4px 14px -3px rgba(250, 204, 21, 0.2) !important;
            color: #fef08a !important;
        }

        /* Typography Adapters */
        html.dark .text-slate-900 { color: #f8fafc !important; }
        html.dark .text-slate-800 { color: #f1f5f9 !important; }
        html.dark .text-slate-700 { color: #cbd5e1 !important; }
        html.dark .text-slate-600 { color: #94a3b8 !important; }
        html.dark .text-slate-500 { color: #94a3b8 !important; }
        html.dark .text-slate-400 { color: #64748b !important; }

        /* Emerald Accents in Dark Mode */
        html.dark .text-emerald-800,
        html.dark .text-emerald-900,
        html.dark .text-emerald-700 {
            color: #6ee7b7 !important;
        }

        /* Active Nav & Segment items in Dark Mode (Liquid Pill) */
        html.dark nav a.bg-white\/95,
        html.dark .segment-track a.bg-white {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.35) 0%, rgba(5, 150, 105, 0.2) 100%) !important;
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(52, 211, 153, 0.45) !important;
            border-top: 1.5px solid rgba(110, 231, 183, 0.75) !important;
            box-shadow: inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.45),
                        0 8px 22px -6px rgba(16, 185, 129, 0.45) !important;
            color: #6ee7b7 !important;
        }
        html.dark nav a.bg-white\/95 svg,
        html.dark .segment-track a.bg-white svg {
            color: #34d399 !important;
        }

        html.dark nav a:not(.bg-white\/95) {
            color: #94a3b8 !important;
        }
        html.dark nav a:not(.bg-white\/95):hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%) !important;
            color: #f8fafc !important;
        }

        /* Dropdown Menu in Dark Mode */
        html.dark #userMenuDropdown {
            background: linear-gradient(160deg, rgba(22, 38, 30, 0.95) 0%, rgba(14, 26, 20, 0.96) 100%) !important;
            backdrop-filter: blur(28px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.14) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: inset 0 1.5px 1px 0 rgba(255, 255, 255, 0.25), 0 25px 60px -15px rgba(0, 0, 0, 0.8) !important;
        }
        html.dark #userMenuDropdown a:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Table Rows */
        html.dark tr:hover {
            background-color: rgba(255, 255, 255, 0.04) !important;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 dark:text-slate-100 antialiased selection:bg-emerald-500/20 selection:text-emerald-400 relative">

    <!-- FLOATING GLASS SIDEBAR (Desktop) -->
    <aside id="appSidebar" class="glass-panel sidebar-transition fixed bottom-6 left-6 top-6 z-30 hidden w-[250px] flex-col rounded-[28px] px-4 py-6 lg:flex">
        <!-- Sidebar Header with Official HMJ Logo & Apple Collapse Toggle -->
        <div class="sidebar-header flex items-center justify-between px-2">
            <a href="{{ route('beranda') }}" class="flex items-center gap-3 transition-opacity hover:opacity-85">
                <!-- Official HMJ Akuntansi Polines Logo Container -->
                <div class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-white p-1 shadow-md shadow-emerald-950/20 border border-white/60 dark:border-white/20 overflow-hidden">
                    <img src="{{ asset('images/logo-hmj-akuntansi.jpg') }}" alt="Logo Pustaka Akuntansi" class="h-full w-full object-contain">
                </div>
                <div class="sidebar-text">
                    <span class="block text-[17px] font-bold leading-tight tracking-tight text-slate-900">Pustaka</span>
                    <span class="block text-[17px] font-bold leading-tight tracking-tight text-emerald-800">Akuntansi</span>
                </div>
            </a>

            <!-- Collapse / Expand Button (Apple Liquid Glass Toggle) -->
            <button id="sidebarToggleBtn" type="button" onclick="toggleSidebarCollapse()" class="liquid-icon-btn grid h-8 w-8 place-items-center rounded-xl text-slate-500 dark:text-slate-300" title="Kecilkan / Perluas Sidebar">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-9 flex flex-col gap-2">
            <!-- Beranda -->
            <a href="{{ route('beranda') }}"
               title="Beranda"
               class="relative flex h-11 items-center gap-3.5 rounded-full px-4 text-[15px] transition-all duration-200 {{ request()->routeIs('beranda') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('beranda') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="sidebar-text flex-1">Beranda</span>
            </a>

            <!-- Katalog -->
            <a href="{{ route('katalog') }}"
               title="Katalog Buku"
               class="relative flex h-11 items-center gap-3.5 rounded-full px-4 text-[15px] transition-all duration-200 {{ request()->routeIs('katalog') || request()->routeIs('buku.detail') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('katalog') || request()->routeIs('buku.detail') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                <span class="sidebar-text flex-1">Katalog</span>
            </a>

            <!-- Tata Tertib & Ketentuan Layanan -->
            <a href="{{ route('tata-tertib') }}"
               title="Tata Tertib"
               class="relative flex h-11 items-center gap-3.5 rounded-full px-4 text-[15px] transition-all duration-200 {{ request()->routeIs('tata-tertib') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('tata-tertib') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span class="sidebar-text flex-1">Tata Tertib</span>
            </a>

            @if(session('student_id'))
                @php
                    $sidebarStudent = \App\Models\Anggota::find(session('student_id'));
                    $activeBadgeCount = $sidebarStudent ? \App\Models\Loan::where(function($q) use ($sidebarStudent) {
                        $q->where('member_nim', $sidebarStudent->nim_no_anggota)->orWhere('anggota_id', $sidebarStudent->id);
                    })->whereIn('status', ['booked', 'dipinjam', 'terlambat'])->count() : 0;
                @endphp

                <!-- Buku Favorit -->
                <a href="{{ route('favorit') }}"
                   title="Buku Favorit"
                   class="relative flex h-11 items-center gap-3.5 rounded-full px-4 text-[15px] transition-all duration-200 {{ request()->routeIs('favorit') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('favorit') ? 'text-amber-500 fill-amber-500' : 'text-slate-500' }}" fill="{{ request()->routeIs('favorit') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                    <span class="sidebar-text flex-1">Buku Favorit</span>
                </a>

                <!-- Pinjaman Saya -->
                <a href="{{ route('pinjaman') }}"
                   title="Pinjaman Saya"
                   class="relative flex h-11 items-center gap-3.5 rounded-full px-4 text-[15px] transition-all duration-200 {{ request()->routeIs('pinjaman') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('pinjaman') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                    <span class="sidebar-text flex-1">Pinjaman Saya</span>
                    @if($activeBadgeCount > 0)
                        <span class="sidebar-badge grid h-6 min-w-6 place-items-center rounded-full bg-amber-400 text-amber-950 px-2 text-[12px] font-bold shadow-sm">
                            {{ $activeBadgeCount }}
                        </span>
                    @endif
                </a>

                <!-- Riwayat -->
                <a href="{{ route('riwayat') }}"
                   title="Riwayat"
                   class="relative flex h-11 items-center gap-3.5 rounded-full px-4 text-[15px] transition-all duration-200 {{ request()->routeIs('riwayat') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                    <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('riwayat') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="sidebar-text flex-1">Riwayat</span>
                </a>
            @endif
        </nav>

        @if(session('student_id'))
            <!-- Bottom Widget in Sidebar -->
            <div class="mt-auto pt-4 border-t border-slate-200/50 dark:border-white/10">
                <a href="{{ route('keluar') }}"
                   title="Keluar"
                   class="flex h-11 items-center gap-3 rounded-full px-4 text-[15px] text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition-colors hover:bg-rose-50 dark:hover:bg-rose-950/30">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <span class="sidebar-text">Keluar</span>
                </a>
            </div>
        @endif
    </aside>

    <!-- FLOATING GLASS TOPBAR -->
    <header id="appHeader" class="glass-topbar sidebar-transition fixed left-4 right-4 top-4 z-20 flex h-[72px] items-center gap-3 sm:gap-4 rounded-[26px] px-3 sm:px-4 lg:left-[300px] lg:right-6 lg:top-6 lg:px-6">
        <!-- Mobile Logo Icon -->
        <a href="{{ route('beranda') }}" class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-white p-1 lg:hidden shadow-sm border border-slate-200/60 dark:border-white/20 overflow-hidden">
            <img src="{{ asset('images/logo-hmj-akuntansi.jpg') }}" alt="Logo Pustaka Akuntansi" class="h-full w-full object-contain">
        </a>

        <!-- Liquid Glass Search Capsule -->
        <form action="{{ route('katalog') }}" method="GET" class="mx-auto w-full max-w-[580px]">
            <div class="glass-field flex h-11 sm:h-12 items-center gap-3 rounded-full px-4">
                <svg class="h-4 sm:h-5 w-4 sm:w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari judul, penulis, atau ISBN..."
                    class="w-full bg-transparent text-[14px] sm:text-[15px] font-medium outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500"
                >
            </div>
        </form>

        <!-- Right Side: Dark Mode Toggle + User Avatar / Login Pill -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- DARK MODE LIQUID GLASS TOGGLE BUTTON -->
            <button type="button"
                    onclick="toggleDarkMode()"
                    class="liquid-icon-btn grid h-10 w-10 shrink-0 place-items-center rounded-full text-slate-600 dark:text-amber-300"
                    title="Ganti Mode Gelap / Terang">
                <!-- Sun icon (shown when dark) -->
                <svg class="hidden dark:block h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                </svg>
                <!-- Moon icon (shown when light) -->
                <svg class="block dark:hidden h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                </svg>
            </button>

            <!-- Notification Bell with Apple Liquid Glass Popover (iPusnas style) -->
            @php
                $loggedStudent = session('student_id') ? \App\Models\Anggota::find(session('student_id')) : null;
                $headerNotifs = [];
                $unreadAlertCount = 0;

                if ($loggedStudent) {
                    $activeLoans = \App\Models\Loan::with('book')
                        ->where(function($q) use ($loggedStudent) {
                            $q->where('anggota_id', $loggedStudent->id)
                              ->orWhere('member_nim', $loggedStudent->nim_no_anggota);
                        })
                        ->whereIn('status', ['dipinjam', 'terlambat', 'booked'])
                        ->get();

                    foreach ($activeLoans as $loanItem) {
                        if ($loanItem->status === 'terlambat' || ($loanItem->jatuh_tempo && today()->gt($loanItem->jatuh_tempo->startOfDay()))) {
                            $diff = abs((int) $loanItem->jatuh_tempo->startOfDay()->diffInDays(today()));
                            if ($diff === 0) $diff = 1;
                            $headerNotifs[] = [
                                'badge' => 'Terlambat',
                                'badge_class' => 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400',
                                'title' => 'Peringatan Terlambat Pengembalian',
                                'message' => 'Buku "' . ($loanItem->book?->judul ?? 'Buku') . '" terlambat ' . $diff . ' hari. Mohon segera kembalikan ke perpustakaan.',
                                'time' => 'Batas: ' . ($loanItem->jatuh_tempo?->format('d M Y') ?? '-'),
                                'url' => route('pinjaman', ['tab' => 'dipinjam']),
                            ];
                            $unreadAlertCount++;
                        } elseif ($loanItem->status === 'dipinjam' && $loanItem->jatuh_tempo) {
                            $diffToDue = (int) today()->diffInDays($loanItem->jatuh_tempo->startOfDay(), false);
                            if ($diffToDue >= 0 && $diffToDue <= 3) {
                                $headerNotifs[] = [
                                    'badge' => 'Segera Kembali',
                                    'badge_class' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-400',
                                    'title' => 'Batas Pengembalian Mendekat',
                                    'message' => 'Buku "' . ($loanItem->book?->judul ?? 'Buku') . '" harus dikembalikan dalam ' . max(0, $diffToDue) . ' hari.',
                                    'time' => 'Batas: ' . $loanItem->jatuh_tempo->format('d M Y'),
                                    'url' => route('pinjaman', ['tab' => 'dipinjam']),
                                ];
                                $unreadAlertCount++;
                            }
                        } elseif ($loanItem->status === 'booked') {
                            $headerNotifs[] = [
                                'badge' => 'Siap Diambil',
                                'badge_class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400',
                                'title' => 'Booking Siap Diambil di Ruang Baca',
                                'message' => 'Buku "' . ($loanItem->book?->judul ?? 'Buku') . '" siap diambil. Kode booking: ' . ($loanItem->booking_code ?? 'AKT-BOOK'),
                                'time' => 'Maks. 1x24 jam',
                                'url' => route('pinjaman', ['tab' => 'booked']),
                            ];
                            $unreadAlertCount++;
                        }
                    }
                }

                // General announcement notice
                $headerNotifs[] = [
                    'badge' => 'Info Layanan',
                    'badge_class' => 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300',
                    'title' => 'Jam Pelayanan Ruang Baca',
                    'message' => 'Senin – Kamis 08.30 – 15.30 WIB, Jumat 08.30 – 14.00 WIB. Maksimal peminjaman 2 buku per mahasiswa.',
                    'time' => 'Informasi Resmi',
                    'url' => route('tata-tertib'),
                ];
            @endphp

            <div class="relative" id="notifMenuWrapper">
                <button type="button"
                        onclick="document.getElementById('notifDropdown').classList.toggle('hidden')"
                        class="liquid-icon-btn relative grid h-10 w-10 place-items-center rounded-full text-slate-600 dark:text-slate-300"
                        title="Notifikasi">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    @if($unreadAlertCount > 0)
                        <span class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] font-bold text-white shadow-sm ring-2 ring-white dark:ring-slate-900 animate-pulse">
                            {{ $unreadAlertCount }}
                        </span>
                    @endif
                </button>

                <!-- Solid-Tinted High Readability Dropdown (iPusnas style) -->
                <div id="notifDropdown" class="absolute right-0 mt-2 hidden w-[340px] sm:w-[400px] rounded-[28px] p-5 shadow-2xl z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-2xl border border-white/90 dark:border-slate-700/80 ring-1 ring-black/5">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/80 dark:border-slate-700/80">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">Notifikasi</span>
                            @if($unreadAlertCount > 0)
                                <span class="rounded-full bg-rose-100 dark:bg-rose-950/70 px-2.5 py-0.5 text-[10px] font-bold text-rose-700 dark:text-rose-300">
                                    {{ $unreadAlertCount }} baru
                                </span>
                            @endif
                        </div>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold">Pustaka Akuntansi</span>
                    </div>

                    <div class="max-h-[340px] overflow-y-auto space-y-2.5 pr-1">
                        @foreach($headerNotifs as $notif)
                            <a href="{{ $notif['url'] }}" class="group block rounded-2xl p-3.5 bg-slate-50/95 dark:bg-slate-800/95 hover:bg-emerald-50/90 dark:hover:bg-slate-700/90 transition-all border border-slate-200/80 dark:border-slate-700/80 shadow-2xs">
                                <div class="flex items-start justify-between gap-2 mb-1.5">
                                    <span class="text-[10px] font-bold uppercase tracking-wider rounded-full px-2.5 py-0.5 {{ $notif['badge_class'] }}">
                                        {{ $notif['badge'] }}
                                    </span>
                                    <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400">{{ $notif['time'] }}</span>
                                </div>
                                <p class="text-[13px] font-bold text-slate-900 dark:text-white group-hover:text-emerald-800 dark:group-hover:text-emerald-400 transition-colors leading-snug">
                                    {{ $notif['title'] }}
                                </p>
                                <p class="mt-1 text-[12px] text-slate-700 dark:text-slate-200 leading-relaxed">
                                    {{ $notif['message'] }}
                                </p>
                            </a>
                        @endforeach
                    </div>

                    @if($loggedStudent)
                        <div class="pt-3 mt-3 border-t border-slate-200/80 dark:border-slate-700/80 text-center">
                            <a href="{{ route('pinjaman') }}" class="text-xs font-bold text-emerald-800 dark:text-emerald-400 hover:underline">
                                Buka Pinjaman Saya &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if(session('student_id'))
                <!-- User Profile Liquid Glass Capsule -->
                <div class="relative" id="userMenuWrapper">
                    <button type="button" onclick="document.getElementById('userMenuDropdown').classList.toggle('hidden')" class="btn-glass flex h-10 sm:h-11 items-center gap-2 rounded-full px-3 sm:px-3.5 text-[13px] sm:text-[14px] font-semibold">
                        <span class="grid h-6 w-6 sm:h-7 sm:w-7 place-items-center rounded-full bg-emerald-700 text-xs font-bold text-white shadow-sm border border-white/20">
                            {{ $loggedStudent ? $loggedStudent->initials : 'M' }}
                        </span>
                        <span class="hidden max-w-[120px] truncate md:inline">{{ $loggedStudent ? $loggedStudent->nama : 'Mahasiswa' }}</span>
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Dropdown with High Readability -->
                    <div id="userMenuDropdown" class="absolute right-0 mt-2 hidden w-60 rounded-2xl p-2.5 shadow-2xl z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-2xl border border-white/90 dark:border-slate-700/80 ring-1 ring-black/5">
                        <div class="px-3 py-2.5 border-b border-slate-200/80 dark:border-slate-700/80 mb-1">
                            <p class="text-xs text-slate-500 font-medium">NIM Mahasiswa</p>
                            <p class="text-[14px] font-bold text-slate-900 dark:text-white">{{ $loggedStudent?->nim_no_anggota }}</p>
                            <p class="text-xs text-emerald-800 dark:text-emerald-400 font-semibold">{{ $loggedStudent?->kelas }} • {{ $loggedStudent?->program_studi }}</p>
                        </div>
                        <a href="{{ route('pinjaman') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-slate-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            Pinjaman Saya
                        </a>
                        <a href="{{ route('favorit') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-slate-800">
                            <svg class="h-4 w-4 text-amber-500 fill-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Buku Favorit
                        </a>
                        <a href="{{ route('riwayat') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-slate-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat
                        </a>
                        <div class="my-1 border-t border-slate-200/80 dark:border-slate-700/80"></div>
                        <a href="{{ route('keluar') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            Keluar
                        </a>
                    </div>
                </div>
            @else
                <!-- Guest View: Button to Login (Single Clear Entry Point) -->
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <a href="{{ route('masuk') }}" class="btn-emerald flex h-10 sm:h-11 items-center gap-2 rounded-full px-4 sm:px-5 text-[13px] sm:text-[14px] font-semibold">
                        <span>Masuk</span>
                    </a>
                    <a href="{{ route('daftar') }}" class="btn-amber-gold hidden sm:flex h-10 sm:h-11 items-center rounded-full px-4 sm:px-5 text-[13px] sm:text-[14px] font-semibold">
                        <span>Daftar</span>
                    </a>
                </div>
            @endif
        </div>
    </header>

    <!-- MAIN CONTENT WRAPPER -->
    <main id="appMain" class="sidebar-transition min-h-screen px-4 pb-24 pt-[100px] sm:px-6 lg:pl-[300px] lg:pr-8 lg:pt-[116px]">
        <div class="mx-auto max-w-[1200px]">
            <!-- FLASH NOTIFICATIONS -->
            @if(session('success'))
                <div class="glass-card mb-6 flex items-center gap-3.5 rounded-2xl border-emerald-300/80 bg-emerald-50/80 dark:bg-emerald-950/40 dark:border-emerald-500/30 p-4 text-emerald-900 dark:text-emerald-200 animate-rise shadow-sm">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-emerald-600 text-white shadow-sm border border-white/20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </span>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="glass-card mb-6 flex items-center gap-3.5 rounded-2xl border-rose-300/80 bg-rose-50/80 dark:bg-rose-950/40 dark:border-rose-500/30 p-4 text-rose-900 dark:text-rose-200 animate-rise shadow-sm">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-rose-600 text-white shadow-sm border border-white/20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </span>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="glass-card mb-6 flex items-center gap-3.5 rounded-2xl border-amber-300/80 bg-amber-50/80 dark:bg-amber-950/40 dark:border-amber-500/30 p-4 text-amber-900 dark:text-amber-200 animate-rise shadow-sm">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-amber-500 text-white shadow-sm border border-white/20">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    </span>
                    <p class="text-sm font-medium">{{ session('warning') }}</p>
                </div>
            @endif

            @yield('content')

            <!-- APPLE LIQUID GLASS FOOTER (Tentang Kami & Statistik Koleksi) -->
            <footer class="mt-20 sm:mt-28 rounded-[36px] glass-card p-7 sm:p-10 shadow-2xl border border-white/80 dark:border-white/10 relative overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
                    <!-- Col 1: Brand (5 cols) -->
                    <div class="lg:col-span-5 space-y-3.5">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-white p-1 shadow-md border border-white/80 dark:border-white/20">
                                <img src="{{ asset('images/logo-hmj-akuntansi.jpg') }}" alt="Logo" class="h-full w-full object-contain">
                            </div>
                            <div>
                                <span class="block text-[17px] font-bold text-slate-900 leading-tight">Pustaka Akuntansi</span>
                                <span class="block text-xs text-emerald-800 dark:text-emerald-400 font-semibold">Ruang Baca Jurusan Polines</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-[13px] text-slate-600 dark:text-slate-300 leading-relaxed max-w-md">
                            Portal katalog buku dan layanan sirkulasi perpustakaan digital untuk mahasiswa Jurusan Akuntansi Politeknik Negeri Semarang.
                        </p>
                        <div class="pt-2">
                            <a href="/admin" class="btn-glass inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:text-emerald-800 transition shadow-xs">
                                <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                <span>Masuk sebagai Admin</span>
                            </a>
                        </div>
                    </div>

                    <!-- Col 2: Tentang Kami (4 cols) -->
                    <div class="lg:col-span-4 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-slate-100">Tentang Kami</h4>
                        <p class="text-xs sm:text-[13px] text-slate-600 dark:text-slate-300 leading-relaxed">
                            Perpustakaan Pustaka Akuntansi menyediakan koleksi buku akuntansi, perpajakan, audit, manajemen keuangan, serta literatur tugas akhir dan skripsi.
                        </p>
                        <div class="pt-1 text-xs space-y-1 text-slate-500 dark:text-slate-400">
                            <div>📍 Ruang Baca Jurusan Akuntansi Polines</div>
                            <div>🕒 Senin – Kamis (08.30 – 15.30 WIB) • Jumat (08.30 – 14.00 WIB)</div>
                            <div>📞 WA Pustakawan: 0887895627873</div>
                        </div>
                    </div>

                    <!-- Col 3: Statistik Koleksi (3 cols) -->
                    <div class="lg:col-span-3 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-slate-100">Statistik Koleksi</h4>
                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between pb-1.5 border-b border-slate-200/50 dark:border-white/10">
                                <span class="text-slate-500">Total Judul</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ \App\Models\Book::count() }} Judul</span>
                            </div>
                            <div class="flex items-center justify-between pb-1.5 border-b border-slate-200/50 dark:border-white/10">
                                <span class="text-slate-500">Total Stok</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ \App\Models\Book::sum('stok') }} Eksemplar</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Tersedia</span>
                                <span class="font-bold text-emerald-800 dark:text-emerald-400">{{ \App\Models\Book::where('stok', '>', 0)->sum('stok') }} Eksemplar</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copyright Bar -->
                <div class="mt-8 pt-6 border-t border-slate-200/60 dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                    <p>© 2026 Pustaka Akuntansi — Ruang Baca Jurusan Akuntansi Polines.</p>
                    <a href="{{ route('tata-tertib') }}" class="font-medium text-emerald-800 dark:text-emerald-400 hover:underline">
                        Tata Tertib & Ketentuan Layanan →
                    </a>
                </div>
            </footer>
        </div>
    </main>

    <!-- MOBILE BOTTOM NAVIGATION (Phone only) -->
    <nav class="glass-topbar fixed bottom-4 left-4 right-4 z-30 flex h-16 items-center justify-around rounded-[24px] px-2 lg:hidden shadow-2xl">
        <a href="{{ route('beranda') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ request()->routeIs('beranda') ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
            Beranda
        </a>
        <a href="{{ route('katalog') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ request()->routeIs('katalog') ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
            Katalog
        </a>
        @if(session('student_id'))
            <a href="{{ route('pinjaman') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ request()->routeIs('pinjaman') ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                Pinjaman
            </a>
            <a href="{{ route('riwayat') }}" class="flex flex-col items-center gap-1 text-xs font-semibold {{ request()->routeIs('riwayat') ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Riwayat
            </a>
        @else
            <a href="{{ route('masuk') }}" class="flex flex-col items-center gap-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                Masuk
            </a>
        @endif
    </nav>

    <!-- Sidebar Collapse & Theme Toggle Scripts -->
    <script>
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('perpus_theme', isDark ? 'dark' : 'light');
        }

        function toggleSidebarCollapse() {
            const isCurrentlyCollapsed = document.documentElement.classList.contains('sidebar-collapsed') ||
                                         document.body.classList.contains('sidebar-collapsed');

            if (isCurrentlyCollapsed) {
                document.documentElement.classList.remove('sidebar-collapsed');
                document.body.classList.remove('sidebar-collapsed');
                localStorage.setItem('perpus_sidebar_collapsed', '0');
            } else {
                document.documentElement.classList.add('sidebar-collapsed');
                document.body.classList.add('sidebar-collapsed');
                localStorage.setItem('perpus_sidebar_collapsed', '1');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            const userMenu = document.getElementById('userMenuDropdown');
            const userWrapper = document.getElementById('userMenuWrapper');
            if (userMenu && userWrapper && !userWrapper.contains(e.target)) {
                userMenu.classList.add('hidden');
            }

            const notifMenu = document.getElementById('notifDropdown');
            const notifWrapper = document.getElementById('notifMenuWrapper');
            if (notifMenu && notifWrapper && !notifWrapper.contains(e.target)) {
                notifMenu.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
