@extends('front.layout')

@section('title', 'Buku Favorit Saya — Pustaka Akuntansi')

@section('content')
    <!-- PAGE HEADER -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2">
            <a href="{{ route('beranda') }}" class="hover:text-emerald-800 transition">Beranda</a>
            <span>&bull;</span>
            <span class="text-emerald-800 dark:text-emerald-400 font-bold">Buku Favorit</span>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 leading-tight">
                    Rekapan Buku Favorit
                </h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Koleksi referensi buku yang Anda simpan untuk dibaca atau dipinjam sewaktu-waktu.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full liquid-badge-amber px-3.5 py-1.5 text-xs font-bold shadow-xs">
                    <svg class="h-3.5 w-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span>{{ $favoriteBooks->count() }} Buku Favorit</span>
                </span>
            </div>
        </div>
    </div>

    @if($favoriteBooks->isEmpty())
        <!-- EMPTY STATE (Apple Liquid Glass Card) -->
        <div class="glass-card rounded-[32px] p-10 sm:p-14 text-center max-w-lg mx-auto shadow-xl border border-white/80 dark:border-white/10 my-10">
            <div class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-amber-500/15 text-amber-600 border border-amber-500/30 mb-5 shadow-sm">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 leading-tight">Belum Ada Buku Favorit</h3>
            <p class="mt-2 text-xs sm:text-[13px] text-slate-600 dark:text-slate-300 leading-relaxed max-w-sm mx-auto">
                Anda belum menyimpan buku ke daftar favorit. Buka katalog, pilih buku yang Anda sukai, lalu tekan tombol <strong>"Simpan ke Favorit"</strong>.
            </p>
            <div class="mt-7">
                <a href="{{ route('katalog') }}" class="btn-emerald inline-flex h-11 items-center gap-2 rounded-full px-6 text-sm font-semibold shadow-md">
                    <span>Jelajahi Katalog Buku</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    @else
        <!-- FAVORITES GRID -->
        <div class="grid grid-cols-2 gap-x-5 gap-y-8 sm:grid-cols-3 xl:grid-cols-4">
            @foreach($favoriteBooks as $book)
                @include('front.components.book-card', ['book' => $book])
            @endforeach
        </div>
    @endif
@endsection
