<?php $__env->startSection('title', 'Beranda — Pustaka Akuntansi'); ?>

<?php $__env->startSection('content'); ?>
    <!-- PAGE TITLE -->
    <h1 class="text-[32px] sm:text-[36px] font-bold tracking-tight text-slate-900 mb-8">Beranda</h1>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($student): ?>
        <!-- 4 APPLE LIQUID GLASS STAT CARDS (Logged in Student) -->
        <div class="grid grid-cols-2 gap-4 sm:gap-5 xl:grid-cols-4">
            <!-- Card 1: Sedang Dipinjam -->
            <div class="glass-card glass-card-hover rounded-[26px] p-6">
                <span class="block text-[13px] font-medium text-slate-500 mb-2">Sedang Dipinjam</span>
                <span class="block text-[26px] sm:text-[30px] font-bold tracking-tight text-slate-900">
                    <?php echo e($stats['borrowed_count']); ?> Buku
                </span>
            </div>

            <!-- Card 2: Siap Diambil -->
            <div class="glass-card glass-card-hover rounded-[26px] p-6">
                <span class="block text-[13px] font-medium text-slate-500 mb-2">Siap Diambil</span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['booked_count'] > 0): ?>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[26px] sm:text-[30px] font-bold tracking-tight text-slate-900">
                            <?php echo e($stats['booked_count']); ?>

                        </span>
                        <span class="liquid-badge-amber rounded-full px-2.5 py-1 text-xs font-bold shadow-sm">
                            Kode <?php echo e($stats['latest_booking_code']); ?>

                        </span>
                    </div>
                <?php else: ?>
                    <span class="block text-[26px] sm:text-[30px] font-bold tracking-tight text-slate-900">
                        0 Buku
                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Card 3: Kuota Pinjam -->
            <div class="glass-card glass-card-hover rounded-[26px] p-6">
                <span class="block text-[13px] font-medium text-slate-500 mb-2">Kuota Pinjam</span>
                <span class="block text-[26px] sm:text-[30px] font-bold tracking-tight text-slate-900">
                    <?php echo e($stats['active_count']); ?> / <?php echo e($stats['max_quota']); ?> Buku
                </span>
            </div>

            <!-- Card 4: Denda Aktif -->
            <div class="glass-card glass-card-hover rounded-[26px] p-6">
                <span class="block text-[13px] font-medium text-slate-500 mb-2">Denda Aktif</span>
                <span class="block text-[26px] sm:text-[30px] font-bold tracking-tight <?php echo e($stats['fine_amount'] > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900'); ?>">
                    Rp <?php echo e(number_format($stats['fine_amount'], 0, ',', '.')); ?>

                </span>
            </div>
        </div>

    <?php else: ?>
        <!-- GUEST HERO BANNER (Authentic Apple Liquid Glass) -->
        <div class="glass-card relative overflow-hidden rounded-[32px] p-8 sm:p-11 mb-10">
            <div class="relative z-10 max-w-3xl">
                <!-- Apple Liquid Amber / Gold Pill Badge (Crest Stars Accent) -->
                <span class="inline-flex items-center gap-2 rounded-full liquid-badge-amber px-4 py-1 text-xs font-semibold mb-4 shadow-sm">
                    <svg class="h-3.5 w-3.5 text-amber-700 dark:text-amber-300" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span>Ruang Baca Jurusan Akuntansi Polines</span>
                </span>

                <h2 class="text-2xl sm:text-[32px] font-bold tracking-tight text-slate-900 leading-snug">
                    Eksplorasi Koleksi Buku & Referensi Akuntansi
                </h2>
                <p class="mt-3 text-slate-600 dark:text-slate-300 text-sm sm:text-[15px] leading-relaxed max-w-2xl">
                    Temukan buku referensi perkuliahan, modul praktik, literatur perpajakan, audit, dan skripsi secara langsung. Mahasiswa dapat memesan (booking) buku sebelum pengambilan di ruang baca.
                </p>

                <!-- Primary Action (Jelajahi Seluruh Koleksi) -->
                <div class="mt-7 flex flex-wrap items-center gap-3">
                    <a href="<?php echo e(route('katalog')); ?>" class="btn-emerald inline-flex h-12 items-center gap-2.5 rounded-full px-7 text-[15px] font-semibold">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                        <span>Jelajahi Seluruh Koleksi</span>
                    </a>
                </div>

                <!-- Category Shortcuts with Liquid Glass Pills (Exact 5 Categories) -->
                <div class="mt-6 flex flex-wrap items-center gap-2.5 pt-3 border-t border-slate-200/50 dark:border-white/10 text-xs">
                    <span class="text-slate-500 font-medium mr-1">Kategori:</span>
                    <a href="<?php echo e(route('katalog', ['kategori' => 'akuntansi'])); ?>" class="liquid-chip rounded-full px-3.5 py-1.5 font-medium">
                        Akuntansi
                    </a>
                    <a href="<?php echo e(route('katalog', ['kategori' => 'perpajakan'])); ?>" class="liquid-chip rounded-full px-3.5 py-1.5 font-medium">
                        Perpajakan
                    </a>
                    <a href="<?php echo e(route('katalog', ['kategori' => 'fiksi'])); ?>" class="liquid-chip rounded-full px-3.5 py-1.5 font-medium">
                        Fiksi
                    </a>
                    <a href="<?php echo e(route('katalog', ['kategori' => 'ta-skripsi'])); ?>" class="liquid-chip rounded-full px-3.5 py-1.5 font-medium">
                        TA / Skripsi
                    </a>
                    <a href="<?php echo e(route('katalog', ['kategori' => 'laporan-pkl'])); ?>" class="liquid-chip rounded-full px-3.5 py-1.5 font-medium">
                        Laporan PKL
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- SECTION: BUKU POPULER MINGGU INI -->
    <section class="mt-10 sm:mt-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-[22px] font-bold tracking-tight text-slate-900">Buku Populer Minggu Ini</h2>
            <a href="<?php echo e(route('katalog')); ?>" class="liquid-chip rounded-full px-4 py-1.5 text-xs font-semibold text-emerald-800 dark:text-emerald-300">
                Lihat Semua &rarr;
            </a>
        </div>
        <div class="grid grid-cols-2 gap-x-5 gap-y-8 sm:grid-cols-3 xl:grid-cols-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $popularBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('front.components.book-card', ['book' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-slate-500 col-span-full">Belum ada data koleksi buku.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <!-- SECTION: KOLEKSI TERBARU -->
    <section class="mt-14 sm:mt-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-[22px] font-bold tracking-tight text-slate-900">Koleksi Terbaru</h2>
            <a href="<?php echo e(route('katalog')); ?>" class="liquid-chip rounded-full px-4 py-1.5 text-xs font-semibold text-emerald-800 dark:text-emerald-300">
                Lihat Semua &rarr;
            </a>
        </div>
        <div class="grid grid-cols-2 gap-x-5 gap-y-8 sm:grid-cols-3 xl:grid-cols-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $newestBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('front.components.book-card', ['book' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-slate-500 col-span-full">Belum ada data buku terbaru.</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <!-- SECTION: LAYANAN & INFORMASI PERPUSTAKAAN (Apple Liquid Glass) -->
    <section class="mt-16 sm:mt-20">
        <div class="glass-card overflow-hidden rounded-[32px] p-6 sm:p-8 md:p-10 shadow-xl border border-white/80 dark:border-white/15">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Map Container (Clean, Tanpa Bar Kotak Bertumpuk) -->
                <div class="lg:col-span-5 relative">
                    <div class="relative w-full h-[240px] sm:h-[280px] rounded-[24px] overflow-hidden border border-white/60 dark:border-white/10 shadow-inner bg-slate-100 dark:bg-slate-800">
                        <iframe
                            title="Peta Lokasi Perpustakaan Polines"
                            src="https://maps.google.com/maps?q=Perpustakaan+Politeknik+Negeri+Semarang,+Tembalang,+Semarang&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            class="w-full h-full border-0 filter contrast-[1.05]"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <!-- Info Details -->
                <div class="lg:col-span-7 flex flex-col justify-between space-y-5">
                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center gap-2 rounded-full liquid-badge-emerald px-3.5 py-1 text-xs font-semibold">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                Perpustakaan Politeknik Negeri Semarang
                            </span>
                            <a href="https://maps.google.com/?q=Perpustakaan+Politeknik+Negeri+Semarang"
                               target="_blank"
                               class="btn-glass inline-flex items-center gap-1.5 rounded-full px-3.5 py-1 text-xs font-semibold text-emerald-800 dark:text-emerald-400 hover:text-emerald-950 transition">
                                <span>Buka di Google Maps</span>
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </a>
                        </div>
                        <h3 class="text-2xl sm:text-[26px] font-bold tracking-tight text-slate-900 leading-snug">
                            Layanan & Informasi Perpustakaan
                        </h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            WCXP+34P, Tembalang, Kota Semarang, Jawa Tengah 50275 • Layanan peminjaman, pengembalian, dan penelusuran koleksi buku fisik maupun digital jurusan akuntansi.
                        </p>
                    </div>

                    <!-- Meta highlights -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        <div class="rounded-2xl bg-white/70 dark:bg-slate-800/70 border border-white/80 dark:border-white/10 p-4">
                            <span class="block text-slate-400 font-medium mb-1">🕒 Jam Pelayanan</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-sm">Senin – Kamis: 08.30 – 15.30 WIB<br><span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Jumat: 08.30 – 14.00 WIB</span></span>
                        </div>
                        <div class="rounded-2xl bg-white/70 dark:bg-slate-800/70 border border-white/80 dark:border-white/10 p-4">
                            <span class="block text-slate-400 font-medium mb-1">💬 Admin Perpustakaan</span>
                            <a href="https://wa.me/62887895627873?text=Halo%20Admin%20Perpustakaan%20Akuntansi%20Polines" target="_blank" class="font-bold text-emerald-800 dark:text-emerald-400 text-sm hover:underline flex items-center gap-1">
                                0887895627873 (WhatsApp)
                            </a>
                            <span class="text-[11px] text-slate-500 block mt-1">Layanan print, fotokopi & konsultasi sirkulasi</span>
                        </div>
                    </div>

                    <!-- Direct Link to Tata Tertib & Ketentuan Layanan -->
                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <a href="<?php echo e(route('tata-tertib')); ?>" class="btn-glass inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:text-emerald-800 transition">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span>Lihat Tata Tertib & Ketentuan Layanan</span>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\perpustakaan admin\perpustakaan\resources\views/front/beranda.blade.php ENDPATH**/ ?>