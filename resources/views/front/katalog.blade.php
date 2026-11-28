@extends('front.layout')

@section('title', 'Katalog Buku — Perpustakaan Jurusan Akuntansi')

@section('content')
    <style>
        .catalog-prodi-collapse {
            grid-template-rows: 1fr;
            opacity: 1;
            margin-top: 0.625rem;
            transition: grid-template-rows 0.26s var(--apple-ease), opacity 0.22s ease, margin-top 0.26s var(--apple-ease);
        }
        .catalog-prodi-collapse.is-collapsed {
            grid-template-rows: 0fr;
            opacity: 0;
            margin-top: 0;
            pointer-events: none;
        }
        .catalog-prodi-collapse.is-collapsed > div {
            overflow: hidden !important;
        }
        @keyframes catalogCardFadeIn {
            from { opacity: 0; transform: translateY(10px) scale(0.985); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .catalog-card-stagger {
            animation: catalogCardFadeIn 0.34s var(--apple-ease) both;
            animation-delay: calc(min(var(--card-idx, 0), 11) * 26ms);
        }
        @keyframes catalogSkeletonPulse {
            0%, 100% { opacity: 0.55; }
            50% { opacity: 0.9; }
        }
        .catalog-skeleton-pulse {
            animation: catalogSkeletonPulse 1.2s ease-in-out infinite;
        }
    </style>

    <div id="catalogRoot"
         data-base-url="{{ route('katalog') }}"
         data-current-sort="{{ $currentSort }}"
         data-current-kategori="{{ $currentKategori }}"
         data-current-prodi="{{ $currentProdi }}"
         data-current-tahun="{{ $currentTahun }}"
         data-only-available="{{ $onlyAvailable ? '1' : '0' }}"
         data-current-search="{{ $currentSearch }}">

        <!-- HEADER META + SORT / STOCK / MOBILE FILTER BUTTON -->
        <div id="catalogHeaderMeta" class="mb-3">
            @include('front.partials.katalog-header-meta')
        </div>

        <!-- STICKY FILTER BAR -->
        <div id="catalogControls" class="sticky top-[134px] sm:top-[74px] lg:top-[72px] z-20 mb-5">
            @include('front.partials.katalog-controls')
        </div>

        <!-- SKELETON LOADER -->
        <div id="catalogSkeleton" class="hidden grid grid-cols-2 gap-x-3.5 gap-y-6 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5" aria-hidden="true">
            @for($i = 0; $i < 10; $i++)
                <div class="catalog-skeleton-pulse flex flex-col gap-2">
                    <div class="aspect-[5/7] w-full rounded-[10px] bg-slate-200/80 dark:bg-white/10"></div>
                    <div class="h-3.5 w-4/5 rounded-full bg-slate-200/80 dark:bg-white/10"></div>
                    <div class="h-3 w-3/5 rounded-full bg-slate-200/70 dark:bg-white/10"></div>
                    <div class="h-5 w-28 rounded-full bg-slate-200/70 dark:bg-white/10 mt-1"></div>
                </div>
            @endfor
        </div>

        <!-- BOOK RESULTS -->
        <div id="catalogResults">
            @include('front.partials.katalog-results')
        </div>

        <!-- MOBILE BOTTOM SHEET -->
        <div id="catalogSheetHost">
            @include('front.partials.katalog-sheet')
        </div>
    </div>

    <script>
        (function() {
            let activeRequestController = null;

            function closeAllListboxes() {
                document.querySelectorAll('[data-listbox-menu]').forEach(function(menu) {
                    menu.classList.add('hidden');
                });
                document.querySelectorAll('[data-listbox-trigger]').forEach(function(btn) {
                    btn.setAttribute('aria-expanded', 'false');
                    const chev = btn.querySelector('[data-listbox-chevron]');
                    if (chev) chev.classList.remove('rotate-180');
                });
            }

            function openListbox(key) {
                closeAllListboxes();
                const trigger = document.querySelector('[data-listbox-trigger="' + key + '"]');
                const menu = document.querySelector('[data-listbox-menu="' + key + '"]');
                if (!trigger || !menu) return;

                menu.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
                const chev = trigger.querySelector('[data-listbox-chevron]');
                if (chev) chev.classList.add('rotate-180');

                const selectedOpt = menu.querySelector('[role="option"][aria-selected="true"]') || menu.querySelector('[role="option"]');
                if (selectedOpt) selectedOpt.focus();
            }

            function openMobileSheet() {
                const sheet = document.getElementById('catalogMobileSheet');
                const btn = document.getElementById('catalogMobileFilterBtn');
                if (!sheet) return;

                if (sheet.parentElement !== document.body) {
                    document.body.appendChild(sheet);
                }

                sheet.classList.remove('hidden');
                requestAnimationFrame(function() {
                    const backdrop = sheet.querySelector('[data-sheet-backdrop]');
                    const panel = sheet.querySelector('[data-sheet-panel]');
                    if (backdrop) backdrop.classList.remove('opacity-0');
                    if (panel) panel.classList.remove('translate-y-full');
                });
                if (btn) btn.setAttribute('aria-expanded', 'true');
            }

            function closeMobileSheet() {
                const sheet = document.getElementById('catalogMobileSheet');
                const btn = document.getElementById('catalogMobileFilterBtn');
                if (!sheet) return;

                const backdrop = sheet.querySelector('[data-sheet-backdrop]');
                const panel = sheet.querySelector('[data-sheet-panel]');
                if (backdrop) backdrop.classList.add('opacity-0');
                if (panel) panel.classList.add('translate-y-full');
                if (btn) btn.setAttribute('aria-expanded', 'false');

                setTimeout(function() {
                    sheet.classList.add('hidden');
                }, 220);
            }

            function syncMobileSheetSelection(state) {
                const sheet = document.getElementById('catalogMobileSheet');
                if (!sheet) return;

                sheet.querySelectorAll('.sheet-chip-sort').forEach(function(btn) {
                    const active = btn.getAttribute('data-sheet-sort') === state.sort;
                    btn.setAttribute('aria-pressed', active ? 'true' : 'false');
                    btn.classList.toggle('btn-emerald', active);
                    btn.classList.toggle('liquid-chip', !active);
                });

                let isContextual = false;
                sheet.querySelectorAll('.sheet-chip-category').forEach(function(btn) {
                    const active = btn.getAttribute('data-sheet-category') === state.kategori;
                    if (active && btn.getAttribute('data-contextual') === '1') {
                        isContextual = true;
                    }
                    btn.setAttribute('aria-pressed', active ? 'true' : 'false');
                    btn.classList.toggle('btn-emerald', active);
                    btn.classList.toggle('liquid-chip', !active);
                });

                const prodiSec = document.getElementById('sheetProdiSection');
                if (prodiSec) {
                    const showProdi = isContextual || (state.kategori === 'semua' && state.prodi !== 'semua');
                    prodiSec.classList.toggle('hidden', !showProdi);
                    if (!showProdi) {
                        state.prodi = 'semua';
                        state.tahun = 'semua';
                    }
                }

                const serverKategori = readRootState().kategori;
                const categoryChangedInSheet = state.kategori !== serverKategori;

                sheet.querySelectorAll('.sheet-chip-prodi').forEach(function(btn) {
                    const originallyDisabled = btn.hasAttribute('data-originally-disabled');
                    if (categoryChangedInSheet) {
                        btn.disabled = false;
                        btn.removeAttribute('aria-disabled');
                        btn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-slate-100/80', 'dark:bg-white/5', 'text-slate-400');
                    } else if (originallyDisabled) {
                        btn.disabled = true;
                        btn.setAttribute('aria-disabled', 'true');
                        btn.classList.add('opacity-50', 'cursor-not-allowed', 'bg-slate-100/80', 'dark:bg-white/5', 'text-slate-400');
                    }

                    if (btn.disabled) return;
                    const active = btn.getAttribute('data-sheet-prodi') === state.prodi;
                    const isD3 = btn.getAttribute('data-sheet-prodi-level') === 'd3';
                    btn.setAttribute('aria-pressed', active ? 'true' : 'false');
                    btn.classList.remove('btn-emerald', 'btn-amber-gold', 'liquid-chip');
                    if (active) {
                        btn.classList.add(isD3 ? 'btn-amber-gold' : 'btn-emerald');
                    } else {
                        btn.classList.add('liquid-chip');
                    }
                });

                sheet.querySelectorAll('.sheet-chip-tahun').forEach(function(btn) {
                    const active = btn.getAttribute('data-sheet-tahun') === state.tahun;
                    btn.setAttribute('aria-pressed', active ? 'true' : 'false');
                    btn.classList.toggle('btn-emerald', active);
                    btn.classList.toggle('liquid-chip', !active);
                });

                const stockBtn = document.getElementById('sheetOnlyAvailableToggle');
                const stockIndicator = document.getElementById('sheetStockIndicator');
                if (stockBtn) stockBtn.setAttribute('aria-pressed', state.tersedia === '1' ? 'true' : 'false');
                if (stockIndicator) {
                    stockIndicator.classList.toggle('bg-emerald-500', state.tersedia === '1');
                    stockIndicator.classList.toggle('justify-end', state.tersedia === '1');
                    stockIndicator.classList.toggle('bg-slate-300', state.tersedia !== '1');
                    stockIndicator.classList.toggle('dark:bg-white/20', state.tersedia !== '1');
                    stockIndicator.classList.toggle('justify-start', state.tersedia !== '1');
                }
            }

            let draftSheetState = null;

            function readRootState() {
                const root = document.getElementById('catalogRoot');
                if (!root) return { sort: 'terbaru', kategori: 'semua', prodi: 'semua', tahun: 'semua', tersedia: '0', q: '' };
                return {
                    sort: root.dataset.currentSort || 'terbaru',
                    kategori: root.dataset.currentKategori || 'semua',
                    prodi: root.dataset.currentProdi || 'semua',
                    tahun: root.dataset.currentTahun || 'semua',
                    tersedia: root.dataset.onlyAvailable || '0',
                    q: root.dataset.currentSearch || '',
                };
            }

            async function catalogFetchUrl(targetHref, pushState) {
                const root = document.getElementById('catalogRoot');
                const headerMetaEl = document.getElementById('catalogHeaderMeta');
                const controlsEl = document.getElementById('catalogControls');
                const resultsEl = document.getElementById('catalogResults');
                const skeletonEl = document.getElementById('catalogSkeleton');
                const sheetHostEl = document.getElementById('catalogSheetHost');
                if (!root || !controlsEl || !resultsEl) {
                    window.location.href = targetHref;
                    return;
                }

                closeAllListboxes();

                if (activeRequestController) {
                    activeRequestController.abort();
                }
                activeRequestController = new AbortController();

                const currentProdiPanel = document.getElementById('catalogProdiCollapse');
                const wasProdiVisible = currentProdiPanel && currentProdiPanel.getAttribute('data-visible') === '1';

                const parsedTarget = new URL(targetHref, window.location.origin);
                const clickedCat = parsedTarget.searchParams.get('kategori') || 'semua';
                const targetContextualLink = controlsEl.querySelector('a[data-catalog-nav][href*="kategori=' + encodeURIComponent(clickedCat) + '"]');
                const willHideProdiSoon = wasProdiVisible && (!parsedTarget.searchParams.get('prodi')) && (
                    clickedCat === 'semua' || (targetContextualLink && targetContextualLink.getAttribute('data-contextual') === '0')
                );

                if (willHideProdiSoon && currentProdiPanel) {
                    currentProdiPanel.classList.add('is-collapsed');
                }

                if (skeletonEl) {
                    resultsEl.classList.add('hidden');
                    skeletonEl.classList.remove('hidden');
                }

                try {
                    const fetchPromise = fetch(parsedTarget.href, {
                        headers: {
                            'X-Catalog-Fragment': '1',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                        signal: activeRequestController.signal,
                    });

                    const collapseDelay = willHideProdiSoon
                        ? new Promise(function(resolve) { setTimeout(resolve, 180); })
                        : Promise.resolve();

                    const [response] = await Promise.all([fetchPromise, collapseDelay]);
                    if (!response.ok) throw new Error('HTTP ' + response.status);

                    const payload = await response.json();

                    if (pushState) {
                        history.pushState({ catalogUrl: parsedTarget.href }, '', parsedTarget.href);
                    }

                    const topSearchInputs = document.querySelectorAll('#appHeader input[name="q"]');
                    const nextQ = parsedTarget.searchParams.get('q') || '';
                    topSearchInputs.forEach(function(inp) { inp.value = nextQ; });

                    if (headerMetaEl && typeof payload.headerMetaHtml === 'string') {
                        headerMetaEl.innerHTML = payload.headerMetaHtml;
                    }

                    if (typeof payload.controlsHtml === 'string') {
                        controlsEl.innerHTML = payload.controlsHtml;
                        const nextProdiPanel = document.getElementById('catalogProdiCollapse');
                        if (!wasProdiVisible && payload.showProdiSubfilter && nextProdiPanel) {
                            nextProdiPanel.classList.add('is-collapsed');
                            requestAnimationFrame(function() {
                                nextProdiPanel.classList.remove('is-collapsed');
                            });
                        }
                    }

                    if (typeof payload.resultsHtml === 'string') {
                        resultsEl.innerHTML = payload.resultsHtml;
                    }

                    if (sheetHostEl && typeof payload.sheetHtml === 'string') {
                        document.querySelectorAll('body > #catalogMobileSheet').forEach(function(el) { el.remove(); });
                        sheetHostEl.innerHTML = payload.sheetHtml;
                    }

                    root.dataset.currentSort = parsedTarget.searchParams.get('sort') || 'terbaru';
                    root.dataset.currentKategori = parsedTarget.searchParams.get('kategori') || 'semua';
                    root.dataset.currentProdi = parsedTarget.searchParams.get('prodi') || 'semua';
                    root.dataset.currentTahun = parsedTarget.searchParams.get('tahun') || 'semua';
                    root.dataset.onlyAvailable = parsedTarget.searchParams.get('tersedia') === '1' ? '1' : '0';
                    root.dataset.currentSearch = nextQ;
                } catch (err) {
                    if (err && err.name === 'AbortError') return;
                    window.location.href = targetHref;
                    return;
                } finally {
                    if (skeletonEl) skeletonEl.classList.add('hidden');
                    if (resultsEl) resultsEl.classList.remove('hidden');
                }
            }

            window.catalogFetchUrl = catalogFetchUrl;
            window.catalogHandlePopState = function(href) {
                catalogFetchUrl(href, false);
            };

            if (!window.__catalogInteractiveBound) {
                window.__catalogInteractiveBound = true;

                document.addEventListener('click', function(e) {
                    const target = e.target;
                    if (!target || typeof target.closest !== 'function') return;
                    if (!document.getElementById('catalogRoot')) return;

                    // 1. Accessible Listbox triggers (Urutkan & Tahun)
                    const listboxTrigger = target.closest('[data-listbox-trigger]');
                    if (listboxTrigger) {
                        e.preventDefault();
                        e.stopPropagation();
                        const key = listboxTrigger.getAttribute('data-listbox-trigger');
                        const menu = document.querySelector('[data-listbox-menu="' + key + '"]');
                        if (menu && menu.classList.contains('hidden')) {
                            openListbox(key);
                        } else {
                            closeAllListboxes();
                        }
                        return;
                    }

                    // 2. Mobile Bottom Sheet open / close / interactions
                    if (target.closest('#catalogMobileFilterBtn')) {
                        e.preventDefault();
                        draftSheetState = readRootState();
                        syncMobileSheetSelection(draftSheetState);
                        openMobileSheet();
                        return;
                    }

                    if (target.closest('[data-sheet-close]') || target.closest('[data-sheet-backdrop]')) {
                        e.preventDefault();
                        closeMobileSheet();
                        return;
                    }

                    const sortChip = target.closest('.sheet-chip-sort');
                    if (sortChip && draftSheetState) {
                        draftSheetState.sort = sortChip.getAttribute('data-sheet-sort') || 'terbaru';
                        syncMobileSheetSelection(draftSheetState);
                        return;
                    }

                    const catChip = target.closest('.sheet-chip-category');
                    if (catChip && draftSheetState) {
                        draftSheetState.kategori = catChip.getAttribute('data-sheet-category') || 'semua';
                        if (catChip.getAttribute('data-contextual') !== '1') {
                            draftSheetState.prodi = 'semua';
                            draftSheetState.tahun = 'semua';
                        }
                        syncMobileSheetSelection(draftSheetState);
                        return;
                    }

                    const prodiChip = target.closest('.sheet-chip-prodi');
                    if (prodiChip && !prodiChip.disabled && draftSheetState) {
                        draftSheetState.prodi = prodiChip.getAttribute('data-sheet-prodi') || 'semua';
                        syncMobileSheetSelection(draftSheetState);
                        return;
                    }

                    const tahunChip = target.closest('.sheet-chip-tahun');
                    if (tahunChip && draftSheetState) {
                        draftSheetState.tahun = tahunChip.getAttribute('data-sheet-tahun') || 'semua';
                        syncMobileSheetSelection(draftSheetState);
                        return;
                    }

                    if (target.closest('#sheetOnlyAvailableToggle') && draftSheetState) {
                        draftSheetState.tersedia = draftSheetState.tersedia === '1' ? '0' : '1';
                        syncMobileSheetSelection(draftSheetState);
                        return;
                    }

                    if (target.closest('#sheetResetBtn')) {
                        e.preventDefault();
                        const root = document.getElementById('catalogRoot');
                        closeMobileSheet();
                        catalogFetchUrl(root.dataset.baseUrl || window.location.pathname, true);
                        return;
                    }

                    if (target.closest('#sheetApplyBtn') && draftSheetState) {
                        e.preventDefault();
                        const root = document.getElementById('catalogRoot');
                        const applyUrl = new URL(root.dataset.baseUrl || window.location.pathname, window.location.origin);
                        if (draftSheetState.q) applyUrl.searchParams.set('q', draftSheetState.q);
                        if (draftSheetState.kategori && draftSheetState.kategori !== 'semua') applyUrl.searchParams.set('kategori', draftSheetState.kategori);
                        if (draftSheetState.prodi && draftSheetState.prodi !== 'semua') applyUrl.searchParams.set('prodi', draftSheetState.prodi);
                        if (draftSheetState.tahun && draftSheetState.tahun !== 'semua') applyUrl.searchParams.set('tahun', draftSheetState.tahun);
                        if (draftSheetState.sort && draftSheetState.sort !== 'terbaru') applyUrl.searchParams.set('sort', draftSheetState.sort);
                        if (draftSheetState.tersedia === '1') applyUrl.searchParams.set('tersedia', '1');
                        closeMobileSheet();
                        catalogFetchUrl(applyUrl.href, true);
                        return;
                    }

                    // 3. No-reload filter & pagination links
                    const navLink = target.closest('a[data-catalog-nav], #catalogPagination a[href]');
                    if (navLink) {
                        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                        e.preventDefault();
                        catalogFetchUrl(navLink.href, true);
                        return;
                    }

                    // 4. Click outside listboxes
                    if (!target.closest('[data-listbox-root]')) {
                        closeAllListboxes();
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (!document.getElementById('catalogRoot')) return;

                    if (e.key === 'Escape') {
                        closeAllListboxes();
                        closeMobileSheet();
                        return;
                    }

                    const activeMenu = document.querySelector('[data-listbox-menu]:not(.hidden)');
                    if (!activeMenu) return;

                    const options = Array.from(activeMenu.querySelectorAll('[role="option"]'));
                    if (options.length === 0) return;

                    const currentIdx = options.indexOf(document.activeElement);
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextIdx = currentIdx < options.length - 1 ? currentIdx + 1 : 0;
                        options[nextIdx].focus();
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevIdx = currentIdx > 0 ? currentIdx - 1 : options.length - 1;
                        options[prevIdx].focus();
                    } else if (e.key === 'Home') {
                        e.preventDefault();
                        options[0].focus();
                    } else if (e.key === 'End') {
                        e.preventDefault();
                        options[options.length - 1].focus();
                    }
                });
            }
        })();
    </script>
@endsection
