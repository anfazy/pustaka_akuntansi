<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['book']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['book']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $cover = $book->cover;
    $artIndex = abs(crc32($book->judul)) % 6;
    $rack = $book->rak ?: ($book->collection_type === 'ta_skripsi' ? 'Rak TA-01' : 'Rak A-01');
?>

<a href="<?php echo e(route('buku.detail', $book->id)); ?>" class="group flex flex-col gap-3 rounded-2xl p-1 transition-transform duration-300 hover:-translate-y-1.5">
    <!-- Book Cover -->
    <div class="relative aspect-[5/7] w-full max-w-[180px] overflow-hidden rounded-[8px] book-shadow shrink-0 transition-shadow duration-300 group-hover:shadow-xl dark:shadow-[0_12px_28px_-10px_rgba(0,0,0,0.7)]">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cover && (file_exists(public_path(ltrim($cover, '/'))) || str_starts_with($cover, 'http'))): ?>
            <img src="<?php echo e(asset(ltrim($cover, '/'))); ?>" alt="Sampul <?php echo e($book->judul); ?>" loading="lazy" class="h-full w-full object-cover">
        <?php else: ?>
            <!-- Typographic Fallback Cover Apple Style -->
            <div class="cover-art-<?php echo e($artIndex); ?> flex h-full w-full flex-col justify-between p-4">
                <div class="font-serif text-[15px] sm:text-[17px] font-semibold leading-[1.1] tracking-tight">
                    <?php echo e($book->judul); ?>

                </div>
                <div class="space-y-1">
                    <p class="text-[11px] font-medium opacity-85 line-clamp-1"><?php echo e($book->penulis); ?></p>
                    <p class="text-[8px] uppercase tracking-widest opacity-60">Pustaka Akuntansi</p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Book Info -->
    <div class="space-y-1">
        <h3 class="text-[15px] font-semibold leading-snug text-slate-900 group-hover:text-emerald-800 dark:group-hover:text-emerald-400 transition-colors line-clamp-2">
            <?php echo e($book->judul); ?>

        </h3>
        <p class="text-[13px] text-slate-500 line-clamp-1">
            <?php echo e($book->penulis); ?>

        </p>
    </div>

    <!-- Liquid Glass Stock Pill -->
    <div class="self-start">
        <span class="liquid-chip inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[12px] font-medium">
            <span class="h-1.5 w-1.5 rounded-full <?php echo e($book->stok > 0 ? 'bg-emerald-500' : 'bg-rose-500'); ?>"></span>
            <span><?php echo e($rack); ?> • <?php echo e($book->stok > 0 ? "Tersedia {$book->stok}" : 'Sedang Habis'); ?></span>
        </span>
    </div>
</a>
<?php /**PATH C:\perpustakaan admin\perpustakaan\resources\views/front/components/book-card.blade.php ENDPATH**/ ?>