@if(auth()->check())
<!-- TOP NAVBAR PERSIS MOCKUP -->
<header class="fi-custom-full-header fixed top-0 left-0 right-0 z-40 h-16 border-b px-4 lg:px-6 flex items-center justify-between shadow-sm" style="background: linear-gradient(to right, #022c22, #064e3b, #047857); border-color: rgba(4, 120, 87, 0.8);">
    <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Toggle -->
        <button
            type="button"
            x-data="{}"
            x-on:click="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()"
            class="lg:hidden p-2 rounded-lg text-emerald-100 hover:bg-[#064e3b] focus:outline-none"
            aria-label="Toggle Navigation"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        
        <!-- Brand Title: Perpustakaan Jurusan Akuntansi -->
        <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-sm transition-transform group-hover:scale-105" style="background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%); color: #022c22; border: 1px solid rgba(253, 230, 138, 0.6); box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
                <svg class="w-5 h-5" style="stroke-width: 2.2;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5M4.5 21V10.5M3 21h18M5.25 9h13.5" />
                </svg>
            </div>
            <div class="flex flex-col text-left">
                <span class="font-bold text-base sm:text-lg tracking-tight text-white leading-tight" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Perpustakaan Jurusan <span style="color: #fcd34d;">Akuntansi</span>
                </span>
                <span class="text-[10px] font-medium hidden sm:inline" style="color: #a7f3d0;">
                    Sistem Informasi Sirkulasi &amp; Koleksi • Pustaka Akuntansi
                </span>
            </div>
        </a>
    </div>

    <!-- Right Top Bar: Tanggal & Avatar 'AP' -->
    <div class="flex items-center gap-3">
        <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium" style="background: rgba(2, 44, 34, 0.65); border: 1px solid rgba(4, 120, 87, 0.7); color: #ecfdf5;">
            <svg class="w-3.5 h-3.5" style="color: #fbbf24;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253 3.75m-15 0h18m-18 0v11.25a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25V7.5M4.5 19.5h15" />
            </svg>
            <span>{{ \Carbon\Carbon::now(config('app.timezone', 'Asia/Jakarta'))->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
        
        <div class="flex items-center gap-2.5">
            <span class="hidden sm:inline text-xs font-semibold text-white">Admin Perpus</span>
            <div class="w-9 h-9 rounded-full font-extrabold text-sm flex items-center justify-center shadow-xs cursor-pointer" style="background: #fbbf24; color: #022c22; box-shadow: 0 0 0 2px rgba(253, 230, 138, 0.6);" title="Admin Perpustakaan Jurusan Akuntansi">
                AP
            </div>
            <form action="{{ route('filament.admin.auth.logout') }}" method="POST" class="inline ml-1">
                @csrf
                <button type="submit" title="Keluar / Logout" class="text-emerald-200 hover:text-white p-1.5 rounded-lg hover:bg-emerald-900/40 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</header>
@endif
