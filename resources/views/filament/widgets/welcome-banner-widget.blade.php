<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-indigo-600 to-indigo-900 p-6 md:p-8 text-white shadow-xl ring-1 ring-white/10 dark:from-blue-900 dark:via-indigo-900 dark:to-slate-950">
        {{-- Decorative ambient background elements --}}
        <div class="pointer-events-none absolute -right-10 -top-10 h-64 w-64 rounded-full bg-blue-400/20 blur-3xl"></div>
        <div class="pointer-events-none absolute right-1/4 -bottom-12 h-56 w-56 rounded-full bg-indigo-300/15 blur-2xl"></div>

        <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            {{-- Left column: Greeting and details --}}
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold backdrop-blur-md ring-1 ring-white/20" style="background-color: rgba(255, 255, 255, 0.18); color: #ffffff;">
                        <svg class="h-3.5 w-3.5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        Pustaka Akuntansi
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium backdrop-blur-sm" style="background-color: rgba(255, 255, 255, 0.12); color: #e0e7ff;">
                        <svg class="h-3.5 w-3.5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253 3.75m-15 0h18m-18 0v11.25a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25V7.5M4.5 19.5h15" />
                        </svg>
                        {{ $formattedDate }}
                    </span>
                </div>

                <div class="space-y-1">
                    <h2 class="text-2xl font-bold tracking-tight text-white md:text-3xl" style="color: #ffffff !important;">
                        {{ $greeting }}, <span class="text-blue-200 underline decoration-indigo-300/40 decoration-wavy underline-offset-4">{{ $userName }}</span>! 👋
                    </h2>
                    <p class="max-w-2xl text-sm leading-relaxed md:text-base" style="color: rgba(224, 231, 255, 0.95) !important;">
                        Selamat datang di pusat kendali operasional Pustaka Akuntansi. Pantau statistik sirkulasi, distribusi kategori referensi, dan ketersediaan stok buku secara real-time.
                    </p>
                </div>
            </div>

            {{-- Right column: Quick Navigation Button --}}
            <div class="flex items-center self-start md:self-center">
                <x-filament::button
                    tag="a"
                    href="{{ route('filament.admin.resources.books.index') }}"
                    color="gray"
                    icon="heroicon-m-book-open"
                    size="lg"
                    class="font-semibold shadow-md"
                    style="background-color: #ffffff !important; color: #1e1b4b !important;"
                >
                    <span style="color: #1e1b4b !important; font-weight: 600;">Buka Katalog Buku</span>
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
