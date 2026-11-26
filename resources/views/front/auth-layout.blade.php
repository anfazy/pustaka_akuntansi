<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Masuk — Perpustakaan Jurusan Akuntansi')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-hmj-akuntansi.jpg') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Pre-load Dark Mode to avoid flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('perpus_theme');
            const systemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
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
        :root {
            --apple-ease: cubic-bezier(0.32, 0.72, 0, 1);
            --apple-spring: cubic-bezier(0.22, 1.36, 0.36, 1);
        }

        body {
            background: radial-gradient(1100px 650px at 85% -5%, rgba(52, 211, 153, 0.28), transparent 60%),
                        radial-gradient(850px 500px at 85% 15%, rgba(245, 158, 11, 0.08), transparent 50%),
                        radial-gradient(950px 600px at -5% 35%, rgba(167, 243, 208, 0.38), transparent 55%),
                        radial-gradient(1200px 750px at 65% 105%, rgba(110, 231, 183, 0.30), transparent 60%),
                        radial-gradient(850px 550px at 25% 75%, rgba(209, 250, 229, 0.45), transparent 55%),
                        linear-gradient(180deg, #f0fdf4 0%, #edf9f2 50%, #f4fbf7 100%);
            background-attachment: fixed;
            color: #0f172a;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            overflow-x: hidden;
            transition: background 0.45s var(--apple-ease), color 0.3s ease;
        }

        /* Apple Liquid Glass Card */
        .glass-card {
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.72) 0%, rgba(246, 252, 248, 0.45) 60%, rgba(255, 255, 255, 0.62) 100%);
            backdrop-filter: blur(40px) saturate(210%) brightness(102%);
            -webkit-backdrop-filter: blur(40px) saturate(210%) brightness(102%);
            border: 1px solid rgba(255, 255, 255, 0.88);
            border-top: 1.5px solid #ffffff;
            border-left: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: inset 0 1.5px 1px 0 #ffffff,
                        inset 0 -1px 1px 0 rgba(6, 78, 59, 0.04),
                        0 30px 70px -20px rgba(6, 78, 59, 0.18),
                        0 4px 16px -4px rgba(15, 23, 42, 0.05);
            transition: all 0.3s ease;
        }

        .glass-field {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.72) 0%, rgba(240, 249, 244, 0.58) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(15, 23, 42, 0.14);
            border-top: 1px solid rgba(15, 23, 42, 0.09);
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

        .btn-emerald {
            background: linear-gradient(180deg, 
                rgba(52, 211, 153, 0.95) 0%, 
                rgba(16, 185, 129, 0.90) 45%, 
                rgba(5, 150, 105, 0.96) 100%) !important;
            backdrop-filter: blur(20px) saturate(200%);
            -webkit-backdrop-filter: blur(20px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.45) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.9) !important;
            box-shadow: inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.85),
                        inset 0 -1.5px 1px 0 rgba(0, 0, 0, 0.2),
                        0 12px 28px -6px rgba(16, 185, 129, 0.5),
                        0 4px 10px -2px rgba(5, 150, 105, 0.25) !important;
            color: #ffffff !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
            position: relative;
            overflow: hidden;
            touch-action: manipulation;
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
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.45) 0%, rgba(255, 255, 255, 0.05) 100%);
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
            filter: brightness(1.08);
            transform: translateY(-2px) scale(1.015);
            box-shadow: inset 0 1.5px 1.5px 0 #ffffff,
                        inset 0 -1.5px 1px 0 rgba(0, 0, 0, 0.2),
                        0 16px 36px -6px rgba(16, 185, 129, 0.65),
                        0 6px 14px -2px rgba(5, 150, 105, 0.35) !important;
        }

        .btn-emerald:active {
            transform: translateY(0) scale(0.94);
            transition-duration: 90ms;
        }

        .btn-glass {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.78) 0%, rgba(240, 253, 244, 0.50) 100%);
            backdrop-filter: blur(24px) saturate(200%);
            -webkit-backdrop-filter: blur(24px) saturate(200%);
            border: 1px solid rgba(15, 23, 42, 0.10);
            border-top: 1.5px solid #ffffff;
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 6px 16px -6px rgba(6, 78, 59, 0.12);
            color: #0f172a;
            position: relative;
            overflow: hidden;
            touch-action: manipulation;
            transition: transform 0.38s var(--apple-spring),
                        background 0.24s ease,
                        border-color 0.24s ease,
                        box-shadow 0.28s var(--apple-ease);
        }

        .btn-glass:hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(240, 253, 244, 0.70) 100%);
            border-color: rgba(52, 211, 153, 0.55);
            transform: translateY(-2px) scale(1.012);
        }

        .btn-glass:active {
            transform: translateY(0) scale(0.94);
            transition-duration: 90ms;
        }

        .liquid-badge-emerald {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.20) 0%, rgba(5, 150, 105, 0.08) 100%);
            backdrop-filter: blur(16px) saturate(200%);
            -webkit-backdrop-filter: blur(16px) saturate(200%);
            border: 1px solid rgba(52, 211, 153, 0.45);
            border-top: 1.5px solid rgba(110, 231, 183, 0.9);
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.7), 0 4px 12px -3px rgba(5, 150, 105, 0.15);
            color: #064e3b;
        }

        /* Apple Liquid Bright Lemon-Yellow (Light, Sunny, Non-Dark) */
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
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0) 100%);
            border-radius: inherit;
            pointer-events: none;
        }

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
            transition: transform 0.38s var(--apple-spring), filter 0.2s ease, box-shadow 0.28s var(--apple-ease);
        }

        .liquid-icon-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 45%;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.6) 0%, transparent 100%);
            border-radius: inherit;
            pointer-events: none;
        }

        .liquid-icon-btn:hover {
            transform: translateY(-1.5px) scale(1.04);
            filter: brightness(1.04);
            box-shadow: inset 0 1.5px 1px 0 #ffffff, 0 8px 18px -4px rgba(6, 78, 59, 0.18);
        }

        .liquid-icon-btn:active {
            transform: translateY(0) scale(0.90);
            transition-duration: 85ms;
            filter: brightness(0.96);
        }

        @keyframes riseIn {
            from { opacity: 0; transform: translateY(18px) scale(0.975); filter: blur(6px); }
            to { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        .animate-rise {
            animation: riseIn 0.48s var(--apple-ease) both;
        }

        /* DARK MODE APPLE LIQUID GLASS STYLES */
        html.dark body {
            background: radial-gradient(1200px 700px at 75% -10%, rgba(16, 185, 129, 0.14), transparent 60%),
                        radial-gradient(900px 550px at 75% 15%, rgba(245, 158, 11, 0.08), transparent 55%),
                        radial-gradient(1100px 650px at -10% 40%, rgba(20, 184, 166, 0.10), transparent 60%),
                        radial-gradient(1100px 800px at 50% 100%, rgba(5, 150, 105, 0.14), transparent 65%),
                        linear-gradient(180deg, #06110c 0%, #07150f 50%, #050f0a 100%) !important;
            background-attachment: fixed !important;
            color: #f1f5f9;
        }

        html.dark .glass-card {
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 60%, rgba(16, 185, 129, 0.03) 100%),
                        rgba(11, 23, 18, 0.6) !important;
            backdrop-filter: blur(36px) saturate(200%);
            -webkit-backdrop-filter: blur(36px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: inset 0 1.5px 1px 0 rgba(255, 255, 255, 0.25),
                        0 30px 70px -20px rgba(0, 0, 0, 0.75),
                        0 4px 16px -4px rgba(0, 0, 0, 0.5) !important;
        }

        html.dark .glass-field {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.3) 0%, rgba(255, 255, 255, 0.02) 100%),
                        rgba(8, 18, 14, 0.6) !important;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            box-shadow: inset 0 1.5px 3px 0 rgba(0, 0, 0, 0.4),
                        inset 0 -1px 0 0 rgba(255, 255, 255, 0.08) !important;
            color: #f1f5f9;
        }

        html.dark .glass-field input,
        html.dark .glass-field select {
            color: #f8fafc;
        }

        html.dark .glass-field select option {
            background-color: #0f1a16;
            color: #f8fafc;
        }

        html.dark .glass-field:focus-within {
            background: rgba(12, 26, 20, 0.8) !important;
            border-color: #34d399 !important;
            border-top-color: #6ee7b7 !important;
            box-shadow: inset 0 1px 2px 0 rgba(0, 0, 0, 0.3),
                        0 0 0 3px rgba(16, 185, 129, 0.25) !important;
        }

        html.dark .liquid-icon-btn {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0.04) 100%) !important;
            backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.16) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.35) !important;
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.35), 0 4px 12px -4px rgba(0, 0, 0, 0.4) !important;
        }

        html.dark .btn-glass {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0.04) 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.16) !important;
            border-top: 1.5px solid rgba(255, 255, 255, 0.35) !important;
            color: #f8fafc !important;
        }

        html.dark .liquid-badge-emerald {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.25) 0%, rgba(5, 150, 105, 0.12) 100%) !important;
            border: 1px solid rgba(52, 211, 153, 0.35) !important;
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

        html.dark .text-slate-900 { color: #f8fafc !important; }
        html.dark .text-slate-800 { color: #f1f5f9 !important; }
        html.dark .text-slate-700 { color: #cbd5e1 !important; }
        html.dark .text-slate-600 { color: #94a3b8 !important; }
        html.dark .text-slate-500 { color: #94a3b8 !important; }
        html.dark .text-slate-400 { color: #64748b !important; }

        html.dark .text-emerald-800,
        html.dark .text-emerald-900 {
            color: #6ee7b7 !important;
        }

        html.dark .bg-emerald-50 {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.25) 0%, rgba(5, 150, 105, 0.12) 100%) !important;
            color: #6ee7b7 !important;
            border: 1px solid rgba(52, 211, 153, 0.35) !important;
            border-top: 1px solid rgba(110, 231, 183, 0.65) !important;
            box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.35) !important;
        }

        html.dark .bg-slate-100 {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.11) 0%, rgba(255, 255, 255, 0.03) 100%) !important;
            color: #cbd5e1 !important;
            border: 1px solid rgba(255, 255, 255, 0.13) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.3) !important;
        }

        html.dark .border-slate-200,
        html.dark .border-slate-200\/70,
        html.dark .border-slate-200\/60 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-3 sm:p-4 relative">

    <!-- FLOATING THEME TOGGLE -->
    <div class="fixed z-20" style="top: max(1rem, env(safe-area-inset-top)); right: max(1rem, env(safe-area-inset-right));">
        <button type="button"
                onclick="toggleDarkMode()"
                class="liquid-icon-btn grid h-9 w-9 place-items-center rounded-full text-slate-600 dark:text-amber-300 shadow-md hover:scale-105 transition-all"
                title="Ganti Mode Gelap / Terang"
                aria-label="Ganti Mode Gelap atau Terang">
            <!-- Sun icon (shown when dark) -->
            <svg class="hidden dark:block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
            </svg>
            <!-- Moon icon (shown when light) -->
            <svg class="block dark:hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
            </svg>
        </button>
    </div>

    <!-- MAIN CARD WRAPPER (Top padding clears floating theme toggle on all widths) -->
    <main class="relative z-10 w-full flex justify-center pt-16 pb-8">
        @yield('content')
    </main>

    <script>
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('perpus_theme', isDark ? 'dark' : 'light');
        }

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');
            if (eyeOpen && eyeClosed) {
                eyeOpen.classList.toggle('hidden', isHidden);
                eyeClosed.classList.toggle('hidden', !isHidden);
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
