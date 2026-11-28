@extends('front.auth-layout')

@section('title', 'Masuk — Perpustakaan Jurusan Akuntansi')

@section('content')
    <div class="glass-card w-full max-w-[370px] rounded-[28px] p-4 shadow-2xl animate-rise">
        <!-- Back to Beranda Link -->
        <div class="flex items-center justify-between mb-3.5">
            <a href="{{ route('beranda') }}" class="inline-flex min-h-[44px] sm:min-h-[34px] items-center gap-1.5 text-[11.5px] font-semibold text-slate-500 hover:text-emerald-800 dark:hover:text-emerald-400 transition-colors">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Beranda</span>
            </a>

            <span class="inline-flex items-center gap-1.5 rounded-full liquid-badge-emerald px-2.5 py-0.5 text-[11px] font-semibold">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                Mahasiswa
            </span>
        </div>

        <!-- Brand Header with Official HMJ Logo -->
        <div class="flex items-center gap-2.5">
            <div class="grid h-9 w-9 shrink-0 place-items-center rounded-[10px] bg-white p-0.5 shadow-sm shadow-emerald-950/15 border border-white/80 dark:border-white/20 overflow-hidden">
                <img src="{{ asset('images/logo-perpustakaan-akuntansi.jpg') }}" alt="Logo Perpustakaan Jurusan Akuntansi" class="h-full w-full object-contain">
            </div>
            <div>
                <span class="block text-[13.5px] font-bold leading-tight tracking-tight text-slate-900">Perpustakaan Jurusan Akuntansi</span>
                <span class="block text-[11px] text-emerald-800 dark:text-emerald-400 font-medium">Politeknik Negeri Semarang</span>
            </div>
        </div>

        <h1 class="mt-4 text-[20px] sm:text-[22px] font-bold tracking-tight text-slate-900">Masuk</h1>

        <!-- FLASH MESSAGES -->
        @if(session('error'))
            <div class="mt-4 flex items-center gap-2.5 rounded-2xl border border-rose-200 bg-rose-50/90 p-3 text-xs font-medium text-rose-800">
                <svg class="h-4 w-4 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="mt-4 flex items-center gap-2.5 rounded-2xl border border-emerald-200 bg-emerald-50/90 p-3 text-xs font-medium text-emerald-800">
                <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- LOGIN FORM -->
        <form action="{{ route('masuk.proses') }}" method="POST" class="mt-4 space-y-3">
            @csrf

            <div>
                <label for="nimInput" class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider mb-1">NIM Mahasiswa <span class="text-rose-500">*</span></label>
                <div class="glass-field rounded-[12px] px-3 py-2.5 sm:py-2">
                    <input type="text" name="nim" id="nimInput" value="{{ old('nim') }}" required placeholder="Contoh: 4.41.24.0.12" class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="passwordInput" class="block text-[11px] font-semibold text-slate-700 uppercase tracking-wider">Kata Sandi <span class="text-rose-500">*</span></label>
                    <button type="button" onclick="document.getElementById('modalForgotPw').classList.remove('hidden')" class="inline-flex min-h-[36px] sm:min-h-[28px] items-center text-[11.5px] font-semibold text-emerald-800 dark:text-emerald-400 hover:underline">
                        Lupa kata sandi?
                    </button>
                </div>
                <div class="glass-field relative flex items-center rounded-[12px] px-3 py-2.5 sm:py-2">
                    <input type="password" name="password" id="passwordInput" required placeholder="Masukkan kata sandi" class="w-full bg-transparent pr-10 text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    <button type="button" onclick="togglePasswordVisibility('passwordInput', this)" aria-label="Tampilkan atau sembunyikan kata sandi" class="absolute right-0.5 grid h-11 w-11 sm:h-8 sm:w-8 place-items-center rounded-full text-slate-400 hover:text-emerald-700 active:scale-95 transition">
                        <svg class="eye-open h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg class="eye-closed hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center pt-0.5">
                <label class="flex items-center gap-2 cursor-pointer text-[11.5px] text-slate-600 font-medium select-none">
                    <input type="checkbox" name="remember" class="h-3.5 w-3.5 rounded border-slate-300 text-emerald-600 accent-emerald-600 focus:ring-emerald-500">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <button type="submit" class="btn-emerald w-full min-h-[44px] rounded-full text-[13.5px] font-semibold mt-1">
                Masuk
            </button>
        </form>

        <!-- REGISTER LINK (Golden Action Pill) -->
        <div class="mt-4 pt-4 border-t border-slate-200/60 dark:border-white/10 text-center">
            <p class="text-[11.5px] text-slate-500 mb-2.5 font-medium">Belum terdaftar sebagai anggota perpustakaan?</p>
            <a href="{{ route('daftar') }}" class="btn-amber-gold inline-flex min-h-[44px] sm:min-h-[40px] w-full items-center justify-center rounded-full text-[13px] font-semibold shadow-md">
                Daftar Anggota Baru
            </a>
        </div>
    </div>

    <!-- MODAL LUPA KATA SANDI (Concentric 32px -> 16px, 44x44 close button, Apple vertical rounded-rectangle button stack) -->
    <div id="modalForgotPw" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-md">
        <div class="glass-card animate-rise relative w-full max-w-[420px] rounded-[32px] p-6 sm:p-8 shadow-2xl border border-white/80 dark:border-white/20">
            <!-- Close icon button (44x44 circle) -->
            <button type="button" onclick="document.getElementById('modalForgotPw').classList.add('hidden')" aria-label="Tutup modal" class="absolute right-3 top-3 grid h-11 w-11 place-items-center rounded-full text-slate-400 hover:bg-slate-200/60 dark:hover:bg-white/10 hover:text-slate-700 active:scale-95 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal Header -->
            <div class="flex items-center gap-3 mb-4">
                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-emerald-500/15 text-emerald-800 dark:text-emerald-400 border border-emerald-500/30">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 leading-tight">Lupa Kata Sandi?</h3>
                    <p class="text-xs text-slate-500 font-medium">Bantuan Reset Sandi Mahasiswa</p>
                </div>
            </div>

            <p class="text-[13px] text-slate-600 dark:text-slate-300 leading-relaxed">
                Untuk keamanan akun peminjaman buku, pengaturan ulang kata sandi dilakukan melalui verifikasi oleh petugas perpustakaan. Silakan hubungi admin perpustakaan melalui WhatsApp:
            </p>

            <!-- WA Admin Info Glass Card -->
            <div class="mt-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 p-4 text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-medium">No. WhatsApp Admin:</span>
                    <span class="font-bold text-emerald-900 dark:text-emerald-300 text-sm tracking-wide">0895-3363-84919</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Jam Pelayanan:</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-300">08.00 – 16.00 WIB</span>
                </div>
                <div class="pt-2 border-t border-emerald-500/20 text-slate-500">
                    💡 Cantumkan <strong class="text-slate-700 dark:text-slate-200">NIM</strong> dan <strong class="text-slate-700 dark:text-slate-200">Nama Lengkap</strong> pada pesan Anda.
                </div>
            </div>

            <!-- Direct WhatsApp CTA Button (Apple HIG vertical button stack: rounded-[14px], identical h-11 height) -->
            <div class="mt-6 space-y-2.5">
                <a href="https://wa.me/62895336384919?text=Halo%20Admin%20Perpustakaan%20Akuntansi%20Polines,%20saya%20ingin%20mengatur%20ulang%20kata%20sandi%20akun%20mahasiswa%20saya.%0A%0ANama:%20%0ANIM:%20"
                   target="_blank"
                   class="btn-emerald flex h-11 w-full items-center justify-center gap-2 rounded-[14px] text-sm font-semibold shadow-md">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.303c-.087.087-.179.182-.077.357.101.174.452.744.97 1.205.666.593 1.228.777 1.402.864.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.232-.144.39-.086s1.004.474 1.177.561c.174.087.289.13.332.203.044.072.044.419-.1 1.024z"/>
                    </svg>
                    <span>Hubungi via WhatsApp</span>
                </a>
                <button type="button" onclick="document.getElementById('modalForgotPw').classList.add('hidden')" class="btn-glass flex h-11 w-full items-center justify-center rounded-[14px] text-sm font-semibold text-slate-600 dark:text-slate-300">
                    Batal
                </button>
            </div>
        </div>
    </div>
@endsection
