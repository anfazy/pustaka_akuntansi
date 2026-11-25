@extends('front.layout')

@section('title', 'Riwayat Peminjaman — Perpustakaan Jurusan Akuntansi')

@section('content')
    <h1 class="text-[34px] font-bold tracking-tight text-slate-900 mb-8">Riwayat Peminjaman</h1>

    <!-- TOP TOOLBAR: FILTER & SEARCH -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Segmented Filter Tabs -->
        <div class="segment-track inline-flex rounded-full p-1 max-w-[360px]">
            <a href="{{ route('riwayat', array_merge(request()->except('status', 'page'), ['status' => 'semua'])) }}"
               class="rounded-full px-4 py-1.5 text-xs font-semibold transition-all {{ $currentFilter === 'semua' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Semua
            </a>
            <a href="{{ route('riwayat', array_merge(request()->except('status', 'page'), ['status' => 'tepat'])) }}"
               class="rounded-full px-4 py-1.5 text-xs font-semibold transition-all {{ $currentFilter === 'tepat' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Tepat Waktu
            </a>
            <a href="{{ route('riwayat', array_merge(request()->except('status', 'page'), ['status' => 'terlambat'])) }}"
               class="rounded-full px-4 py-1.5 text-xs font-semibold transition-all {{ $currentFilter === 'terlambat' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                Terlambat
            </a>
        </div>

        <!-- Inline Search -->
        <form action="{{ route('riwayat') }}" method="GET" class="w-full sm:w-[320px]">
            <input type="hidden" name="status" value="{{ $currentFilter }}">
            <div class="glass-field flex h-10 items-center gap-2 rounded-full px-4">
                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" name="q" value="{{ $currentSearch }}" placeholder="Cari buku..." class="w-full bg-transparent text-xs font-medium text-slate-800 outline-none">
            </div>
        </form>
    </div>

    <!-- TABLE CARD -->
    @if($historyLoans->count() > 0)
        <div class="glass-card overflow-hidden rounded-[28px] p-2 sm:p-6 shadow-sm border border-white/90">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-[14px]">
                    <thead class="text-xs font-semibold text-slate-400 border-b border-slate-200/60 uppercase tracking-wider">
                        <tr>
                            <th class="pb-4 pl-4 font-medium">Buku & Kode Eksemplar</th>
                            <th class="pb-4 font-medium">Tanggal Pinjam</th>
                            <th class="pb-4 font-medium">Tanggal Kembali</th>
                            <th class="pb-4 font-medium">Status</th>
                            <th class="pb-4 pr-4 text-right font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($historyLoans as $loan)
                            @php
                                $cover = $loan->book?->cover;
                                $artIndex = abs(crc32($loan->book?->judul ?? 'buku')) % 6;
                                $isLate = $loan->fine_amount > 0;
                            @endphp
                            <tr class="transition-colors hover:bg-white/40">
                                <!-- Book Info -->
                                <td class="py-4 pl-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="relative aspect-[5/7] w-10 shrink-0 overflow-hidden rounded-[4px] book-shadow">
                                            @if($cover && (file_exists(public_path(ltrim($cover, '/'))) || str_starts_with($cover, 'http')))
                                                <img src="{{ asset(ltrim($cover, '/')) }}" alt="{{ $loan->book?->judul }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="cover-art-{{ $artIndex }} h-full w-full p-1 flex flex-col justify-end">
                                                    <span class="font-serif text-[6px] font-bold leading-tight">{{ $loan->book?->judul }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 leading-snug">{{ $loan->book?->judul }}</div>
                                            <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $loan->book?->book_code ?? $loan->booking_code }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Dates -->
                                <td class="py-4 text-slate-600 font-medium">
                                    {{ $loan->tanggal_pinjam?->translatedFormat('d M Y') ?? '-' }}
                                </td>
                                <td class="py-4 text-slate-600 font-medium">
                                    {{ $loan->tanggal_kembali?->translatedFormat('d M Y') ?? '-' }}
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4">
                                    @if($isLate)
                                        <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-800">
                                            Terlambat • Denda Lunas Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full liquid-badge-emerald px-3 py-1 text-xs font-semibold">
                                            Tepat Waktu
                                        </span>
                                    @endif
                                </td>

                                <!-- Action -->
                                <td class="py-4 pr-4 text-right">
                                    @if($loan->book)
                                        <a href="{{ route('buku.detail', $loan->book->id) }}" class="btn-glass rounded-full px-3.5 py-1 text-xs font-semibold text-emerald-800 hover:border-emerald-300">
                                            Pinjam Lagi
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-4">
                {{ $historyLoans->links('vendor.pagination.liquid') }}
            </div>
        </div>
    @else
        <div class="glass-card my-12 flex flex-col items-center justify-center rounded-[32px] p-12 text-center">
            <div class="grid h-16 w-16 place-items-center rounded-full bg-slate-100 text-slate-400 mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Belum ada riwayat peminjaman</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm">Buku yang sudah dikembalikan ke perpustakaan akan tercatat rapi di sini.</p>
        </div>
    @endif
@endsection
