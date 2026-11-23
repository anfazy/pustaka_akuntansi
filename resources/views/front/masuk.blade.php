@extends('front.auth-layout')

@section('title', 'Masuk — Pustaka Akuntansi')

@section('content')
    <div class="glass-card w-full max-w-[440px] rounded-[32px] p-8 sm:p-10 shadow-2xl animate-rise">
        <!-- Back to Beranda Link -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-emerald-800 dark:hover:text-emerald-400 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
        <div class="flex items-center gap-3.5">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-white p-1 shadow-md shadow-emerald-950/15 border border-white/80 dark:border-white/20 overflow-hidden">
                <img src="{{ asset('images/logo-hmj-akuntansi.jpg') }}" alt="Logo Pustaka Akuntansi" class="h-full w-full object-contain">
            </div>
            <div>
                <span class="block text-[18px] font-bold leading-tight tracking-tight text-slate-900">Pustaka Akuntansi</span>
                <span class="block text-xs text-emerald-800 dark:text-emerald-400 font-medium">Ruang Baca Jurusan Polines</span>
            </div>
        </div>

        <h1 class="mt-7 text-[28px] sm:text-[32px] font-bold tracking-tight text-slate-900">Masuk</h1>

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
        <form action="{{ route('masuk.proses') }}" method="POST" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">NIM Mahasiswa</label>
                <div class="glass-field rounded-2xl px-4 py-3">
                    <input type="text" name="nim" id="nimInput" value="{{ old('nim') }}" required placeholder="Contoh: 4.41.24.0.12" class="w-full bg-transparent text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                    <button type="button" onclick="document.getElementById('modalForgotPw').classList.remove('hidden')" class="text-xs font-semibold text-emerald-800 dark:text-emerald-400 hover:underline">
                        Lupa kata sandi?
                    </button>
                </div>
                <div class="glass-field rounded-2xl px-4 py-3">
                    <input type="password" name="password" id="passwordInput" required placeholder="Masukkan kata sandi" class="w-full bg-transparent text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                </div>
            </div>

            <div class="flex items-center pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-600 font-medium">
                    <input type="checkbox" name="remember" checked class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <button type="submit" class="btn-emerald w-full h-12 rounded-full text-[15px] font-semibold mt-2">
                Masuk
            </button>
        </form>

        <!-- REGISTER LINK (Golden Action Pill) -->
        <div class="mt-6 pt-5 border-t border-slate-200/60 dark:border-white/10 text-center">
            <p class="text-xs text-slate-500 mb-3 font-medium">Belum terdaftar sebagai anggota perpustakaan?</p>
            <a href="{{ route('daftar') }}" class="btn-amber-gold inline-flex h-11 w-full items-center justify-center rounded-full text-sm font-semibold shadow-md">
                Daftar Anggota Baru
            </a>
        </div>
    </div>

    <!-- MODAL LUPA KATA SANDI (Apple Liquid Glass) -->
    <div id="modalForgotPw" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-md animate-fade">
        <div class="glass-card relative w-full max-w-[420px] rounded-[32px] p-7 sm:p-8 shadow-2xl border border-white/80 dark:border-white/20">
            <!-- Close icon button -->
            <button type="button" onclick="document.getElementById('modalForgotPw').classList.add('hidden')" class="absolute right-5 top-5 grid h-8 w-8 place-items-center rounded-full text-slate-400 hover:bg-slate-200/60 dark:hover:bg-white/10 text-slate-700 transition">
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
                    <span class="font-bold text-emerald-900 dark:text-emerald-300 text-sm tracking-wide">0887895627873</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-medium">Jam Pelayanan:</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-300">08.00 – 16.00 WIB</span>
                </div>
                <div class="pt-2 border-t border-emerald-500/20 text-slate-500">
                    💡 Cantumkan <strong class="text-slate-700 dark:text-slate-200">NIM</strong> dan <strong class="text-slate-700 dark:text-slate-200">Nama Lengkap</strong> pada pesan Anda.
                </div>
            </div>

            <!-- Direct WhatsApp CTA Button -->
            <div class="mt-6 space-y-2">
                <a href="https://wa.me/62887895627873?text=Halo%20Admin%20Perpustakaan%20Akuntansi%20Polines,%20saya%20ingin%20mengatur%20ulang%20kata%20sandi%20akun%20mahasiswa%20saya.%0A%0ANama:%20%0ANIM:%20"
                   target="_blank"
                   class="btn-emerald flex w-full items-center justify-center gap-2 rounded-full py-3 text-sm font-semibold shadow-md">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.287.043.087.072.188.014.303-.058.116-.087.188-.173.289l-.26.303c-.087.087-.179.182-.077.357.101.174.452.744.97 1.205.666.593 1.228.777 1.402.864.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.232-.144.39-.086s1.004.474 1.177.561c.174.087.289.13.332.203.044.072.044.419-.1 1.024z"/>
                    </svg>
                    <span>Hubungi via WhatsApp</span>
                </a>
                <button type="button" onclick="document.getElementById('modalForgotPw').classList.add('hidden')" class="btn-glass flex w-full items-center justify-center rounded-full py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    Batal
                </button>
            </div>
        </div>
    </div>
@endsection
