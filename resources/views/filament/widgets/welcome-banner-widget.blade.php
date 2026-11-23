<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-2xl p-5 sm:p-6 text-white shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4" style="background: linear-gradient(to right, #022c22, #064e3b, #065f46); border: 1px solid rgba(4, 120, 87, 0.8);">
        {{-- Decorative ambient background elements --}}
        <div class="pointer-events-none absolute -right-10 -top-10 h-64 w-64 rounded-full blur-3xl" style="background: rgba(251, 191, 36, 0.08);"></div>
        <div class="pointer-events-none absolute right-1/4 -bottom-12 h-56 w-56 rounded-full blur-2xl" style="background: rgba(16, 185, 129, 0.12);"></div>

        <div class="relative z-10 space-y-2">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold" style="background: rgba(2, 44, 34, 0.75); border: 1px solid rgba(4, 120, 87, 0.8); color: #fcd34d;">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5M4.5 21V10.5M3 21h18M5.25 9h13.5" />
                    </svg>
                    <span>Pustaka Akuntansi</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium" style="background: rgba(6, 95, 70, 0.8); border: 1px solid rgba(4, 120, 87, 0.6); color: #ecfdf5;">
                    <svg class="w-3.5 h-3.5" style="color: #fbbf24;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253 3.75m-15 0h18m-18 0v11.25a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25V7.5M4.5 19.5h15" />
                    </svg>
                    <span>{{ $formattedDate }}</span>
                </span>
            </div>

            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-white" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    {{ $greeting }}, <span style="color: #fcd34d;">{{ $userName }}!</span> 👋
                </h2>
                <p class="text-xs sm:text-sm max-w-3xl leading-relaxed mt-1" style="color: rgba(209, 250, 229, 0.9);">
                    Selamat datang di pusat kendali operasional Pustaka Akuntansi. Pantau statistik sirkulasi, distribusi kategori referensi, dan ketersediaan stok buku secara real-time.
                </p>
            </div>
        </div>

        <div class="relative z-10 shrink-0">
            <a
                href="{{ route('filament.admin.resources.buku.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-extrabold text-xs sm:text-sm shadow-sm transition-all"
                style="background: #fbbf24 !important; color: #022c22 !important; border: 1px solid #fde68a !important;"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <span>Buka Katalog Buku</span>
            </a>
        </div>
    </div>
</x-filament-widgets::widget>
