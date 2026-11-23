<?php $__env->startSection('title', $book->judul . ' — Pustaka Akuntansi'); ?>

<?php
    $cover = $book->cover;
    $artIndex = abs(crc32($book->judul)) % 6;
    $rack = $book->rak ?: ($book->collection_type === 'ta_skripsi' ? 'Rak TA-01' : 'Rak A-02');
    $loanDuration = $book->getLoanDurationWeekdays();
    $estReturn = today()->copy()->addWeekdays($loanDuration);
?>

<?php $__env->startSection('content'); ?>
    <!-- BREADCRUMB -->
    <nav class="mb-8 flex items-center gap-2 text-sm text-slate-500">
        <a href="<?php echo e(route('katalog')); ?>" class="hover:text-emerald-800 dark:hover:text-emerald-400 hover:underline">Katalog</a>
        <span>/</span>
        <a href="<?php echo e(route('katalog', ['kategori' => $book->category?->slug ?? 'semua'])); ?>" class="hover:text-emerald-800 dark:hover:text-emerald-400 hover:underline">
            <?php echo e($book->category?->nama ?? 'Umum'); ?>

        </a>
        <span>/</span>
        <span class="truncate text-slate-800 dark:text-slate-200 font-medium max-w-[200px] sm:max-w-none"><?php echo e($book->judul); ?></span>
    </nav>

    <!-- BOOK MAIN HERO SECTION -->
    <div class="grid grid-cols-1 gap-10 md:grid-cols-12 lg:gap-14">
        <!-- LEFT: COVER & FAVORITE BUTTON -->
        <div class="md:col-span-5 lg:col-span-4 flex flex-col items-center sm:items-start">
            <div class="relative aspect-[5/7] w-full max-w-[320px] overflow-hidden rounded-[12px] book-shadow dark:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.8)]">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cover && (file_exists(public_path(ltrim($cover, '/'))) || str_starts_with($cover, 'http'))): ?>
                    <img src="<?php echo e(asset(ltrim($cover, '/'))); ?>" alt="Sampul <?php echo e($book->judul); ?>" class="h-full w-full object-cover">
                <?php else: ?>
                    <div class="cover-art-<?php echo e($artIndex); ?> flex h-full w-full flex-col justify-between p-7">
                        <div class="font-serif text-[24px] sm:text-[28px] font-semibold leading-[1.1] tracking-tight">
                            <?php echo e($book->judul); ?>

                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium opacity-90"><?php echo e($book->penulis); ?></p>
                            <p class="text-[10px] uppercase tracking-widest opacity-60">Pustaka Akuntansi</p>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($student): ?>
            <?php
                $isFavorited = $book->isFavoritedBy($student);
            ?>
            <button type="button"
                    id="btnToggleFav"
                    onclick="toggleFavoriteBook(<?php echo e($book->id); ?>)"
                    class="<?php echo e($isFavorited ? 'bg-amber-100/90 text-amber-900 border-amber-300 shadow-sm dark:bg-amber-950/70 dark:text-amber-300 dark:border-amber-700' : 'btn-glass text-slate-700 dark:text-slate-200'); ?> mt-5 flex w-full max-w-[320px] items-center justify-center gap-2 rounded-full py-2.5 text-sm font-semibold transition-all">
                <svg id="favStarIcon" class="h-4 w-4 <?php echo e($isFavorited ? 'text-amber-600 fill-amber-500' : 'text-amber-500'); ?>" fill="<?php echo e($isFavorited ? 'currentColor' : 'none'); ?>" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
                <span id="favTextLabel"><?php echo e($isFavorited ? '★ Tersimpan di Favorit' : '☆ Simpan ke Favorit'); ?></span>
            </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- RIGHT: BOOK SPECS & BORROW ACTION -->
        <div class="md:col-span-7 lg:col-span-8 flex flex-col justify-between">
            <div>
                <!-- Category Tag (Liquid Glass Badge) -->
                <span class="inline-flex items-center rounded-full liquid-badge-emerald px-3.5 py-1 text-xs font-semibold mb-3">
                    <?php echo e($book->category?->nama ?? 'Buku Akuntansi'); ?>

                </span>

                <!-- Title & Author -->
                <h1 class="text-[30px] sm:text-[38px] font-bold tracking-tight text-slate-900 leading-tight">
                    <?php echo e($book->judul); ?>

                </h1>
                <p class="mt-1 text-[17px] font-medium text-slate-600 dark:text-slate-300">
                    <?php echo e($book->penulis); ?>

                </p>

                <!-- Metadata Grid -->
                <div class="mt-8 grid grid-cols-2 gap-y-4 gap-x-8 text-[14px] border-t border-b border-slate-200/70 dark:border-white/10 py-6">
                    <div>
                        <span class="block text-slate-500 text-xs">Penerbit:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200"><?php echo e($book->penerbit ?? 'Pustaka Jurusan'); ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-xs">Tahun & Edisi:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200"><?php echo e($book->tahun_terbit ?? '2024'); ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-xs">ISBN / Kode Buku:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200"><?php echo e($book->isbn ?? $book->book_code ?? '-'); ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-xs">No. Klasifikasi:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200"><?php echo e($book->collection_type_label); ?></span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-xs">Lokasi Rak:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">Lantai 2 • <?php echo e($rack); ?> • Baris 2</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-xs">Aturan Peminjaman:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">Maks. <?php echo e($loanDuration); ?> Hari Kerja • Denda Rp <?php echo e($book->collection_type === 'ta_skripsi' ? '300' : '200'); ?>/hari</span>
                    </div>
                </div>

                <!-- Availability Badge Pill (Liquid Glass Pill) -->
                <div class="mt-6">
                    <div class="inline-flex items-center gap-2 rounded-full liquid-chip px-4 py-2 text-[13px] font-medium">
                        <span class="h-2 w-2 rounded-full <?php echo e($book->stok > 0 ? 'bg-emerald-500' : 'bg-rose-500'); ?>"></span>
                        <span><?php echo e($book->stok > 0 ? "Tersedia {$book->stok} dari {$book->stok} Eksemplar" : 'Stok sedang tidak tersedia'); ?></span>
                        <span class="text-slate-400">•</span>
                        <span><?php echo e($rack); ?></span>
                        <span class="text-slate-400">•</span>
                        <span>Maks. Pinjam <?php echo e($loanDuration); ?> Hari</span>
                    </div>
                </div>

                <!-- Action Button Section -->
                <div class="mt-8 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('student_id')): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($book->stok > 0): ?>
                            <button type="button" onclick="document.getElementById('bookingModal').classList.remove('hidden')"
                                    class="btn-emerald h-12 rounded-full px-8 text-[15px] font-semibold w-full sm:w-auto">
                                Ajukan Pinjam Buku
                            </button>
                            <span class="text-xs text-slate-500 font-medium">Ambil fisik di ruang baca jurusan (maks. 1&times;24 jam)</span>
                        <?php else: ?>
                            <button type="button" disabled class="h-12 rounded-full px-8 text-[15px] font-semibold bg-slate-200 dark:bg-white/10 text-slate-400 cursor-not-allowed">
                                Stok Buku Sedang Habis
                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <!-- Guest User CTA (Apple Liquid Amber / Honey Gold) -->
                        <a href="<?php echo e(route('masuk')); ?>" class="btn-amber-gold flex h-12 items-center justify-center rounded-full px-8 text-[15px] font-semibold w-full sm:w-auto shadow-md">
                            Masuk untuk Meminjam Buku
                        </a>
                        <span class="text-xs text-slate-500 font-medium">Ambil fisik di ruang baca jurusan (maks. 1&times;24 jam)</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- DESKRIPSI BUKU -->
    <section class="mt-14 max-w-4xl">
        <h2 class="text-[20px] font-bold tracking-tight text-slate-900 mb-4">Deskripsi</h2>
        <div class="text-[15px] leading-relaxed text-slate-600 dark:text-slate-300">
            <?php echo e($book->sinopsis ?? 'Buku koleksi ruang baca Jurusan Akuntansi Politeknik Negeri Semarang, memuat materi pembelajaran perkuliahan, teori akuntansi, dan studi kasus praktis.'); ?>

        </div>
    </section>

    <!-- BUKU TERKAIT -->
    <section class="mt-14 border-t border-slate-200/70 dark:border-white/10 pt-8">
        <h2 class="text-[20px] font-bold tracking-tight text-slate-900 mb-6">Buku Terkait</h2>
        <div class="grid grid-cols-2 gap-x-5 gap-y-8 sm:grid-cols-3 xl:grid-cols-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $relatedBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('front.components.book-card', ['book' => $rel], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    <!-- MODAL KONFIRMASI PEMINJAMAN FISIK (Apple Liquid Glass) -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($student): ?>
        <div id="bookingModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
            <!-- Scrim Backdrop -->
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity" onclick="document.getElementById('bookingModal').classList.add('hidden')"></div>

            <!-- Dialog Window -->
            <div class="glass-card relative z-10 w-full max-w-[500px] overflow-hidden rounded-[32px] p-7 sm:p-9 shadow-2xl border border-white/95 dark:border-white/10 animate-rise">
                <h3 class="text-[24px] font-bold tracking-tight text-slate-900">Konfirmasi Peminjaman Fisik</h3>

                <!-- Mini Book Info -->
                <div class="mt-6 flex items-center gap-4">
                    <div class="relative aspect-[5/7] w-14 shrink-0 overflow-hidden rounded-[4px] book-shadow">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cover && (file_exists(public_path(ltrim($cover, '/'))) || str_starts_with($cover, 'http'))): ?>
                            <img src="<?php echo e(asset(ltrim($cover, '/'))); ?>" alt="<?php echo e($book->judul); ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                            <div class="cover-art-<?php echo e($artIndex); ?> h-full w-full p-1.5 flex flex-col justify-end">
                                <span class="font-serif text-[7px] font-bold leading-tight"><?php echo e($book->judul); ?></span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <h4 class="text-[16px] font-bold text-slate-900 leading-snug"><?php echo e($book->judul); ?></h4>
                        <p class="text-xs text-slate-500 mt-0.5"><?php echo e($book->penulis); ?> • <?php echo e($rack); ?></p>
                    </div>
                </div>

                <!-- Form Action -->
                <form action="<?php echo e(route('buku.booking', $book->id)); ?>" method="POST" class="mt-6">
                    <?php echo csrf_field(); ?>

                    <!-- Segmented Duration Toggle -->
                    <div class="segment-track flex rounded-full mb-4">
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="duration" value="3" class="sr-only peer" onchange="updateDueDate(3)">
                            <div class="py-2 text-xs font-semibold rounded-full peer-checked:bg-white dark:peer-checked:bg-emerald-500/20 peer-checked:text-emerald-800 dark:peer-checked:text-emerald-300 peer-checked:shadow-sm text-slate-600 dark:text-slate-400 transition-all">
                                3 Hari
                            </div>
                        </label>
                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="duration" value="<?php echo e($loanDuration); ?>" checked class="sr-only peer" onchange="updateDueDate(<?php echo e($loanDuration); ?>)">
                            <div class="py-2 text-xs font-semibold rounded-full peer-checked:bg-white dark:peer-checked:bg-emerald-500/20 peer-checked:text-emerald-800 dark:peer-checked:text-emerald-300 peer-checked:shadow-sm text-slate-600 dark:text-slate-400 transition-all">
                                <?php echo e($loanDuration); ?> Hari (Standar)
                            </div>
                        </label>
                    </div>

                    <!-- Return Date Preview -->
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                        Kembali maks: <span id="dueDateText" class="text-emerald-800 dark:text-emerald-400"><?php echo e($estReturn->translatedFormat('d F Y')); ?></span>
                    </p>

                    <!-- Pearl Info Box -->
                    <div class="pearl-card mt-6 rounded-[20px] p-4 text-[13px] leading-relaxed text-slate-600 dark:text-slate-300">
                        <strong class="text-slate-800 dark:text-slate-100">Ketentuan Booking:</strong><br>
                        Batas ambil: <strong class="text-emerald-800 dark:text-emerald-400">Besok 16.00 WIB</strong> (1x24 jam).<br>
                        Denda keterlambatan: <strong>Rp <?php echo e($book->collection_type === 'ta_skripsi' ? '300' : '200'); ?>/hari kerja</strong>.
                    </div>

                    <!-- Modal Actions -->
                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="document.getElementById('bookingModal').classList.add('hidden')"
                                class="btn-glass w-32 h-11 rounded-full text-sm font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                                class="btn-emerald flex-1 h-11 rounded-full text-sm font-semibold">
                            Konfirmasi Pinjam
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function updateDueDate(days) {
                const now = new Date();
                let added = 0;
                while (added < days) {
                    now.setDate(now.getDate() + 1);
                    const day = now.getDay();
                    if (day !== 0 && day !== 6) added++;
                }
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                document.getElementById('dueDateText').textContent = now.toLocaleDateString('id-ID', options);
            }

            function toggleFavoriteBook(bookId) {
                const btn = document.getElementById('btnToggleFav');
                const label = document.getElementById('favTextLabel');
                const icon = document.getElementById('favStarIcon');

                fetch(`/buku/${bookId}/favorit`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.is_favorited) {
                            btn.className = 'bg-amber-100/90 text-amber-900 border-amber-300 shadow-sm dark:bg-amber-950/70 dark:text-amber-300 dark:border-amber-700 mt-5 flex w-full max-w-[320px] items-center justify-center gap-2 rounded-full py-2.5 text-sm font-semibold transition-all';
                            icon.setAttribute('fill', 'currentColor');
                            icon.className = 'h-4 w-4 text-amber-600 fill-amber-500';
                            label.textContent = '★ Tersimpan di Favorit';
                        } else {
                            btn.className = 'btn-glass text-slate-700 dark:text-slate-200 mt-5 flex w-full max-w-[320px] items-center justify-center gap-2 rounded-full py-2.5 text-sm font-semibold transition-all';
                            icon.setAttribute('fill', 'none');
                            icon.className = 'h-4 w-4 text-amber-500';
                            label.textContent = '☆ Simpan ke Favorit';
                        }
                    }
                })
                .catch(err => console.error(err));
            }
        </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('front.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\perpustakaan admin\perpustakaan\resources\views/front/buku.blade.php ENDPATH**/ ?>