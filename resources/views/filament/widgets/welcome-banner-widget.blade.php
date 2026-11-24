<x-filament-widgets::widget>
    <div class="pa-hero-banner relative overflow-hidden">
        <div class="relative z-10">
            <div class="pa-hero-top-row">
                <span class="pa-hero-pill inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold">
                    <span>★</span>
                    <span>Ruang Baca Jurusan Akuntansi Polines</span>
                </span>
                <span class="pa-hero-date inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium">
                    <span>{{ $formattedDate }}</span>
                </span>
            </div>

            <h2 class="pa-hero-title font-extrabold tracking-tight" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                {{ $greeting }}, <span class="pa-hero-highlight">Admin Pustaka!</span>
            </h2>

            <p class="pa-hero-desc max-w-2xl">
                Kelola koleksi referensi perkuliahan, verifikasi booking mahasiswa, dan pantau sirkulasi peminjaman buku Jurusan Akuntansi secara real-time.
            </p>

            <div class="pa-hero-actions">
                <a
                    href="{{ \App\Filament\Resources\BookResource::getUrl('index') }}"
                    class="pa-pill-emerald inline-flex items-center gap-2 text-xs sm:text-sm"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span>Jelajahi Koleksi Buku</span>
                </a>
                <a
                    href="{{ \App\Filament\Resources\LoanResource::getUrl('index') }}"
                    class="pa-hero-secondary-btn inline-flex items-center gap-2 font-bold text-xs sm:text-sm transition-all"
                >
                    <span>Kelola Peminjaman &amp; Booking</span>
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>