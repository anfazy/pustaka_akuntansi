@props(['book'])

@php
    $coverUrl = $book->cover_url;
    $artIndex = abs(crc32($book->judul)) % 6;
@endphp

<a href="{{ route('buku.detail', $book->id) }}" class="apple-press group flex h-full flex-col gap-2 rounded-2xl p-0.5 transition-all duration-400 ease-[cubic-bezier(0.22,1.36,0.36,1)] hover:-translate-y-1.5 active:scale-[0.96]">
    <!-- Book Cover -->
    <div class="relative aspect-[5/7] w-full overflow-hidden rounded-[10px] book-shadow shrink-0 transition-all duration-400 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:shadow-2xl group-hover:scale-[1.015] dark:shadow-[0_12px_28px_-10px_rgba(0,0,0,0.7)]">
        @if($coverUrl)
            <img src="{{ $coverUrl }}" alt="Sampul {{ $book->judul }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-[1.04]">
        @else
            <!-- Typographic Fallback Cover Apple Style -->
            <div class="cover-art-{{ $artIndex }} flex h-full w-full flex-col justify-between p-3">
                <div class="font-serif text-[12.5px] sm:text-[13px] font-semibold leading-[1.15] tracking-tight line-clamp-4">
                    {{ $book->judul }}
                </div>
                <div class="space-y-0.5 pt-1.5">
                    <p class="text-[10px] font-medium opacity-90 line-clamp-1">{{ $book->penulis }}</p>
                    <p class="text-[7px] uppercase tracking-widest opacity-75">Perpustakaan Jurusan Akuntansi</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Book Info (flex-1 keeps stock pill aligned at the bottom across cards) -->
    <div class="flex flex-1 flex-col space-y-0.5">
        <h3 class="text-[13px] font-semibold leading-snug text-slate-900 group-hover:text-emerald-800 dark:group-hover:text-emerald-400 transition-colors line-clamp-2">
            {{ $book->judul }}
        </h3>
        <p class="text-[11.5px] text-slate-500 line-clamp-1">
            {{ $book->penulis }}
        </p>
        @if(filled($book->program_studi))
            <p class="text-[10.5px] font-medium text-emerald-700 dark:text-emerald-400 line-clamp-1">
                {{ $book->program_studi }}
            </p>
        @endif
    </div>

    <!-- Liquid Glass Stock Pill -->
    <div class="mt-auto pt-0.5 self-start">
        <span class="liquid-chip inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[10.5px] font-medium whitespace-nowrap">
            <span class="h-1.5 w-1.5 rounded-full {{ $book->stok > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
            <span>{{ $book->stok > 0 ? "Tersedia {$book->stok} Eksemplar" : 'Sedang Habis' }}</span>
        </span>
    </div>
</a>
