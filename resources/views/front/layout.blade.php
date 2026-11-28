<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Perpustakaan Jurusan Akuntansi — Politeknik Negeri Semarang')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-perpustakaan-akuntansi.jpg') }}">
    @stack('styles')

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
            --apple-ease: cubic-bezier(0.32, 0.72, 0, 1);
            --apple-spring: cubic-bezier(0.22, 1.36, 0.36, 1);
        }

        @keyframes vtFadeIn {
            from { opacity: 0; transform: translateY(10px) scale(0.994); }
            to { opacity: 1; transform: none; }
        }

        @keyframes spotlightSlideIn {
            0% { opacity: 0; transform: translateX(18px) scale(0.96); filter: blur(6px); }
            100% { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
        }

        @keyframes shelfSwitchIn {
            0% { opacity: 0; transform: translateY(12px) scale(0.99); filter: blur(5px); }
            100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        .spotlight-slide.is-active {
            animation: spotlightSlideIn 0.48s var(--apple-ease) both;
        }
        .shelf-panel.is-active {
            animation: shelfSwitchIn 0.44s var(--apple-ease) both;
        }
        .spotlight-cover-3d {
            transform: perspective(900px) rotateY(-8deg) rotateX(2deg);
            transition: transform 0.55s var(--apple-spring), box-shadow 0.4s ease;
        }
        .spotlight-card:hover .spotlight-cover-3d {
            transform: perspective(900px) rotateY(0deg) rotateX(0deg) translateY(-4px) scale(1.03);
        }
        .spotlight-dot {
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            transition: width 0.38s var(--apple-spring), background-color 0.28s ease;
        }
        .spotlight-dot.is-active {
            width: 24px;
        }

        /* Authentic Liquid Glass Popover Sheet (Mounted outside <header> so backdrop-filter actually blurs content behind it!) */
        .glass-popover-sheet {
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.95) 0%, rgba(244, 253, 247, 0.91) 55%, rgba(255, 255, 255, 0.94) 100%) !important;
            backdrop-filter: blur(44px) saturate(210%) brightness(102%) !important;
            -webkit-backdrop-filter: blur(44px) saturate(210%) brightness(102%) !important;
            border: 1px solid rgba(255, 255, 255, 0.96) !important;
            border-top: 1.5px solid #ffffff !important;
            box-shadow: inset 0 1.5px 1px 0 #ffffff,
                        inset 0 -1px 1px 0 rgba(6, 78, 59, 0.05),
                        0 28px 64px -16px rgba(6, 78, 59, 0.28),
                        0 6px 18px -4px rgba(15, 23, 42, 0.10) !important;
        }

        html.dark .glass-popover-sheet {
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.05) 60%, rgba(16, 185, 129, 0.06) 100%),
                        rgba(10, 22, 17, 0.93) !important;
            backdrop-filter: blur(44px) saturate(200%) !important;
            -webkit-backdrop-filter: blur(44px) saturate(200%) !important;
            border: 1px solid rgba(255, 255, 255, 0.18) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.35) !important;
            box-shadow: inset 0 1.5px 1px 0 rgba(255, 255, 255, 0.25),
                        0 28px 64px -14px rgba(0, 0, 0, 0.88) !important;
        }

        /* Slim Top Navigation Progress Indicator */
        #appleNavProgress {
            position: fixed;
            top: 0;
            left: 0;
            height: 2.5px;
            width: 0%;
            z-index: 60;
            background: linear-gradient(90deg, #34d399, #10b981, #fde047);
            opacity: 0;
            pointer-events: none;
            transition: width 0.28s var(--apple-ease), opacity 0.2s ease;
        }

        body {
            background: var(--app-bg);
            background-attachment: fixed;
            color: #0f172a;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            overflow-x: clip;
            transition: background 0.45s var(--apple-ease), color 0.3s ease;
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
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.84) 0%, rgba(240, 253, 244, 0.72) 50%, rgba(255, 255, 255, 0.84) 100%);
            backdrop-filter: blur(36px) saturate(220%) brightness(103%);
            -webkit-backdrop-filter: blur(36px) saturate(220%) brightness(103%);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1.5px 1px 0 #ffffff,
                        0 16px 40px -16px rgba(6, 78, 59, 0.14),
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

        /* Apple Liquid Glass Interactive Polish (Spring Physics + Dynamic Specular Highlight) */
        .btn-emerald,
        .btn-glass,
        .liquid-chip,
        .liquid-icon-btn,
        .btn-yellow,
        .btn-amber-gold,
        .apple-press {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
            will-change: transform;
        }

        .glass-card-hover {
            position: relative;
            transition: transform 0.42s var(--apple-spring),
                        box-shadow 0.36s var(--apple-ease),
                        border-color 0.28s ease;
        }

        .glass-card-hover:hover {
            transform: translateY(-4px) scale(1.008);
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 28px 58px -16px rgba(6, 78, 59, 0.22);
        }

        .glass-card-hover:active {
            transform: translateY(-1px) scale(0.985);
            transition-duration: 90ms;
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
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.72) 0%, rgba(240, 249, 244, 0.58) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-top: 1px solid rgba(15, 23, 42, 0.08);
            border-bottom: 1.5px solid rgba(255, 255, 255, 0.95);
            box-shadow: inset 0 1.5px 3px 0 rgba(6, 78, 59, 0.05),
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
            transition: transform 0.38s var(--apple-spring),
                        box-shadow 0.28s var(--apple-ease),
                        filter 0.24s ease;
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

        .btn-emerald::after,
        .btn-glass::after {
            content: '';
            position: absolute;
            inset: -20% -60%;
            background: linear-gradient(110deg, transparent 35%, rgba(255, 255, 255, 0.38) 50%, transparent 65%);
            transform: translateX(-110%);
            transition: transform 0.65s var(--apple-ease);
            pointer-events: none;
        }

        .btn-emerald:hover::after,
        .btn-glass:hover::after {
            transform: translateX(110%);
        }

        .btn-emerald:hover {
            filter: brightness(1.07);
            transform: translateY(-2px) scale(1.015);
            box-shadow: inset 0 1.5px 1.5px 0 #ffffff,
                        inset 0 -1.5px 1px 0 rgba(0, 0, 0, 0.2),
                        0 18px 38px -6px rgba(16, 185, 129, 0.65),
                        0 0 20px -2px rgba(234, 179, 8, 0.35) !important;
        }

        .btn-emerald:active {
            transform: translateY(0) scale(0.94);
            transition-duration: 90ms;
            box-shadow: inset 0 1.5px 2px 0 rgba(0, 0, 0, 0.22),
                        0 6px 16px -4px rgba(5, 150, 105, 0.4) !important;
        }

        /* Compact glow spread inside horizontally scrolling chip strips so overflow-x clip never slices glow boxes */
        .catalog-chip-strip .btn-emerald {
            box-shadow: inset 0 1.5px 1.5px 0 rgba(254, 240, 138, 0.7),
                        inset 0 -1.5px 1px 0 rgba(0, 0, 0, 0.2),
                        0 4px 10px -2px rgba(16, 185, 129, 0.42),
                        0 0 6px -1px rgba(52, 211, 153, 0.25) !important;
        }

        .catalog-chip-strip .btn-emerald:hover {
            box-shadow: inset 0 1.5px 1.5px 0 #ffffff,
                        inset 0 -1.5px 1px 0 rgba(0, 0, 0, 0.2),
                        0 6px 14px -2px rgba(16, 185, 129, 0.55),
                        0 0 8px -1px rgba(234, 179, 8, 0.3) !important;
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
            transition: transform 0.38s var(--apple-spring),
                        background 0.24s ease,
                        border-color 0.24s ease,
                        box-shadow 0.28s var(--apple-ease);
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
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(240, 253, 244, 0.68) 100%);
            border-color: rgba(52, 211, 153, 0.6);
            border-top-color: #ffffff;
            transform: translateY(-2px) scale(1.012);
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 12px 26px -6px rgba(6, 78, 59, 0.18);
        }

        .btn-glass:active {
            transform: translateY(0) scale(0.94);
            transition-duration: 90ms;
            filter: brightness(0.97);
            box-shadow: inset 0 1.5px 2px 0 rgba(6, 78, 59, 0.14), 0 3px 10px -4px rgba(6, 78, 59, 0.10);
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
            transition: transform 0.36s var(--apple-spring),
                        background 0.24s ease,
                        border-color 0.24s ease,
                        color 0.2s ease,
                        box-shadow 0.28s var(--apple-ease);
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
            transform: translateY(-2px) scale(1.02);
            box-shadow: inset 0 1px 1px 0 #ffffff, 0 10px 24px -4px rgba(16, 185, 129, 0.32);
        }

        .liquid-chip:active {
            transform: translateY(0) scale(0.93);
            transition-duration: 85ms;
            filter: brightness(0.97);
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
            transition: transform 0.38s var(--apple-spring),
                        background 0.24s ease,
                        box-shadow 0.28s var(--apple-ease),
                        filter 0.2s ease;
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
            transform: translateY(-1.5px) scale(1.04);
            filter: brightness(1.04);
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 10px 20px -4px rgba(6, 78, 59, 0.18);
        }

        .liquid-icon-btn:active {
            transform: translateY(0) scale(0.90);
            transition-duration: 85ms;
            filter: brightness(0.95);
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
            from { opacity: 0; transform: translateY(12px) scale(0.985); filter: blur(4px); }
            to { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        @keyframes popoverSpringIn {
            0% { opacity: 0; transform: translateY(-10px) scale(0.92); filter: blur(8px); }
            100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        @keyframes popoverSpringOut {
            0% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
            100% { opacity: 0; transform: translateY(-8px) scale(0.94); filter: blur(6px); }
        }

        @keyframes modalSheetIn {
            0% { opacity: 0; transform: translateY(20px) scale(0.93); filter: blur(8px); }
            100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        @keyframes modalSheetOut {
            0% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
            100% { opacity: 0; transform: translateY(14px) scale(0.95); filter: blur(6px); }
        }

        @keyframes backdropFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .apple-modal-root.is-closing .glass-modal-panel {
            animation: modalSheetOut 0.2s var(--apple-ease) forwards;
        }
        .apple-modal-root.is-closing .glass-modal-backdrop {
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .glass-modal-backdrop {
            animation: backdropFadeIn 0.28s ease both;
        }

        .animate-rise {
            animation: riseIn 0.45s var(--apple-ease) both;
        }

        .apple-stage-enter {
            animation: vtFadeIn 0.22s var(--apple-ease) both;
        }

        .apple-stage-leaving {
            opacity: 0;
            transform: translateY(4px) scale(0.996);
            transition: opacity 0.09s ease, transform 0.09s ease;
        }

        .glass-popover {
            transform-origin: top right;
            animation: popoverSpringIn 0.26s var(--apple-spring) both;
        }
        .glass-popover.is-closing {
            animation: popoverSpringOut 0.15s var(--apple-ease) forwards;
            pointer-events: none;
        }

        .glass-modal-panel {
            animation: modalSheetIn 0.32s var(--apple-spring) both;
        }

        /* Staggered scroll / mount reveal */
        .apple-reveal {
            opacity: 0;
            transform: translateY(12px) scale(0.99);
            transition: opacity 0.30s var(--apple-ease),
                        transform 0.34s var(--apple-ease);
            transition-delay: calc(var(--stagger, 0) * 25ms);
        }
        .apple-reveal.is-visible {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Sliding Gliding Pill inside Segmented Controls */
        .segment-track {
            display: flex;
            align-items: center;
            position: relative;
        }
        .segment-thumb {
            position: absolute;
            top: 4px;
            bottom: 4px;
            left: 0;
            border-radius: 9999px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(240, 253, 244, 0.88) 100%);
            border: 1px solid rgba(167, 243, 208, 0.9);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 6px 16px -4px rgba(6, 78, 59, 0.18);
            transition: transform 0.42s var(--apple-spring), width 0.38s var(--apple-ease), opacity 0.2s ease;
            pointer-events: none;
            z-index: 0;
        }
        html.dark .segment-thumb {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.38) 0%, rgba(5, 150, 105, 0.22) 100%);
            border: 1px solid rgba(52, 211, 153, 0.5);
            border-top: 1.5px solid rgba(110, 231, 183, 0.8);
            box-shadow: inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.45), 0 8px 22px -6px rgba(16, 185, 129, 0.45);
        }
        .segment-track > a,
        .segment-track > button {
            position: relative;
            z-index: 1;
        }
        .segment-track.has-thumb > a.bg-white,
        .segment-track.has-thumb > button.is-active {
            background: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
        }

        /* COLLAPSIBLE SIDEBAR RULES (Fluid Apple NavigationSplitView) */
        .sidebar-transition {
            transition: width 0.42s var(--apple-ease),
                        left 0.42s var(--apple-ease),
                        padding 0.42s var(--apple-ease);
        }

        #appSidebar .sidebar-text {
            display: block;
            max-width: 180px;
            opacity: 1;
            overflow: hidden;
            white-space: nowrap;
            transform: translateX(0);
            transition: max-width 0.36s var(--apple-ease),
                        opacity 0.24s ease,
                        transform 0.34s var(--apple-ease);
        }

        html.sidebar-collapsed #appSidebar,
        body.sidebar-collapsed #appSidebar {
            width: 68px !important;
            padding-left: 0.6rem !important;
            padding-right: 0.6rem !important;
        }

        html.sidebar-collapsed #appSidebar .sidebar-text,
        body.sidebar-collapsed #appSidebar .sidebar-text {
            max-width: 0 !important;
            opacity: 0 !important;
            transform: translateX(-8px) !important;
            pointer-events: none;
        }

        html.sidebar-collapsed #appSidebar .sidebar-header,
        body.sidebar-collapsed #appSidebar .sidebar-header {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        html.sidebar-collapsed #appSidebar .sidebar-brand-link,
        body.sidebar-collapsed #appSidebar .sidebar-brand-link {
            justify-content: center !important;
            gap: 0 !important;
        }

        html.sidebar-collapsed #appSidebar nav a,
        body.sidebar-collapsed #appSidebar nav a {
            justify-content: center !important;
            gap: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            width: 38px !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        html.sidebar-collapsed #appSidebar .sidebar-badge,
        body.sidebar-collapsed #appSidebar .sidebar-badge {
            position: absolute !important;
            top: 2px !important;
            right: 2px !important;
            height: 15px !important;
            min-width: 15px !important;
            padding: 0 4px !important;
            font-size: 9.5px !important;
        }

        html.sidebar-collapsed #appHeader,
        body.sidebar-collapsed #appHeader,
        html.sidebar-collapsed .scroll-edge-top,
        body.sidebar-collapsed .scroll-edge-top {
            left: 96px !important;
        }

        html.sidebar-collapsed #appMain,
        body.sidebar-collapsed #appMain {
            padding-left: 96px !important;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
            .apple-reveal {
                opacity: 1 !important;
                transform: none !important;
            }
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
            color: #f1f5f9 !important;
        }

        html.dark .liquid-icon-btn:hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.08) 100%) !important;
            border-color: rgba(255, 255, 255, 0.28) !important;
            border-top-color: rgba(255, 255, 255, 0.5) !important;
            color: #ffffff !important;
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

        /* Apple macOS-style Sleek Translucent Scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 23, 42, 0.16) transparent;
        }
        html.dark * {
            scrollbar-color: rgba(255, 255, 255, 0.22) transparent;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
            margin: 6px 0;
        }
        ::-webkit-scrollbar-button {
            display: none;
            width: 0;
            height: 0;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(15, 23, 42, 0.16);
            border-radius: 9999px;
            border: 1px solid transparent;
            background-clip: content-box;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(15, 23, 42, 0.30);
        }
        html.dark ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.22);
        }
        html.dark ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.36);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Apple HIG Scroll Edge Effect (Top: blur + fade; Bottom: fade-only per iOS 26 guidelines) */
        .scroll-edge-top {
            background: linear-gradient(180deg, rgba(240, 253, 244, 0.86) 0%, rgba(240, 253, 244, 0.38) 58%, transparent 100%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            -webkit-mask-image: linear-gradient(to bottom, black 55%, transparent 100%);
            mask-image: linear-gradient(to bottom, black 55%, transparent 100%);
        }
        .scroll-edge-bottom {
            background: linear-gradient(0deg, rgba(240, 253, 244, 0.88) 0%, rgba(240, 253, 244, 0.35) 55%, transparent 100%);
        }
        html.dark .scroll-edge-top {
            background: linear-gradient(180deg, rgba(6, 17, 12, 0.88) 0%, rgba(6, 17, 12, 0.38) 58%, transparent 100%);
        }
        html.dark .scroll-edge-bottom {
            background: linear-gradient(0deg, rgba(5, 15, 10, 0.90) 0%, rgba(5, 15, 10, 0.35) 55%, transparent 100%);
        }

        /* Safe Area support for iOS/iPadOS */
        .safe-bottom-nav {
            bottom: max(0.75rem, env(safe-area-inset-bottom));
        }

        /* Table Rows */
        html.dark tr:hover {
            background-color: rgba(255, 255, 255, 0.04) !important;
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 dark:text-slate-100 antialiased selection:bg-emerald-500/20 selection:text-emerald-400 relative">
    <div id="appleNavProgress"></div>

    <!-- FLOATING GLASS SIDEBAR (Desktop Apple NavigationSplitView proportions; 28px = 16px + 12px pad) -->
    <aside id="appSidebar" class="glass-panel sidebar-transition fixed bottom-4 left-4 top-4 z-30 hidden w-[236px] flex-col rounded-[28px] p-3 lg:flex">
        <!-- Sidebar Brand Header -->
        <div class="sidebar-header flex items-center px-1">
            <a href="{{ route('beranda') }}" class="sidebar-brand-link group flex items-center gap-2.5 min-w-0 w-full transition-all duration-300 hover:opacity-90 active:scale-[0.98]">
                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-[16px] bg-white p-0.5 shadow-md shadow-emerald-950/15 ring-1 ring-emerald-500/15 border border-white/80 dark:border-white/20 overflow-hidden transition-transform duration-300 group-hover:scale-105">
                    <img src="{{ asset('images/logo-perpustakaan-akuntansi.jpg') }}" alt="Logo Perpustakaan Jurusan Akuntansi" class="h-full w-full object-contain">
                </div>
                <div class="sidebar-text min-w-0">
                    <span class="block text-[11px] font-bold leading-tight tracking-[0.08em] uppercase text-emerald-700 dark:text-emerald-400">Perpustakaan</span>
                    <span class="block text-[13px] font-bold leading-tight tracking-tight text-slate-900 dark:text-white mt-0.5">Jurusan Akuntansi</span>
                    <span class="block text-[11px] font-medium leading-tight text-slate-500 dark:text-slate-400 mt-0.5 truncate">Politeknik Negeri Semarang</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-5 flex flex-col gap-1">
            <!-- Beranda -->
            <a href="{{ route('beranda') }}"
               title="Beranda"
               class="relative flex h-9 items-center gap-2.5 rounded-full px-3 text-[13px] transition-all duration-300 active:scale-[0.95] {{ request()->routeIs('beranda') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                <svg class="h-[17px] w-[17px] shrink-0 {{ request()->routeIs('beranda') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="sidebar-text flex-1">Beranda</span>
            </a>

            <!-- Katalog -->
            <a href="{{ route('katalog') }}"
               title="Katalog Buku"
               class="relative flex h-9 items-center gap-2.5 rounded-full px-3 text-[13px] transition-all duration-300 active:scale-[0.95] {{ request()->routeIs('katalog') || request()->routeIs('buku.detail') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                <svg class="h-[17px] w-[17px] shrink-0 {{ request()->routeIs('katalog') || request()->routeIs('buku.detail') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                <span class="sidebar-text flex-1">Katalog</span>
            </a>

            <!-- Tata Tertib & Ketentuan Layanan -->
            <a href="{{ route('tata-tertib') }}"
               title="Tata Tertib"
               class="relative flex h-9 items-center gap-2.5 rounded-full px-3 text-[13px] transition-all duration-300 active:scale-[0.95] {{ request()->routeIs('tata-tertib') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                <svg class="h-[17px] w-[17px] shrink-0 {{ request()->routeIs('tata-tertib') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
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
                   class="relative flex h-9 items-center gap-2.5 rounded-full px-3 text-[13px] transition-all duration-300 active:scale-[0.95] {{ request()->routeIs('favorit') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                    <svg class="h-[17px] w-[17px] shrink-0 {{ request()->routeIs('favorit') ? 'text-emerald-700' : 'text-slate-500' }}" fill="{{ request()->routeIs('favorit') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                    <span class="sidebar-text flex-1">Buku Favorit</span>
                </a>

                <!-- Pinjaman Saya -->
                <a href="{{ route('pinjaman') }}"
                   title="Pinjaman Saya"
                   class="relative flex h-9 items-center gap-2.5 rounded-full px-3 text-[13px] transition-all duration-300 active:scale-[0.95] {{ request()->routeIs('pinjaman') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                    <svg class="h-[17px] w-[17px] shrink-0 {{ request()->routeIs('pinjaman') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                    <span class="sidebar-text flex-1">Pinjaman Saya</span>
                    @if($activeBadgeCount > 0)
                        <span class="sidebar-badge btn-amber-gold grid h-5 min-w-5 place-items-center rounded-full px-1.5 text-[10.5px] font-bold tabular-nums">
                            {{ $activeBadgeCount }}
                        </span>
                    @endif
                </a>

                <!-- Riwayat -->
                <a href="{{ route('riwayat') }}"
                   title="Riwayat"
                   class="relative flex h-9 items-center gap-2.5 rounded-full px-3 text-[13px] transition-all duration-300 active:scale-[0.95] {{ request()->routeIs('riwayat') ? 'bg-white/95 font-semibold text-emerald-800' : 'text-slate-600 hover:bg-white/50 hover:text-slate-900' }}">
                    <svg class="h-[17px] w-[17px] shrink-0 {{ request()->routeIs('riwayat') ? 'text-emerald-700' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="sidebar-text flex-1">Riwayat</span>
                </a>
            @endif
        </nav>

        @if(session('student_id'))
            <!-- Bottom Widget in Sidebar -->
            <div class="mt-auto pt-3 border-t border-slate-200/50 dark:border-white/10">
                <a href="{{ route('keluar') }}"
                   title="Keluar"
                   class="flex h-9 items-center gap-2.5 rounded-full px-3 text-[13px] text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition-all duration-200 active:scale-[0.95] hover:bg-rose-50 dark:hover:bg-rose-950/30">
                    <svg class="h-[17px] w-[17px] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <span class="sidebar-text">Keluar</span>
                </a>
            </div>
        @endif
    </aside>

    <!-- SCROLL EDGE EFFECT (Top scrim beneath floating topbar) -->
    <div class="scroll-edge-top sidebar-transition pointer-events-none fixed inset-x-0 top-0 z-10 h-36 sm:h-16 lg:left-[252px]"></div>

    <!-- FLOATING GLASS TOPBAR (Concentric 30px mobile / 25px pill desktop = inner radius + pad) -->
    <header id="appHeader" class="glass-topbar sidebar-transition fixed left-3 right-3 top-3 z-20 flex flex-col gap-2 rounded-[30px] p-2 sm:left-4 sm:right-4 sm:top-4 sm:h-[50px] sm:flex-row sm:items-center sm:justify-between sm:gap-3 sm:rounded-[25px] sm:px-3.5 sm:py-0 lg:left-[264px] lg:right-4 lg:top-4">
        <!-- Mobile Top Row (Logo + Action Controls) / Desktop Leading Sidebar Toggle + Search + Actions -->
        <div class="flex w-full items-center justify-between gap-2 sm:contents">
            <!-- Desktop Apple sidebar.left Toolbar Button -->
            <button id="sidebarToggleBtn"
                    type="button"
                    onclick="toggleSidebarCollapse()"
                    class="liquid-icon-btn hidden lg:grid h-8 w-8 shrink-0 place-items-center rounded-full text-slate-600 dark:text-slate-300"
                    title="Tampilkan / Kecilkan Sidebar"
                    aria-label="Tampilkan atau Kecilkan Sidebar">
                <svg class="h-4 w-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="4" width="18" height="16" rx="3.5" />
                    <path stroke-linecap="round" d="M9.5 4.5v15" />
                    <path stroke-linecap="round" d="M5.75 8.5h1.5M5.75 11.5h1.5" />
                </svg>
            </button>

            <!-- Mobile Logo + Title -->
            <a href="{{ route('beranda') }}" class="flex min-h-[44px] items-center gap-2.5 min-w-0 lg:hidden active:scale-95 transition-transform">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-[18px] bg-white p-0.5 shadow-sm border border-slate-200/60 dark:border-white/20 overflow-hidden">
                    <img src="{{ asset('images/logo-perpustakaan-akuntansi.jpg') }}" alt="Logo Perpustakaan Jurusan Akuntansi" class="h-full w-full object-contain">
                </span>
                <span class="hidden min-[460px]:inline sm:hidden text-xs font-bold leading-tight text-slate-900 dark:text-white truncate">
                    Perpus Akuntansi
                </span>
            </a>

            <!-- Desktop/Tablet Liquid Glass Search Capsule -->
            <form action="{{ route('katalog') }}" method="GET" class="hidden sm:block mx-auto w-full max-w-[380px]">
                <div class="glass-field flex h-9 lg:h-8 items-center gap-2 rounded-full px-3">
                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari judul, penulis, atau ISBN..."
                        class="w-full bg-transparent text-[12.5px] font-medium outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500"
                    >
                </div>
            </form>

            <!-- Right Side: Dark Mode Toggle + Notification + User Avatar / Login Pills -->
            <div class="flex items-center gap-1.5 shrink-0">
                <!-- DARK MODE LIQUID GLASS TOGGLE BUTTON (>= 44pt tap target on mobile) -->
                <button type="button"
                        onclick="toggleDarkMode()"
                        class="liquid-icon-btn grid h-11 w-11 sm:h-9 sm:w-9 lg:h-8 lg:w-8 shrink-0 place-items-center rounded-full text-slate-600 dark:text-amber-300"
                        title="Ganti Mode Gelap / Terang"
                        aria-label="Ganti Mode Gelap atau Terang">
                    <!-- Sun icon (shown when dark) -->
                    <svg class="hidden dark:block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <!-- Moon icon (shown when light) -->
                    <svg class="block dark:hidden h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                </button>

            <!-- Notification Bell (Authenticated Students Only: Personal Alerts, Apple Liquid Glass Sheet) -->
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
                        if ($loanItem->isOverdue()) {
                            $diff = max(1, $loanItem->calculateOverdueWeekdays(today()));
                            $headerNotifs[] = [
                                'badge' => 'Terlambat',
                                'badge_class' => 'bg-rose-500/15 text-rose-700 dark:bg-rose-500/20 dark:text-rose-300 border border-rose-500/25',
                                'title' => 'Peringatan Terlambat Pengembalian',
                                'message' => 'Buku "' . ($loanItem->book?->judul ?? 'Buku') . '" terlambat ' . $diff . ' hari kerja. Harap segera kembalikan di meja sirkulasi.',
                                'time' => 'Batas: ' . ($loanItem->jatuh_tempo?->format('d M Y') ?? '-'),
                                'url' => route('pinjaman', ['tab' => 'denda']),
                            ];
                            $unreadAlertCount++;
                        } elseif ($loanItem->status === 'dipinjam' && $loanItem->jatuh_tempo) {
                            $diffToDue = (int) today()->diffInDays($loanItem->jatuh_tempo->startOfDay(), false);
                            if ($diffToDue >= 0 && $diffToDue <= 3) {
                                $headerNotifs[] = [
                                    'badge' => 'Segera Kembali',
                                    'badge_class' => 'bg-amber-500/15 text-amber-800 dark:bg-amber-400/20 dark:text-amber-200 border border-amber-500/25',
                                    'title' => 'Batas Pengembalian Mendekat',
                                    'message' => 'Buku "' . ($loanItem->book?->judul ?? 'Buku') . '" jatuh tempo dalam ' . max(0, $diffToDue) . ' hari.',
                                    'time' => 'Batas: ' . $loanItem->jatuh_tempo->format('d M Y'),
                                    'url' => route('pinjaman', ['tab' => 'dipinjam']),
                                ];
                                $unreadAlertCount++;
                            }
                        } elseif ($loanItem->status === 'booked') {
                            $headerNotifs[] = [
                                'badge' => 'Siap Diambil',
                                'badge_class' => 'bg-emerald-500/15 text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-200 border border-emerald-500/25',
                                'title' => 'Booking Siap Diambil di Ruang Baca',
                                'message' => 'Buku "' . ($loanItem->book?->judul ?? 'Buku') . '" siap diambil. Kode: ' . ($loanItem->booking_code ?? 'AKT-BOOK'),
                                'time' => 'Maks. 1×24 jam',
                                'url' => route('pinjaman', ['tab' => 'siap']),
                            ];
                            $unreadAlertCount++;
                        }
                    }
                }
            @endphp

            @if($loggedStudent)
                <div class="relative" id="notifMenuWrapper">
                    <button type="button"
                            onclick="toggleGlassDropdown('notifDropdown', 'userMenuDropdown', this)"
                            class="liquid-icon-btn relative grid h-11 w-11 sm:h-9 sm:w-9 lg:h-8 lg:w-8 place-items-center rounded-full text-slate-600 dark:text-slate-300"
                            title="Notifikasi Sirkulasi"
                            aria-label="Buka Notifikasi">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        @if($unreadAlertCount > 0)
                            <span class="absolute top-0.5 right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-sm ring-2 ring-white dark:ring-slate-900 animate-pulse">
                                {{ $unreadAlertCount }}
                            </span>
                        @endif
                    </button>
                </div>
            @endif

            @if(session('student_id'))
                <!-- User Profile Liquid Glass Capsule -->
                <div class="relative" id="userMenuWrapper">
                    <button type="button" onclick="toggleGlassDropdown('userMenuDropdown', 'notifDropdown', this)" class="btn-glass flex h-11 sm:h-9 lg:h-8 items-center gap-1.5 rounded-full px-2.5 text-[12.5px] font-semibold">
                        <span class="grid h-6 w-6 lg:h-5.5 lg:w-5.5 place-items-center rounded-full bg-emerald-700 text-[11px] font-bold text-white shadow-sm border border-white/20">
                            {{ $loggedStudent ? $loggedStudent->initials : 'M' }}
                        </span>
                        <span class="hidden max-w-[100px] truncate md:inline">{{ $loggedStudent ? $loggedStudent->nama : 'Mahasiswa' }}</span>
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </div>
            @else
                <!-- Guest View: Apple HIG Action Prominence -->
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('daftar') }}" class="btn-glass flex h-11 sm:h-9 lg:h-8 items-center rounded-full px-3.5 text-[12.5px] font-semibold whitespace-nowrap">
                        <span>Daftar</span>
                    </a>
                    <a href="{{ route('masuk') }}" class="btn-emerald flex h-11 sm:h-9 lg:h-8 items-center gap-1.5 rounded-full px-4 text-[12.5px] font-semibold whitespace-nowrap">
                        <span>Masuk</span>
                    </a>
                </div>
            @endif
            </div>
        </div>

        <!-- Mobile Search Capsule (44px min height) -->
        <form action="{{ route('katalog') }}" method="GET" class="w-full sm:hidden">
            <div class="glass-field flex h-11 items-center gap-2.5 rounded-full px-3.5">
                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari judul, penulis, atau ISBN..."
                    class="w-full bg-transparent text-[14px] font-medium outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500"
                >
            </div>
        </form>
    </header>

    @if($loggedStudent)
        <!-- FLOATING POPOVERS OUTSIDE <header> (Top-level DOM nodes so backdrop-filter genuinely blurs underlying content without bleed-through!) -->
        <div id="notifDropdown" class="glass-popover glass-popover-sheet fixed z-50 hidden rounded-[28px] p-3 sm:w-[390px]">
            <div class="flex items-center justify-between pb-3 mb-3 px-2 border-b border-slate-300/40 dark:border-white/10">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-slate-900 dark:text-white">Notifikasi Anda</span>
                    @if($unreadAlertCount > 0)
                        <span class="rounded-full bg-rose-500/15 px-2.5 py-0.5 text-[11px] font-bold text-rose-700 dark:text-rose-300">
                            {{ $unreadAlertCount }} aktif
                        </span>
                    @endif
                </div>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold">Sirkulasi</span>
            </div>

            <div class="max-h-[340px] overflow-y-auto space-y-2.5 pr-0.5">
                @forelse($headerNotifs as $notif)
                    <a href="{{ $notif['url'] }}" class="apple-press group block rounded-[16px] p-3.5 bg-white/85 dark:bg-slate-800/85 hover:bg-white dark:hover:bg-slate-800 transition-all border border-white/90 dark:border-white/15 shadow-xs">
                        <div class="flex items-start justify-between gap-2 mb-1.5">
                            <span class="text-[11px] font-bold uppercase tracking-wider rounded-full px-2.5 py-0.5 {{ $notif['badge_class'] }}">
                                {{ $notif['badge'] }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400">{{ $notif['time'] }}</span>
                        </div>
                        <p class="text-[13px] font-bold text-slate-900 dark:text-white group-hover:text-emerald-800 dark:group-hover:text-emerald-300 transition-colors leading-snug">
                            {{ $notif['title'] }}
                        </p>
                        <p class="mt-1 text-[12px] text-slate-700 dark:text-slate-200 leading-relaxed">
                            {{ $notif['message'] }}
                        </p>
                    </a>
                @empty
                    <div class="rounded-[16px] p-5 bg-white/80 dark:bg-slate-800/75 border border-white/90 dark:border-white/12 text-center space-y-1.5">
                        <span class="mx-auto grid h-10 w-10 place-items-center rounded-full bg-emerald-500/15 text-emerald-700 dark:text-emerald-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </span>
                        <p class="text-xs font-bold text-slate-900 dark:text-white">Semua Aman, Tidak Ada Peringatan</p>
                        <p class="text-[11.5px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Pengingat kode booking 1&times;24 jam dan batas pengembalian buku akan muncul otomatis di sini.
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="pt-3 mt-3 border-t border-slate-300/40 dark:border-white/10 text-center">
                <a href="{{ route('pinjaman') }}" class="inline-flex min-h-[44px] sm:min-h-[36px] items-center text-xs font-bold text-emerald-800 dark:text-emerald-400 hover:underline">
                    Kelola Pinjaman Saya &rarr;
                </a>
            </div>
        </div>

        <div id="userMenuDropdown" class="glass-popover glass-popover-sheet fixed z-50 hidden rounded-[24px] p-2 sm:w-64">
            <div class="px-3 py-2.5 border-b border-slate-200/80 dark:border-slate-700/80 mb-1">
                <p class="text-xs text-slate-500 font-medium">NIM Mahasiswa</p>
                <p class="text-[14px] font-bold text-slate-900 dark:text-white">{{ $loggedStudent?->nim_no_anggota }}</p>
                <p class="text-xs text-emerald-800 dark:text-emerald-400 font-semibold">{{ $loggedStudent?->kelas }} • {{ $loggedStudent?->program_studi }}</p>
            </div>
            <a href="{{ route('pinjaman') }}" class="flex min-h-[44px] items-center gap-2.5 rounded-[16px] px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-slate-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                Pinjaman Saya
            </a>
            <a href="{{ route('favorit') }}" class="flex min-h-[44px] items-center gap-2.5 rounded-[16px] px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-slate-800">
                <svg class="h-4 w-4 text-amber-500 fill-amber-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Buku Favorit
            </a>
            <a href="{{ route('riwayat') }}" class="flex min-h-[44px] items-center gap-2.5 rounded-[16px] px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-emerald-50 hover:text-emerald-800 dark:hover:bg-slate-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat
            </a>
            <div class="my-1 border-t border-slate-200/80 dark:border-slate-700/80"></div>
            <a href="{{ route('keluar') }}" class="flex min-h-[44px] items-center gap-2.5 rounded-[16px] px-3 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Keluar
            </a>
        </div>
    @endif

    <!-- MAIN CONTENT WRAPPER -->
    <main id="appMain" class="sidebar-transition min-h-screen px-4 pb-28 pt-[144px] sm:px-5 sm:pt-[82px] lg:pb-10 lg:pl-[264px] lg:pr-5 lg:pt-[78px]">
        <div id="appMainStage" class="apple-stage-enter mx-auto max-w-[1060px]">
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

            <!-- APPLE LIQUID GLASS BENTO FOOTER (Concentric 36px -> 22px subcards) -->
            @php
                $footerTotalTitles = \App\Models\Book::count();
                $footerAvailableCopies = (int) \App\Models\Book::sum('stok');
                $footerBorrowedCopies = (int) \App\Models\Loan::whereIn('status', ['dipinjam', 'terlambat'])->count();
                $footerTotalCopies = $footerAvailableCopies + $footerBorrowedCopies;
            @endphp
            <footer class="apple-reveal mt-10 sm:mt-12 glass-card relative overflow-hidden rounded-[30px] p-4 shadow-xl border border-white/85 dark:border-white/10">
                <!-- Subtle Ambient Glow -->
                <div class="pointer-events-none absolute -right-20 -top-20 h-44 w-44 rounded-full bg-emerald-400/15 blur-3xl dark:bg-emerald-500/10"></div>

                <!-- Top Brand & Quick Actions Bar -->
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-slate-200/60 dark:border-white/10">
                    <div class="flex items-center gap-2.5">
                        <div class="grid h-9 w-9 shrink-0 place-items-center rounded-[14px] bg-white p-0.5 shadow-sm ring-1 ring-emerald-500/15 border border-white/80 dark:border-white/20 overflow-hidden">
                            <img src="{{ asset('images/logo-perpustakaan-akuntansi.jpg') }}" alt="Logo Perpustakaan Jurusan Akuntansi" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <span class="block text-[13.5px] sm:text-[14px] font-bold text-slate-900 leading-tight">Perpustakaan Jurusan Akuntansi</span>
                            <span class="block text-[11px] text-emerald-700 dark:text-emerald-400 font-semibold mt-0.5">Politeknik Negeri Semarang</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="/admin" class="btn-glass inline-flex min-h-[44px] sm:min-h-[34px] items-center gap-1.5 rounded-full px-3.5 text-[11.5px] font-semibold text-slate-700 dark:text-slate-200 hover:text-emerald-800">
                            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <span>Portal Admin</span>
                        </a>
                    </div>
                </div>

                <!-- Middle Concentric Bento Columns (Outer 26px = 14px + 12px padding; Inner 8px) -->
                <div class="relative z-10 mt-3.5 grid grid-cols-1 md:grid-cols-12 gap-3">
                    <!-- Bento Card 1: Tentang Kami & Lokasi (5 Cols) -->
                    <div class="md:col-span-6 lg:col-span-5 rounded-[14px] bg-white/55 dark:bg-white/5 border border-white/80 dark:border-white/10 p-3 flex flex-col justify-between gap-2.5">
                        <div>
                            <h4 class="text-[11px] font-bold uppercase tracking-[0.1em] text-emerald-700 dark:text-emerald-400">Tentang Ruang Baca</h4>
                            <p class="mt-1 text-[12px] text-slate-600 dark:text-slate-300 leading-relaxed">
                                Menyediakan koleksi buku akuntansi, perpajakan, audit, manajemen keuangan, serta literatur Tugas Akhir, Skripsi, dan Laporan PKL.
                            </p>
                        </div>
                        <div class="space-y-1 text-[11.5px] text-slate-600 dark:text-slate-300">
                            <div class="flex items-start gap-2">
                                <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span>Gedung Jurusan Akuntansi Polines, Tembalang, Semarang</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Senin–Kamis <strong class="text-slate-800 dark:text-slate-100 whitespace-nowrap">08.30–15.30 WIB</strong> • Jumat <strong class="text-slate-800 dark:text-slate-100 whitespace-nowrap">08.30–14.00 WIB</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                                <a href="https://wa.me/62895336384919?text=Halo%20Admin%20Perpustakaan%20Akuntansi%20Polines" target="_blank" class="inline-flex min-h-[32px] items-center font-semibold text-emerald-800 dark:text-emerald-400 hover:underline">
                                    WA Pustakawan: 0895-3363-84919
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Bento Card 2: Navigasi Portal (3 Cols) -->
                    <div class="md:col-span-6 lg:col-span-3 rounded-[14px] bg-white/55 dark:bg-white/5 border border-white/80 dark:border-white/10 p-3 flex flex-col justify-between">
                        <div>
                            <h4 class="text-[11px] font-bold uppercase tracking-[0.1em] text-emerald-700 dark:text-emerald-400">Akses Cepat</h4>
                            <ul class="mt-1.5 grid grid-cols-2 lg:grid-cols-1 gap-0.5 text-[12px] font-medium">
                                <li>
                                    <a href="{{ route('beranda') }}" class="inline-flex min-h-[36px] sm:min-h-[28px] items-center text-slate-600 dark:text-slate-300 hover:text-emerald-800 dark:hover:text-emerald-400 transition-colors">Beranda</a>
                                </li>
                                <li>
                                    <a href="{{ route('katalog') }}" class="inline-flex min-h-[36px] sm:min-h-[28px] items-center text-slate-600 dark:text-slate-300 hover:text-emerald-800 dark:hover:text-emerald-400 transition-colors">Katalog Koleksi</a>
                                </li>
                                <li>
                                    <a href="{{ route('tata-tertib') }}" class="inline-flex min-h-[36px] sm:min-h-[28px] items-center text-slate-600 dark:text-slate-300 hover:text-emerald-800 dark:hover:text-emerald-400 transition-colors">Tata Tertib &amp; Denda</a>
                                </li>
                                @if(session('student_id'))
                                    <li>
                                        <a href="{{ route('pinjaman') }}" class="inline-flex min-h-[36px] sm:min-h-[28px] items-center text-slate-600 dark:text-slate-300 hover:text-emerald-800 dark:hover:text-emerald-400 transition-colors">Pinjaman Saya</a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('masuk') }}" class="inline-flex min-h-[36px] sm:min-h-[28px] items-center text-slate-600 dark:text-slate-300 hover:text-emerald-800 dark:hover:text-emerald-400 transition-colors">Masuk Mahasiswa</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Bento Card 3: Statistik Koleksi Live (4 Cols) -->
                    <div class="md:col-span-12 lg:col-span-4 rounded-[14px] bg-white/55 dark:bg-white/5 border border-white/80 dark:border-white/10 p-3 flex flex-col justify-between gap-2">
                        <div class="flex items-center justify-between">
                            <h4 class="text-[11px] font-bold uppercase tracking-[0.1em] text-emerald-700 dark:text-emerald-400">Statistik Koleksi</h4>
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-700 dark:text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Langsung
                            </span>
                        </div>
                        <div class="grid grid-cols-3 lg:grid-cols-1 gap-1.5 text-[11.5px]">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between rounded-[8px] bg-white/75 dark:bg-white/5 border border-white/80 dark:border-white/10 px-3 py-1.5">
                                <span class="text-slate-500">Total Judul</span>
                                <span class="font-bold text-slate-900 tabular-nums">{{ $footerTotalTitles }} Judul</span>
                            </div>
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between rounded-[8px] bg-white/75 dark:bg-white/5 border border-white/80 dark:border-white/10 px-3 py-1.5">
                                <span class="text-slate-500">Total Koleksi</span>
                                <span class="font-bold text-slate-900 tabular-nums">{{ $footerTotalCopies }} Eksemplar</span>
                            </div>
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between rounded-[8px] bg-white/75 dark:bg-white/5 border border-white/80 dark:border-white/10 px-3 py-1.5">
                                <span class="text-slate-500">Siap Dipinjam</span>
                                <span class="font-bold text-emerald-700 dark:text-emerald-400 tabular-nums">{{ $footerAvailableCopies }} Eksemplar</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Copyright Bar -->
                <div class="relative z-10 mt-3.5 pt-3 border-t border-slate-200/60 dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-slate-500">
                    <p>© {{ date('Y') }} Perpustakaan Jurusan Akuntansi — Politeknik Negeri Semarang.</p>
                </div>
            </footer>
        </div>
    </main>

    <!-- SCROLL EDGE EFFECT (Bottom scrim beneath floating mobile tab bar) -->
    <div class="scroll-edge-bottom pointer-events-none fixed inset-x-0 bottom-0 z-20 h-24 lg:hidden"></div>

    <!-- MOBILE BOTTOM NAVIGATION (Apple HIG Floating Liquid Tab Bar, concentric 28px = 22px + p-1.5 6px, min 44px height) -->
    <nav id="mobileTabBar" class="glass-topbar safe-bottom-nav fixed left-3 right-3 z-30 flex items-center justify-around rounded-[28px] p-1.5 lg:hidden shadow-2xl">
        <a href="{{ route('beranda') }}" class="apple-press flex min-h-[48px] flex-1 flex-col items-center justify-center gap-0.5 rounded-[22px] px-2 py-1.5 text-[11px] font-semibold transition-all duration-300 active:scale-90 {{ request()->routeIs('beranda') ? 'bg-white/95 text-emerald-800 shadow-xs dark:bg-emerald-500/20 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
            <span>Beranda</span>
        </a>
        <a href="{{ route('katalog') }}" class="apple-press flex min-h-[48px] flex-1 flex-col items-center justify-center gap-0.5 rounded-[22px] px-2 py-1.5 text-[11px] font-semibold transition-all duration-300 active:scale-90 {{ request()->routeIs('katalog', 'buku.detail') ? 'bg-white/95 text-emerald-800 shadow-xs dark:bg-emerald-500/20 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400' }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
            <span>Katalog</span>
        </a>
        @if(session('student_id'))
            <a href="{{ route('pinjaman') }}" class="apple-press flex min-h-[48px] flex-1 flex-col items-center justify-center gap-0.5 rounded-[22px] px-2 py-1.5 text-[11px] font-semibold transition-all duration-300 active:scale-90 {{ request()->routeIs('pinjaman') ? 'bg-white/95 text-emerald-800 shadow-xs dark:bg-emerald-500/20 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                <span>Pinjaman</span>
            </a>
            <a href="{{ route('riwayat') }}" class="apple-press flex min-h-[48px] flex-1 flex-col items-center justify-center gap-0.5 rounded-[22px] px-2 py-1.5 text-[11px] font-semibold transition-all duration-300 active:scale-90 {{ request()->routeIs('riwayat') ? 'bg-white/95 text-emerald-800 shadow-xs dark:bg-emerald-500/20 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Riwayat</span>
            </a>
        @else
            <a href="{{ route('tata-tertib') }}" class="apple-press flex min-h-[48px] flex-1 flex-col items-center justify-center gap-0.5 rounded-[22px] px-2 py-1.5 text-[11px] font-semibold transition-all duration-300 active:scale-90 {{ request()->routeIs('tata-tertib') ? 'bg-white/95 text-emerald-800 shadow-xs dark:bg-emerald-500/20 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                <span>Aturan</span>
            </a>
            <a href="{{ route('masuk') }}" class="apple-press flex min-h-[48px] flex-1 flex-col items-center justify-center gap-0.5 rounded-[22px] px-2 py-1.5 text-[11px] font-semibold transition-all duration-300 active:scale-90 {{ request()->routeIs('masuk', 'daftar') ? 'bg-white/95 text-emerald-800 shadow-xs dark:bg-emerald-500/20 dark:text-emerald-300' : 'text-slate-500 dark:text-slate-400' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                <span>Masuk</span>
            </a>
        @endif
    </nav>

    <!-- Apple Motion Engine: Sidebar, Theme, Popovers, Modals, Gliding Segment Thumbs & Scroll Reveals -->
    <script>
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('perpus_theme', isDark ? 'dark' : 'light');
        }

        function toggleSidebarCollapse() {
            const isCurrentlyCollapsed = document.documentElement.classList.contains('sidebar-collapsed') ||
                                         document.body.classList.contains('sidebar-collapsed');
            const toggleIcon = document.querySelector('#sidebarToggleBtn svg');
            if (toggleIcon) {
                toggleIcon.style.transform = isCurrentlyCollapsed ? 'scaleX(1)' : 'scaleX(-1)';
            }

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

        function closeGlassPopover(el) {
            if (!el || el.classList.contains('hidden')) return;
            el.classList.add('is-closing');
            setTimeout(function() {
                el.classList.add('hidden');
                el.classList.remove('is-closing');
            }, 170);
        }

        function openAppleModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.classList.remove('hidden', 'is-closing');
        }

        function closeAppleModal(id) {
            const modal = document.getElementById(id);
            if (!modal || modal.classList.contains('hidden')) return;
            modal.classList.add('is-closing');
            setTimeout(function() {
                modal.classList.add('hidden');
                modal.classList.remove('is-closing');
            }, 200);
        }

        function positionGlassPopover(popoverEl, triggerEl) {
            if (!popoverEl) return;
            const header = document.getElementById('appHeader');
            if (window.innerWidth < 640) {
                const headerBottom = header ? header.getBoundingClientRect().bottom : 132;
                popoverEl.style.top = Math.round(headerBottom + 10) + 'px';
                popoverEl.style.left = '12px';
                popoverEl.style.right = '12px';
            } else {
                const btnRect = triggerEl ? triggerEl.getBoundingClientRect() : (header ? header.getBoundingClientRect() : { bottom: 96, right: window.innerWidth - 24 });
                popoverEl.style.top = Math.round(btnRect.bottom + 12) + 'px';
                popoverEl.style.left = 'auto';
                popoverEl.style.right = Math.max(16, Math.round(window.innerWidth - btnRect.right)) + 'px';
            }
        }

        function toggleGlassDropdown(targetId, otherId, triggerEl) {
            const target = document.getElementById(targetId);
            const other = otherId ? document.getElementById(otherId) : null;
            if (other && !other.classList.contains('hidden')) {
                closeGlassPopover(other);
            }
            if (!target) return;
            if (target.classList.contains('hidden')) {
                positionGlassPopover(target, triggerEl);
                target.classList.remove('hidden', 'is-closing');
            } else {
                closeGlassPopover(target);
            }
        }

        function moveSegmentThumb(track, targetEl, animate) {
            if (!track || !targetEl) return;
            const left = targetEl.offsetLeft;
            const width = targetEl.offsetWidth;
            if (width <= 0) return;

            let thumb = track.querySelector('.segment-thumb');
            if (!thumb) {
                thumb = document.createElement('span');
                thumb.className = 'segment-thumb';
                track.classList.add('has-thumb');
                track.prepend(thumb);
            }
            track._activeSegmentEl = targetEl;
            if (!animate) {
                const prevTrans = thumb.style.transition;
                thumb.style.transition = 'none';
                thumb.style.width = width + 'px';
                thumb.style.transform = 'translateX(' + left + 'px)';
                thumb.offsetHeight;
                thumb.style.transition = prevTrans || '';
            } else {
                thumb.style.width = width + 'px';
                thumb.style.transform = 'translateX(' + left + 'px)';
            }
        }

        let currentSpotlightIdx = 0;
        let spotlightTimer = null;

        function goSpotlight(index) {
            const spotlightSlides = document.querySelectorAll('[data-spotlight-slide]');
            const spotlightDots = document.querySelectorAll('[data-spotlight-dot]');
            const spotlightCounter = document.getElementById('spotlightCounter');
            if (spotlightSlides.length === 0) return;

            const total = spotlightSlides.length;
            currentSpotlightIdx = ((index % total) + total) % total;

            spotlightSlides.forEach(function(slide, idx) {
                if (idx === currentSpotlightIdx) {
                    slide.classList.remove('hidden');
                    slide.classList.add('is-active', 'flex');
                } else {
                    slide.classList.add('hidden');
                    slide.classList.remove('is-active', 'flex');
                }
            });

            spotlightDots.forEach(function(dot, idx) {
                if (idx === currentSpotlightIdx) {
                    dot.classList.add('is-active', 'bg-emerald-600', 'dark:bg-emerald-400');
                    dot.classList.remove('bg-slate-300', 'dark:bg-slate-600');
                } else {
                    dot.classList.remove('is-active', 'bg-emerald-600', 'dark:bg-emerald-400');
                    dot.classList.add('bg-slate-300', 'dark:bg-slate-600');
                }
            });

            if (spotlightCounter) {
                spotlightCounter.textContent = (currentSpotlightIdx + 1) + ' / ' + total;
            }
            resetSpotlightAuto();
        }

        function stepSpotlight(delta) {
            goSpotlight(currentSpotlightIdx + delta);
        }

        function resetSpotlightAuto() {
            if (spotlightTimer) clearInterval(spotlightTimer);
            const spotlightSlides = document.querySelectorAll('[data-spotlight-slide]');
            if (spotlightSlides.length > 1) {
                spotlightTimer = setInterval(function() {
                    goSpotlight(currentSpotlightIdx + 1);
                }, 6500);
            }
        }

        function switchBerandaShelf(shelfKey, btnEl) {
            const track = document.getElementById('berandaShelfTabs');
            if (track && btnEl) {
                track.querySelectorAll('button').forEach(function(btn) {
                    btn.classList.remove('is-active', 'text-emerald-800', 'dark:text-emerald-300');
                    btn.classList.add('text-slate-600', 'dark:text-slate-300');
                });
                btnEl.classList.add('is-active', 'text-emerald-800', 'dark:text-emerald-300');
                btnEl.classList.remove('text-slate-600');
                moveSegmentThumb(track, btnEl, true);
            }

            document.querySelectorAll('[data-shelf-panel]').forEach(function(panel) {
                if (panel.dataset.shelfPanel === shelfKey) {
                    panel.classList.remove('hidden');
                    panel.classList.add('is-active', 'grid');
                } else {
                    panel.classList.add('hidden');
                    panel.classList.remove('is-active', 'grid');
                }
            });
        }

        function syncAllSegmentThumbs() {
            document.querySelectorAll('.segment-track').forEach(function(track) {
                const activeEl = track.querySelector('a.bg-white, button.is-active');
                if (activeEl) moveSegmentThumb(track, activeEl, false);
            });
        }

        function initAppleSegments(animateFromPrev) {
            if (typeof ResizeObserver !== 'undefined' && !window._appleSegmentResizeObs) {
                window._appleSegmentResizeObs = new ResizeObserver(function(entries) {
                    const seenTracks = new Set();
                    entries.forEach(function(entry) {
                        const track = entry.target.classList && entry.target.classList.contains('segment-track')
                            ? entry.target
                            : (entry.target.closest ? entry.target.closest('.segment-track') : null);
                        if (track && !seenTracks.has(track)) {
                            seenTracks.add(track);
                            const activeEl = track.querySelector('a.bg-white, button.is-active');
                            if (activeEl) moveSegmentThumb(track, activeEl, false);
                        }
                    });
                });
            }

            document.querySelectorAll('.segment-track').forEach(function(track, idx) {
                const activeLink = track.querySelector('a.bg-white, button.is-active');
                if (!activeLink) return;

                if (window._appleSegmentResizeObs) {
                    window._appleSegmentResizeObs.observe(track);
                    track.querySelectorAll(':scope > a, :scope > button').forEach(function(btn) {
                        window._appleSegmentResizeObs.observe(btn);
                    });
                }

                const storageKey = 'apple_seg_prev_' + window.location.pathname + '_' + idx;
                const prevLeft = sessionStorage.getItem(storageKey);

                if (track.querySelector('a') && prevLeft !== null) {
                    const prev = JSON.parse(prevLeft);
                    let thumb = track.querySelector('.segment-thumb');
                    if (!thumb) {
                        thumb = document.createElement('span');
                        thumb.className = 'segment-thumb';
                        track.classList.add('has-thumb');
                        track.prepend(thumb);
                    }
                    thumb.style.transition = 'none';
                    thumb.style.width = prev.w + 'px';
                    thumb.style.transform = 'translateX(' + prev.l + 'px)';
                    thumb.offsetHeight;
                    thumb.style.transition = '';
                    requestAnimationFrame(function() {
                        moveSegmentThumb(track, activeLink, true);
                    });
                    sessionStorage.removeItem(storageKey);
                } else {
                    moveSegmentThumb(track, activeLink, Boolean(animateFromPrev));
                }

                // Re-sync after Tailwind Play CDN JIT classes & webfonts settle on first load / PJAX swap
                requestAnimationFrame(function() {
                    const currentActive = track.querySelector('a.bg-white, button.is-active');
                    if (currentActive) moveSegmentThumb(track, currentActive, false);
                });
                setTimeout(function() {
                    const currentActive = track.querySelector('a.bg-white, button.is-active');
                    if (currentActive) moveSegmentThumb(track, currentActive, false);
                }, 120);

                track.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        const currentActive = track.querySelector('a.bg-white');
                        if (currentActive) {
                            sessionStorage.setItem(storageKey, JSON.stringify({
                                l: currentActive.offsetLeft,
                                w: currentActive.offsetWidth
                            }));
                        }
                    });
                });
            });

            if (document.fonts && document.fonts.ready && !window._appleSegmentFontsHooked) {
                window._appleSegmentFontsHooked = true;
                document.fonts.ready.then(syncAllSegmentThumbs);
                window.addEventListener('load', syncAllSegmentThumbs);
            }
        }

        function initAppleReveals() {
            const reveals = document.querySelectorAll('.apple-reveal');
            if ('IntersectionObserver' in window && reveals.length > 0) {
                const obs = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            obs.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.08, rootMargin: '0px 0px -24px 0px' });
                reveals.forEach(function(el) { obs.observe(el); });
            } else {
                reveals.forEach(function(el) { el.classList.add('is-visible'); });
            }
        }

        function updateActiveChromeLinks(pathname) {
            const matchRoute = function(hrefPath) {
                if (hrefPath === '/') return pathname === '/';
                if (hrefPath === '/katalog') return pathname === '/katalog' || pathname.startsWith('/buku/');
                return pathname === hrefPath || pathname.startsWith(hrefPath + '/');
            };

            // Desktop sidebar links
            document.querySelectorAll('#appSidebar nav a').forEach(function(a) {
                try {
                    const p = new URL(a.href, window.location.origin).pathname;
                    const active = matchRoute(p);
                    const icon = a.querySelector('svg');
                    a.classList.toggle('bg-white/95', active);
                    a.classList.toggle('font-semibold', active);
                    a.classList.toggle('text-emerald-800', active);
                    a.classList.toggle('text-slate-600', !active);
                    if (icon) {
                        icon.classList.toggle('text-emerald-700', active);
                        icon.classList.toggle('text-slate-500', !active);
                        if (p === '/favorit') {
                            icon.setAttribute('fill', active ? 'currentColor' : 'none');
                        }
                    }
                } catch (_) {}
            });

            // Mobile bottom tab bar links
            document.querySelectorAll('#mobileTabBar a').forEach(function(a) {
                try {
                    const p = new URL(a.href, window.location.origin).pathname;
                    const active = matchRoute(p);
                    a.classList.toggle('bg-white/95', active);
                    a.classList.toggle('text-emerald-800', active);
                    a.classList.toggle('shadow-xs', active);
                    a.classList.toggle('dark:bg-emerald-500/20', active);
                    a.classList.toggle('dark:text-emerald-300', active);
                    a.classList.toggle('text-slate-500', !active);
                    a.classList.toggle('dark:text-slate-400', !active);
                } catch (_) {}
            });
        }

        function renderQrCode(canvasId, value) {
            const draw = function() {
                const canvas = document.getElementById(canvasId);
                if (!canvas || typeof window.QRious === 'undefined') return;
                try {
                    new QRious({
                        element: canvas,
                        value: value,
                        size: 160,
                        backgroundAlpha: 0,
                        foreground: '#0f172a'
                    });
                } catch (e) { console.error(e); }
            };
            if (typeof window.QRious === 'undefined') {
                let s = document.getElementById('qrious-cdn-lib');
                if (!s) {
                    s = document.createElement('script');
                    s.id = 'qrious-cdn-lib';
                    s.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js';
                    document.head.appendChild(s);
                }
                s.addEventListener('load', draw);
            } else {
                draw();
            }
        }

        function initCurrentPage(isSoftSwap) {
            currentSpotlightIdx = 0;
            resetSpotlightAuto();
            initAppleSegments(isSoftSwap);
            initAppleReveals();

            const stage = document.getElementById('appMainStage');
            if (stage) {
                // Remove stage transform class once entrance finishes so fixed descendants never anchor to #appMainStage
                setTimeout(function() {
                    stage.classList.remove('apple-stage-enter');
                }, 250);

                // Teleport modals onto document.body so fixed inset-0 always covers the full viewport (including sidebar & topbar)
                stage.querySelectorAll('.apple-modal-root').forEach(function(modalEl) {
                    document.body.appendChild(modalEl);
                });
            }

            // Execute inline scripts injected inside #appMainStage after PJAX swap
            if (isSoftSwap && stage) {
                    const inlineScripts = Array.from(stage.querySelectorAll('script'));
                    const executeScripts = function() {
                        inlineScripts.forEach(function(oldScript) {
                            const s = document.createElement('script');
                            s.textContent = oldScript.textContent;
                            oldScript.replaceWith(s);
                        });
                    };

                    if (stage.querySelector('canvas[id^="qr_"], #successQrCanvas') && typeof window.QRious === 'undefined') {
                        const qrLib = document.createElement('script');
                        qrLib.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js';
                        qrLib.onload = executeScripts;
                        document.head.appendChild(qrLib);
                    } else {
                        executeScripts();
                    }
            }
        }

        const applePageCache = new Map();
        const applePendingFetches = new Map();
        const CACHE_TTL_MS = 45000;

        function canCachePjaxUrl(urlObj) {
            const livePaths = ['/pinjaman', '/riwayat', '/favorit'];
            return !livePaths.some(function(p) {
                return urlObj.pathname === p || urlObj.pathname.startsWith(p + '/');
            });
        }

        function fetchAppleHtml(url, allowCache) {
            const now = Date.now();
            if (allowCache && applePageCache.has(url)) {
                const entry = applePageCache.get(url);
                if (now - entry.ts < CACHE_TTL_MS) {
                    return Promise.resolve(entry);
                }
                applePageCache.delete(url);
            }
            if (applePendingFetches.has(url)) {
                return applePendingFetches.get(url);
            }
            const p = fetch(url, {
                headers: { 'X-Requested-With': 'ApplePJAX', 'Accept': 'text/html' },
                credentials: 'same-origin'
            }).then(function(res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.text().then(function(html) {
                    const entry = { html: html, finalUrl: res.url || url, ts: Date.now() };
                    if (allowCache) {
                        applePageCache.set(url, entry);
                    }
                    applePendingFetches.delete(url);
                    return entry;
                });
            }).catch(function(err) {
                applePendingFetches.delete(url);
                throw err;
            });
            applePendingFetches.set(url, p);
            return p;
        }

        function prefetchAppleUrl(href) {
            try {
                const urlObj = new URL(href, window.location.origin);
                if (!isPjaxEligible(urlObj)) return;
                if (urlObj.href === window.location.href) return;
                fetchAppleHtml(urlObj.href, canCachePjaxUrl(urlObj)).catch(function() {});
            } catch (_) {}
        }

        let isNavigating = false;
        async function appleSoftNavigate(targetUrl, pushState) {
            if (isNavigating) return;
            const stage = document.getElementById('appMainStage');
            const bar = document.getElementById('appleNavProgress');
            if (!stage) {
                window.location.href = targetUrl;
                return;
            }

            isNavigating = true;
            closeGlassPopover(document.getElementById('notifDropdown'));
            closeGlassPopover(document.getElementById('userMenuDropdown'));

            const targetObj = new URL(targetUrl, window.location.origin);
            const useCache = canCachePjaxUrl(targetObj);
            const hasInstantHit = useCache && applePageCache.has(targetObj.href) && (Date.now() - applePageCache.get(targetObj.href).ts < CACHE_TTL_MS);

            // Instant 0ms optimistic tab feedback so mobile feels instantaneous
            updateActiveChromeLinks(targetObj.pathname);

            let barTimer = null;
            if (!hasInstantHit) {
                barTimer = setTimeout(function() {
                    if (bar) {
                        bar.style.opacity = '1';
                        bar.style.width = '72%';
                    }
                }, 60);
                stage.classList.add('apple-stage-leaving');
            }

            try {
                const result = await fetchAppleHtml(targetObj.href, useCache);
                const doc = new DOMParser().parseFromString(result.html, 'text/html');
                const nextStage = doc.getElementById('appMainStage');

                if (!nextStage) {
                    window.location.href = result.finalUrl || targetUrl;
                    return;
                }

                const parsedUrl = new URL(result.finalUrl || targetUrl, window.location.origin);
                if (pushState) {
                    history.pushState({ appleUrl: parsedUrl.href }, '', parsedUrl.href);
                }

                document.title = doc.title || document.title;
                document.querySelectorAll('body > .apple-modal-root').forEach(function(oldModal) {
                    oldModal.remove();
                });
                stage.innerHTML = nextStage.innerHTML;
                stage.classList.remove('apple-stage-leaving', 'apple-stage-enter');
                void stage.offsetWidth;
                stage.classList.add('apple-stage-enter');

                const nextQ = parsedUrl.searchParams.get('q') || '';
                document.querySelectorAll('#appHeader input[name="q"]').forEach(function(inp) {
                    inp.value = nextQ;
                });

                updateActiveChromeLinks(parsedUrl.pathname);

                if (parsedUrl.hash) {
                    const anchor = document.querySelector(parsedUrl.hash);
                    if (anchor) anchor.scrollIntoView({ behavior: 'smooth' });
                } else {
                    window.scrollTo(0, 0);
                }

                initCurrentPage(true);
            } catch (_) {
                window.location.href = targetUrl;
                return;
            } finally {
                if (barTimer) clearTimeout(barTimer);
                if (bar) {
                    bar.style.width = '100%';
                    setTimeout(function() {
                        bar.style.opacity = '0';
                        setTimeout(function() { bar.style.width = '0%'; }, 160);
                    }, 80);
                }
                isNavigating = false;
            }
        }

        function isPjaxEligible(urlObj) {
            if (urlObj.origin !== window.location.origin) return false;
            const excludedPrefixes = ['/masuk', '/daftar', '/keluar', '/admin'];
            return !excludedPrefixes.some(function(prefix) {
                return urlObj.pathname === prefix || urlObj.pathname.startsWith(prefix + '/');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const toggleIcon = document.querySelector('#sidebarToggleBtn svg');
            if (toggleIcon && document.documentElement.classList.contains('sidebar-collapsed')) {
                toggleIcon.style.transform = 'scaleX(-1)';
            }

            initCurrentPage(false);

            // Warm-cache primary tabs right after first paint so Beranda <-> Katalog <-> Aturan switch in < 0.25s!
            setTimeout(function() {
                document.querySelectorAll('#appSidebar nav a[href], #mobileTabBar a[href]').forEach(function(a) {
                    try {
                        const u = new URL(a.href, window.location.origin);
                        if (isPjaxEligible(u) && canCachePjaxUrl(u)) {
                            prefetchAppleUrl(u.href);
                        }
                    } catch (_) {}
                });
            }, 250);

            // Pre-fetch the instant a finger touches or mouse enters any portal link (~100ms network head start)
            const warmHandler = function(e) {
                if (!e.target || typeof e.target.closest !== 'function') return;
                const link = e.target.closest('a[href]');
                if (!link || link.target === '_blank' || link.hasAttribute('data-no-pjax')) return;
                prefetchAppleUrl(link.href);
            };
            document.addEventListener('pointerdown', warmHandler, { passive: true });
            document.addEventListener('mouseenter', warmHandler, { capture: true, passive: true });

            window.addEventListener('resize', function() {
                document.querySelectorAll('.segment-track').forEach(function(track) {
                    const activeEl = track.querySelector('a.bg-white, button.is-active');
                    if (activeEl) moveSegmentThumb(track, activeEl, false);
                });
            });

            // Intercept internal portal links for <1s transitions (keeps fixed header & mobile tab bar solid!)
            document.addEventListener('click', function(e) {
                if (!e.target || typeof e.target.closest !== 'function') return;
                const link = e.target.closest('a[href]');
                if (!link) return;
                if (link.target === '_blank' || link.hasAttribute('download') || link.hasAttribute('data-no-pjax') || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('https://wa.me')) return;
                try {
                    const url = new URL(link.href, window.location.origin);
                    if (!isPjaxEligible(url)) return;
                    if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;
                    e.preventDefault();
                    appleSoftNavigate(url.href, true);
                } catch (_) {}
            });

            // Intercept GET search forms cleanly
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form) return;
                if ((form.method || 'GET').toUpperCase() !== 'GET') {
                    applePageCache.clear();
                    return;
                }
                try {
                    const actionUrl = new URL(form.action || window.location.href, window.location.origin);
                    if (!isPjaxEligible(actionUrl)) return;
                    e.preventDefault();
                    const params = new URLSearchParams(new FormData(form));
                    actionUrl.search = params.toString();
                    if (document.getElementById('catalogRoot') && actionUrl.pathname === '/katalog' && typeof window.catalogFetchUrl === 'function') {
                        window.catalogFetchUrl(actionUrl.href, true);
                        return;
                    }
                    appleSoftNavigate(actionUrl.href, true);
                } catch (_) {}
            });

            window.addEventListener('popstate', function() {
                const cur = new URL(window.location.href);
                if (document.getElementById('catalogRoot') && cur.pathname === '/katalog' && typeof window.catalogHandlePopState === 'function') {
                    window.catalogHandlePopState(cur.href);
                    return;
                }
                if (isPjaxEligible(cur)) {
                    appleSoftNavigate(cur.href, false);
                } else {
                    window.location.reload();
                }
            });
        });

        // Close dropdowns cleanly with spring-out animation when clicking outside trigger OR popover sheet
        document.addEventListener('click', function(e) {
            const userMenu = document.getElementById('userMenuDropdown');
            const userWrapper = document.getElementById('userMenuWrapper');
            if (userMenu && (!userWrapper || !userWrapper.contains(e.target)) && !userMenu.contains(e.target)) {
                closeGlassPopover(userMenu);
            }

            const notifMenu = document.getElementById('notifDropdown');
            const notifWrapper = document.getElementById('notifMenuWrapper');
            if (notifMenu && (!notifWrapper || !notifWrapper.contains(e.target)) && !notifMenu.contains(e.target)) {
                closeGlassPopover(notifMenu);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
