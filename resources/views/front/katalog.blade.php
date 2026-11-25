@extends('front.layout')

@section('title', 'Katalog Buku — Perpustakaan Jurusan Akuntansi')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-[34px] font-bold tracking-tight text-slate-900 dark:text-white">Katalog Buku</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Jelajahi {{ $books->total() }} koleksi buku dan referensi jurusan akuntansi.</p>
        </div>

        <div>
            @if(request('tersedia') === '1')
                <a href="{{ route('katalog', request()->except('tersedia', 'page')) }}"
                   class="inline-flex min-h-[44px] items-center gap-1.5 rounded-full liquid-badge-emerald px-4 text-xs font-semibold shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span>Hanya Stok Tersedia &times;</span>
                </a>
            @else
                <a href="{{ route('katalog', array_merge(request()->except('page'), ['tersedia' => '1'])) }}"
                   class="inline-flex min-h-[44px] items-center gap-1.5 rounded-full liquid-chip px-4 text-xs font-medium">
                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                    <span>Hanya Stok Tersedia</span>
                </a>
            @endif
        </div>
    </div>

    <!-- CATEGORY FILTER CHIPS (Apple HIG >=44px touch target pills) -->
    <div class="no-scrollbar mb-5 -mx-4 px-4 flex items-center gap-2.5 overflow-x-auto pt-2 pb-5 sm:mx-0 sm:px-0 sm:flex-wrap sm:overflow-visible sm:pb-4">
        <!-- Semua -->
        <a href="{{ route('katalog', array_merge(request()->except('kategori', 'page'), ['kategori' => 'semua'])) }}"
           class="inline-flex min-h-[44px] shrink-0 items-center gap-2 rounded-full px-4 text-[14px] font-semibold transition-all duration-200 {{ $currentKategori === 'semua' ? 'btn-emerald' : 'liquid-chip' }}">
            Semua Kategori
        </a>

        @foreach($categories as $category)
            @php
                $isActiveCat = ($currentKategori === $category->slug || $currentKategori === $category->nama);
            @endphp
            <a href="{{ route('katalog', array_merge(request()->except('kategori', 'page'), ['kategori' => $category->slug])) }}"
               class="inline-flex min-h-[44px] shrink-0 items-center gap-2 rounded-full px-4 text-[14px] font-medium transition-all duration-200 {{ $isActiveCat ? 'btn-emerald' : 'liquid-chip' }}">
                <span>{{ $category->nama }}</span>
                <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $isActiveCat ? 'bg-white/25 text-white' : 'bg-black/5 dark:bg-white/10 text-slate-500 dark:text-slate-400' }}">
                    {{ $category->books_count }}
                </span>
            </a>
        @endforeach
    </div>

    <!-- BOOK GRID -->
    @if($books->count() > 0)
        <div class="grid grid-cols-2 gap-x-5 gap-y-10 sm:grid-cols-3 xl:grid-cols-4">
            @foreach($books as $book)
                @include('front.components.book-card', ['book' => $book])
            @endforeach
        </div>

        <!-- PAGINATION -->
        <div class="mt-12">
            {{ $books->links('vendor.pagination.liquid') }}
        </div>
    @else
        <!-- EMPTY STATE -->
        <div class="glass-card my-12 flex flex-col items-center justify-center rounded-[32px] p-12 text-center">
            <div class="grid h-16 w-16 place-items-center rounded-full bg-slate-100 dark:bg-white/5 text-slate-400 mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Tidak ada buku yang sesuai</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm">Coba ubah kata kunci pencarian atau bersihkan filter kategori Anda.</p>
            <a href="{{ route('katalog') }}" class="btn-glass mt-5 inline-flex min-h-[44px] items-center rounded-full px-5 text-sm font-semibold">
                Reset Semua Filter
            </a>
        </div>
    @endif
@endsection
