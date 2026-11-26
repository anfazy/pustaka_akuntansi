@extends('front.auth-layout')

@section('title', 'Verifikasi Email Anda — Perpustakaan Jurusan Akuntansi')

@section('content')
    <div class="glass-card w-full max-w-[500px] rounded-[32px] p-6 sm:p-8 shadow-2xl animate-rise text-center">
        <!-- Brand Header -->
        <div class="flex items-center justify-center gap-3 mb-6">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-white p-1 shadow-md shadow-emerald-950/15 border border-white/80 dark:border-white/20 overflow-hidden">
                <img src="{{ asset('images/logo-hmj-akuntansi.jpg') }}" alt="Logo Perpustakaan Jurusan Akuntansi" class="h-full w-full object-contain">
            </div>
            <div class="text-left">
                <span class="block text-[16px] font-bold leading-tight tracking-tight text-slate-900">Perpustakaan Jurusan Akuntansi</span>
                <span class="block text-xs text-emerald-800 dark:text-emerald-400 font-medium">Ruang Baca Jurusan Polines</span>
            </div>
        </div>

        <!-- Mail Icon Badge -->
        <div class="mx-auto mb-5 grid h-16 w-16 place-items-center rounded-3xl bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 shadow-inner">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>

        <h1 class="text-[24px] sm:text-[28px] font-bold tracking-tight text-slate-900">Verifikasi Email Anda</h1>

        @if(session('success'))
            <div class="mt-4 flex items-center gap-2.5 rounded-2xl border border-emerald-200 bg-emerald-50/90 p-3 text-left text-xs font-medium text-emerald-800">
                <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="mt-4 flex items-center gap-2.5 rounded-2xl border border-amber-200 bg-amber-50/90 p-3 text-left text-xs font-medium text-amber-800">
                <svg class="h-4 w-4 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-4 flex items-center gap-2.5 rounded-2xl border border-rose-200 bg-rose-50/90 p-3 text-left text-xs font-medium text-rose-800">
                <svg class="h-4 w-4 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <p class="mt-4 text-[14px] leading-relaxed text-slate-600 dark:text-slate-300">
            Silakan cek kotak masuk email Anda
            @if(!empty($email))
                (<strong class="text-slate-900 dark:text-white">{{ $email }}</strong>)
            @endif
            untuk melakukan verifikasi dari tautan yang telah dikirimkan. Apabila tidak ditemukan kiriman email dari sistem, silakan cek folder <strong>spam</strong> email Anda.
        </p>

        @if(!empty($email))
            <form action="{{ route('verifikasi.resend') }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" class="btn-amber-gold w-full h-11 rounded-full text-sm font-semibold shadow-md">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>
        @endif

        <div class="mt-4 flex flex-col sm:flex-row items-center justify-center gap-2.5">
            <a href="{{ route('masuk') }}" class="btn-emerald inline-flex h-11 w-full items-center justify-center rounded-full text-sm font-semibold">
                Ke Halaman Masuk
            </a>
        </div>
    </div>
@endsection
