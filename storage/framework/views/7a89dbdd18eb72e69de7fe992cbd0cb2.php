<?php $__env->startSection('title', 'Katalog Buku — Pustaka Akuntansi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-[34px] font-bold tracking-tight text-slate-900 dark:text-white">Katalog Buku</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Jelajahi <?php echo e($books->total()); ?> koleksi buku dan referensi jurusan akuntansi.</p>
        </div>

        <div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('tersedia') === '1'): ?>
                <a href="<?php echo e(route('katalog', request()->except('tersedia', 'page'))); ?>"
                   class="inline-flex items-center gap-1.5 rounded-full liquid-badge-emerald px-4 py-2 text-xs font-semibold shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span>Hanya Stok Tersedia &times;</span>
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('katalog', array_merge(request()->all(), ['tersedia' => '1']))); ?>"
                   class="inline-flex items-center gap-1.5 rounded-full liquid-chip px-4 py-2 text-xs font-medium">
                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                    <span>Hanya Stok Tersedia</span>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- CATEGORY FILTER CHIPS ONLY (5 Canonical Categories: Fiksi, Akuntansi, Perpajakan, TA/Skripsi, Laporan PKL) -->
    <div class="no-scrollbar mb-8 -mx-4 px-4 flex items-center gap-2.5 overflow-x-auto pb-2 sm:mx-0 sm:px-0">
        <!-- Semua -->
        <a href="<?php echo e(route('katalog', array_merge(request()->except('kategori', 'page'), ['kategori' => 'semua']))); ?>"
           class="inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2 text-[14px] font-semibold transition-all duration-200 <?php echo e($currentKategori === 'semua' ? 'btn-emerald' : 'liquid-chip'); ?>">
            Semua Kategori
        </a>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $isActiveCat = ($currentKategori === $category->slug || $currentKategori === $category->nama);
            ?>
            <a href="<?php echo e(route('katalog', array_merge(request()->except('kategori', 'page'), ['kategori' => $category->slug]))); ?>"
               class="inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2 text-[14px] font-medium transition-all duration-200 <?php echo e($isActiveCat ? 'btn-emerald' : 'liquid-chip'); ?>">
                <span><?php echo e($category->nama); ?></span>
                <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold <?php echo e($isActiveCat ? 'bg-white/25 text-white' : 'bg-black/5 dark:bg-white/10 text-slate-500 dark:text-slate-400'); ?>">
                    <?php echo e($category->books_count); ?>

                </span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- BOOK GRID -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($books->count() > 0): ?>
        <div class="grid grid-cols-2 gap-x-5 gap-y-10 sm:grid-cols-3 xl:grid-cols-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('front.components.book-card', ['book' => $book], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <div class="mt-14 flex justify-center">
            <?php echo e($books->links()); ?>

        </div>
    <?php else: ?>
        <!-- EMPTY STATE -->
        <div class="glass-card my-12 flex flex-col items-center justify-center rounded-[32px] p-12 text-center">
            <div class="grid h-16 w-16 place-items-center rounded-full bg-slate-100 dark:bg-white/5 text-slate-400 mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Tidak ada buku yang sesuai</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm">Coba ubah kata kunci pencarian atau bersihkan filter kategori Anda.</p>
            <a href="<?php echo e(route('katalog')); ?>" class="btn-glass mt-5 rounded-full px-5 py-2 text-sm font-semibold">
                Reset Semua Filter
            </a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\perpustakaan admin\perpustakaan\resources\views/front/katalog.blade.php ENDPATH**/ ?>