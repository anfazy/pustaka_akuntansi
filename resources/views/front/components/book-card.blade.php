@props(['book'])

@php
    $cover = $book->cover;
    $artIndex = abs(crc32($book->judul)) % 6;
    $rack = $book->rak ?: ($book->collection_type === 'ta_skripsi' ? 'Rak TA-01' : 'Rak A-01');
@endphp

<a href="{{ route('buku.detail', $book->id) }}" class="group flex flex-col gap-3 rounded-2xl p-1 transition-transform duration-300 hover:-translate-y-1.5">
    <!-- Book Cover -->
    <div class="relative aspect-[5/7] w-full max-w-[180px] overflow-hidden rounded-[8px] book-shadow shrink-0 transition-shadow duration-300 group-hover:shadow-xl dark:shadow-[0_12px_28px_-10px_rgba(0,0,0,0.7)]">
        @if($cover && (file_exists(public_path(ltrim($cover, '/'))) || str_starts_with($cover, 'http')))
            <img src="{{ asset(ltrim($cover, '/')) }}" alt="Sampul {{ $book->judul }}" loading="lazy" class="h-full w-full object-cover">
        @else
            <!-- Typographic Fallback Cover Apple Style -->
            <div class="cover-art-{{ $artIndex }} flex h-full w-full flex-col justify-between p-4">
                <div class="font-serif text-[15px] sm:text-[17px] font-semibold leading-[1.1] tracking-tight">
                    {{ $book->judul }}
                </div>
                <div class="space-y-1">
                    <p class="text-[11px] font-medium opacity-85 line-clamp-1">{{ $book->penulis }}</p>
                    <p class="text-[8px] uppercase tracking-widest opacity-60">Pustaka Akuntansi</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Book Info -->
    <div class="space-y-1">
        <h3 class="text-[15px] font-semibold leading-snug text-slate-900 group-hover:text-emerald-800 dark:group-hover:text-emerald-400 transition-colors line-clamp-2">
            {{ $book->judul }}
        </h3>
        <p class="text-[13px] text-slate-500 line-clamp-1">
            {{ $book->penulis }}
        </p>
    </div>

    <!-- Liquid Glass Stock Pill -->
    <div class="self-start">
        <span class="liquid-chip inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[12px] font-medium">
            <span class="h-1.5 w-1.5 rounded-full {{ $book->stok > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
            <span>{{ $rack }} • {{ $book->stok > 0 ? "Tersedia {$book->stok}" : 'Sedang Habis' }}</span>
        </span>
    </div>
</a>
