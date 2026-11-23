<div class="fi-wi-library-stats">
    <!-- 4 KARTU STATISTIK UTAMA DASHBOARD PERSIS MOCKUP -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Card 1: Total Kategori -->
        <a href="{{ route('filament.admin.resources.categories.index') }}" class="cursor-pointer bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-emerald-400 transition-all group block">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500">Total Kategori</p>
                <span class="w-7 h-7 rounded-lg flex items-center justify-center group-hover:bg-emerald-700 group-hover:text-white transition-colors" style="background-color: #ecfdf5; color: #047857;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.386l5.483-3.23a1.944 1.944 0 0 0 .749-1.39l.42-6.304a2.25 2.25 0 0 0-.659-1.591L12.75 3.659A2.25 2.25 0 0 0 11.159 3H9.568Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                {{ $kategoriCount }} Kategori
            </p>
            <p class="text-xs font-semibold mt-1.5 flex items-center gap-1 group-hover:underline" style="color: #047857;">
                <span>Klik untuk kelola kategori</span>
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
            </p>
        </a>

        <!-- Card 2: Koleksi Judul Buku -->
        <a href="{{ route('filament.admin.resources.books.index') }}" class="cursor-pointer bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-amber-400 transition-all group block">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500">Koleksi Judul Buku</p>
                <span class="w-7 h-7 rounded-lg flex items-center justify-center group-hover:bg-amber-400 group-hover:text-emerald-950 transition-colors" style="background-color: #fef3c7; color: #b45309;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5M4.5 21V10.5M3 21h18M5.25 9h13.5"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                {{ $judulCount }} Judul
            </p>
            <p class="text-xs font-semibold mt-1.5 flex items-center gap-1 group-hover:underline" style="color: #b45309;">
                <span>Klik untuk lihat katalog buku</span>
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
            </p>
        </a>

        <!-- Card 3: Stok Eksemplar Tersedia -->
        <div class="bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-emerald-300 transition-colors">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500">Stok Eksemplar Tersedia</p>
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background-color: #ecfdf5; color: #047857;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                {{ $totalStok }} Buku
            </p>
            <p class="text-xs font-medium mt-1.5 flex items-center gap-1.5" style="color: #047857;">
                <span>Siap dipinjam mahasiswa</span>
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </p>
        </div>

        <!-- Card 4: Sedang Dipinjam -->
        <a href="{{ route('filament.admin.resources.loans.index') }}" class="cursor-pointer bg-white rounded-xl p-5 border border-gray-200/90 shadow-2xs hover:border-rose-300 transition-colors block">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500">Sedang Dipinjam</p>
                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background-color: #ffe4e6; color: #e11d48;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                {{ $activeLoansCount }} Eksemplar
            </p>
            <p class="text-xs font-medium mt-1.5 flex items-center gap-1.5" style="color: {{ $overdueLoansCount > 0 ? '#e11d48' : '#047857' }};">
                @if($overdueLoansCount > 0)
                    <span>Peringatan: {{ $overdueLoansCount }} buku terlambat!</span>
                    <span>⚠️</span>
                @else
                    <span>Sirkulasi aktif berjalan normal</span>
                    <span>✓</span>
                @endif
            </p>
        </a>
    </div>
</div>
