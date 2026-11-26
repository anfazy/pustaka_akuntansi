@extends('front.auth-layout')

@section('title', 'Daftar Anggota Baru — Perpustakaan Jurusan Akuntansi')

@section('content')
    <div class="glass-card w-full max-w-[540px] rounded-[22px] p-4 sm:p-5 shadow-2xl animate-rise">
        <!-- Back to Beranda Link & Login Shortcut -->
        <div class="flex items-center justify-between gap-3 mb-3">
            <a href="{{ route('beranda') }}" class="inline-flex min-h-[34px] items-center gap-1.5 text-[11.5px] font-semibold text-slate-500 hover:text-emerald-800 dark:hover:text-emerald-400 transition-colors">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Beranda</span>
            </a>

            <a href="{{ route('masuk') }}" class="inline-flex min-h-[32px] items-center gap-1.5 rounded-full liquid-badge-emerald px-3 text-[11.5px] font-semibold hover:opacity-90 transition">
                <span>Sudah punya akun? Masuk</span>
            </a>
        </div>

        <!-- Brand Header with Official HMJ Logo -->
        <div class="flex items-center gap-2.5 mb-3">
            <div class="grid h-9 w-9 shrink-0 place-items-center rounded-[10px] bg-white p-1 shadow-sm shadow-emerald-950/15 border border-white/80 dark:border-white/20 overflow-hidden">
                <img src="{{ asset('images/logo-hmj-akuntansi.jpg') }}" alt="Logo Perpustakaan Jurusan Akuntansi" class="h-full w-full object-contain">
            </div>
            <div>
                <span class="block text-[13.5px] font-bold leading-tight tracking-tight text-slate-900">Perpustakaan Jurusan Akuntansi</span>
                <span class="block text-[11px] text-emerald-800 dark:text-emerald-400 font-medium">Ruang Baca Jurusan Polines</span>
            </div>
        </div>

        <h1 class="text-[20px] sm:text-[22px] font-bold tracking-tight text-slate-900 mb-3.5">Daftar Anggota Baru</h1>

        <!-- REGISTRATION FORM -->
        <form action="{{ route('daftar.proses') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-x-3.5 gap-y-2.5 sm:grid-cols-2">
                <!-- Nama Lengkap -->
                <div class="sm:col-span-2">
                    <label for="namaInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-[12px] px-3 py-2">
                        <input type="text" id="namaInput" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap" class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('nama') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- NIM -->
                <div>
                    <label for="nimRegisterInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">NIM <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-[12px] px-3 py-2">
                        <input type="text" id="nimRegisterInput" name="nim_no_anggota" value="{{ old('nim_no_anggota') }}" required placeholder="Contoh: 4.41.24.0.12" class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('nim_no_anggota') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kelas -->
                <div>
                    <label for="kelasInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Kelas <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-[12px] px-3 py-2">
                        <input type="text" id="kelasInput" name="kelas" value="{{ old('kelas') }}" required placeholder="Contoh: AKT-3A" class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('kelas') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Program Studi -->
                <div class="relative" id="prodiCombobox">
                    <label for="prodiInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Program Studi <span class="text-rose-500">*</span></label>
                    <div class="glass-field relative flex items-center rounded-[12px] px-3 py-2">
                        <input type="text"
                               name="program_studi"
                               id="prodiInput"
                               value="{{ old('program_studi') }}"
                               required
                               autocomplete="off"
                               placeholder="Pilih atau ketik prodi..."
                               class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none pr-7 placeholder:text-slate-400">
                        <button type="button"
                                id="prodiToggleBtn"
                                aria-label="Buka pilihan program studi"
                                class="absolute right-1 grid h-8 w-8 place-items-center rounded-full text-slate-400 hover:text-emerald-700 active:scale-95 transition"
                                title="Buka Pilihan Prodi">
                            <svg id="prodiChevron" class="h-3.5 w-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>
                    @error('program_studi') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror

                    <!-- Floating Apple Liquid Glass Dropdown Options -->
                    <div id="prodiDropdown" class="hidden absolute left-0 right-0 z-50 mt-1.5 max-h-56 overflow-y-auto rounded-2xl border border-white/80 bg-white/95 p-1.5 shadow-2xl backdrop-blur-xl transition-all">
                        <div class="px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Pilihan Jurusan Akuntansi Polines</div>
                        <div id="prodiOptionsList" class="space-y-0.5 mt-0.5">
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-[12.5px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Komputerisasi Akuntansi (S. Tr)">
                                <span>Komputerisasi Akuntansi (S. Tr)</span>
                                <span class="rounded-full bg-emerald-100/80 px-2 py-0.5 text-[9.5px] font-bold text-emerald-800">S. Tr</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-[12.5px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Perbankan Syariah (S. Tr)">
                                <span>Perbankan Syariah (S. Tr)</span>
                                <span class="rounded-full bg-emerald-100/80 px-2 py-0.5 text-[9.5px] font-bold text-emerald-800">S. Tr</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-[12.5px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Analis Keuangan (S. Tr)">
                                <span>Analis Keuangan (S. Tr)</span>
                                <span class="rounded-full bg-emerald-100/80 px-2 py-0.5 text-[9.5px] font-bold text-emerald-800">S. Tr</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-[12.5px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Akuntansi Manajerial (S. Tr)">
                                <span>Akuntansi Manajerial (S. Tr)</span>
                                <span class="rounded-full bg-emerald-100/80 px-2 py-0.5 text-[9.5px] font-bold text-emerald-800">S. Tr</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-[12.5px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Akuntansi (D3)">
                                <span>Akuntansi (D3)</span>
                                <span class="rounded-full bg-amber-100/80 px-2 py-0.5 text-[9.5px] font-bold text-amber-800">D3</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-[12.5px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Keuangan dan Perbankan (D3)">
                                <span>Keuangan dan Perbankan (D3)</span>
                                <span class="rounded-full bg-amber-100/80 px-2 py-0.5 text-[9.5px] font-bold text-amber-800">D3</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Angkatan -->
                <div>
                    <label for="angkatanInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Angkatan <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-[12px] px-3 py-2">
                        <input type="number" id="angkatanInput" name="tahun_angkatan" value="{{ old('tahun_angkatan') }}" min="2000" max="{{ date('Y') + 1 }}" required placeholder="Contoh: 2024" class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('tahun_angkatan') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nomor WhatsApp -->
                <div>
                    <label for="noHpInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-[12px] px-3 py-2">
                        <input type="tel" id="noHpInput" name="no_hp" value="{{ old('no_hp') }}" required placeholder="081234567890" class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    <p id="noHpHint" class="hidden text-[11px] font-medium text-rose-600 mt-1"></p>
                    @error('no_hp') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email Kampus -->
                <div>
                    <label for="emailKampusInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Kampus <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-[12px] px-3 py-2">
                        <input type="email" id="emailKampusInput" name="email" value="{{ old('email') }}" required placeholder="nama@mhs.polines.ac.id" class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    <p id="emailKampusHint" class="hidden text-[11px] font-medium text-rose-600 mt-1"></p>
                    @error('email') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="alamatInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-[12px] px-3 py-2">
                        <input type="text" id="alamatInput" name="alamat" value="{{ old('alamat') }}" required placeholder="Jl. Prof. Sudarto, Tembalang" class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('alamat') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kota / Kabupaten -->
                <div>
                    <label for="kotaInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-[12px] px-3 py-2">
                        <input type="text" id="kotaInput" name="kota" value="{{ old('kota') }}" required placeholder="Kota Semarang" class="w-full bg-transparent text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('kota') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="registerPasswordInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Buat Kata Sandi <span class="text-rose-500">*</span></label>
                    <div class="glass-field relative flex items-center rounded-[12px] px-3 py-2">
                        <input type="password" name="password" id="registerPasswordInput" minlength="8" required placeholder="Minimal 8 karakter" class="w-full bg-transparent pr-8 text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                        <button type="button" onclick="togglePasswordVisibility('registerPasswordInput', this)" aria-label="Tampilkan atau sembunyikan kata sandi" class="absolute right-1 grid h-8 w-8 place-items-center rounded-full text-slate-400 hover:text-emerald-700 active:scale-95 transition">
                            <svg class="eye-open h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg class="eye-closed hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <!-- Indikator Kekuatan Kata Sandi -->
                    <div class="mt-1.5 flex items-center gap-2">
                        <div class="grid flex-1 grid-cols-4 gap-1">
                            <span id="pwSeg1" class="h-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-200"></span>
                            <span id="pwSeg2" class="h-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-200"></span>
                            <span id="pwSeg3" class="h-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-200"></span>
                            <span id="pwSeg4" class="h-1 rounded-full bg-slate-200 dark:bg-slate-700 transition-colors duration-200"></span>
                        </div>
                        <span id="pwStrengthLabel" class="text-[10.5px] font-medium text-slate-400 whitespace-nowrap">Min. 8 karakter</span>
                    </div>
                    @error('password') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div>
                    <label for="registerPasswordConfirmInput" class="block text-[10.5px] font-semibold text-slate-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                    <div class="glass-field relative flex items-center rounded-[12px] px-3 py-2">
                        <input type="password" name="password_confirmation" id="registerPasswordConfirmInput" minlength="8" required placeholder="Ulangi kata sandi" class="w-full bg-transparent pr-8 text-[13px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                        <button type="button" onclick="togglePasswordVisibility('registerPasswordConfirmInput', this)" aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi" class="absolute right-1 grid h-8 w-8 place-items-center rounded-full text-slate-400 hover:text-emerald-700 active:scale-95 transition">
                            <svg class="eye-open h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg class="eye-closed hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <p id="pwMatchHint" class="hidden text-[11px] font-medium mt-1"></p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <button type="submit" class="btn-amber-gold w-full h-10 rounded-full text-[13.5px] font-semibold shadow-md">
                    Ajukan Pendaftaran Anggota
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const prodiInput = document.getElementById('prodiInput');
            const prodiDropdown = document.getElementById('prodiDropdown');
            const prodiToggleBtn = document.getElementById('prodiToggleBtn');
            const prodiChevron = document.getElementById('prodiChevron');
            const prodiItems = document.querySelectorAll('.prodi-item');
            const combobox = document.getElementById('prodiCombobox');

            function openDropdown() {
                prodiDropdown.classList.remove('hidden');
                if (prodiChevron) prodiChevron.classList.add('rotate-180');
            }

            function closeDropdown() {
                prodiDropdown.classList.add('hidden');
                if (prodiChevron) prodiChevron.classList.remove('rotate-180');
            }

            prodiInput.addEventListener('focus', openDropdown);
            prodiInput.addEventListener('click', openDropdown);

            prodiToggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                if (prodiDropdown.classList.contains('hidden')) {
                    openDropdown();
                    prodiInput.focus();
                } else {
                    closeDropdown();
                }
            });

            // Filter options dynamically on input
            prodiInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                openDropdown();
                let hasMatch = false;
                prodiItems.forEach(item => {
                    const label = item.getAttribute('data-label').toLowerCase();
                    if (label.includes(query)) {
                        item.style.display = 'flex';
                        hasMatch = true;
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Select an option
            prodiItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const val = this.getAttribute('data-label');
                    prodiInput.value = val;
                    closeDropdown();
                });
            });

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (combobox && !combobox.contains(e.target)) {
                    closeDropdown();
                }
            });

            // Password strength & confirmation match indicator
            const pwInput = document.getElementById('registerPasswordInput');
            const pwConfirmInput = document.getElementById('registerPasswordConfirmInput');
            const pwSegs = [1, 2, 3, 4].map(i => document.getElementById('pwSeg' + i));
            const pwLabel = document.getElementById('pwStrengthLabel');
            const pwMatchHint = document.getElementById('pwMatchHint');

            function updatePasswordStrength() {
                if (!pwInput || !pwLabel) return;
                const val = pwInput.value;
                let score = 0;
                if (val.length >= 8) score++;
                if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
                if (/\d/.test(val)) score++;
                if (/[^a-zA-Z0-9]/.test(val)) score++;

                const configs = [
                    { text: 'Min. 8 karakter', color: 'bg-slate-200 dark:bg-slate-700', textColor: 'text-slate-400' },
                    { text: 'Lemah', color: 'bg-rose-500', textColor: 'text-rose-600' },
                    { text: 'Cukup', color: 'bg-amber-500', textColor: 'text-amber-600' },
                    { text: 'Kuat', color: 'bg-emerald-500', textColor: 'text-emerald-600' },
                    { text: 'Sangat Kuat', color: 'bg-emerald-600', textColor: 'text-emerald-700' },
                ];

                const level = val.length === 0 ? 0 : (val.length < 8 ? 1 : Math.max(1, score));
                const cfg = configs[level];

                pwSegs.forEach((seg, idx) => {
                    if (!seg) return;
                    seg.className = 'h-1 rounded-full transition-colors duration-200 ' + (idx < level && level > 0 ? cfg.color : 'bg-slate-200 dark:bg-slate-700');
                });

                pwLabel.textContent = val.length > 0 && val.length < 8 ? 'Min. 8 karakter' : cfg.text;
                pwLabel.className = 'text-[10.5px] font-semibold whitespace-nowrap ' + (val.length > 0 && val.length < 8 ? 'text-rose-600' : cfg.textColor);
            }

            function updatePasswordMatch() {
                if (!pwConfirmInput || !pwInput || !pwMatchHint) return;
                const cVal = pwConfirmInput.value;
                if (!cVal) {
                    pwMatchHint.textContent = '';
                    pwMatchHint.className = 'hidden text-[11px] font-medium mt-1';
                    return;
                }
                if (cVal === pwInput.value) {
                    pwMatchHint.textContent = 'Kata sandi cocok.';
                    pwMatchHint.className = 'text-[11px] font-semibold text-emerald-600 mt-1';
                } else {
                    pwMatchHint.textContent = 'Konfirmasi kata sandi tidak cocok.';
                    pwMatchHint.className = 'text-[11px] font-semibold text-rose-600 mt-1';
                }
            }

            if (pwInput) {
                pwInput.addEventListener('input', () => {
                    updatePasswordStrength();
                    updatePasswordMatch();
                });
            }
            if (pwConfirmInput) {
                pwConfirmInput.addEventListener('input', updatePasswordMatch);
            }

            // Show warning ONLY when user enters a wrong email domain (after blur or typing '@')
            const emailInput = document.getElementById('emailKampusInput');
            const emailHint = document.getElementById('emailKampusHint');
            function validateEmailLive(strict) {
                if (!emailInput || !emailHint) return;
                const v = emailInput.value.trim().toLowerCase();
                if (!v || (!strict && !v.includes('@'))) {
                    emailHint.textContent = '';
                    emailHint.className = 'hidden text-[11px] font-medium text-rose-600 mt-1';
                    return;
                }
                if (/^[a-z0-9._%+-]+@mhs\.polines\.ac\.id$/.test(v)) {
                    emailHint.textContent = '';
                    emailHint.className = 'hidden text-[11px] font-medium text-rose-600 mt-1';
                } else {
                    emailHint.textContent = 'Gunakan email domain @mhs.polines.ac.id';
                    emailHint.className = 'text-[11px] font-semibold text-rose-600 mt-1';
                }
            }
            if (emailInput && emailHint) {
                emailInput.addEventListener('input', () => validateEmailLive(false));
                emailInput.addEventListener('blur', () => validateEmailLive(true));
            }

            // Show warning ONLY when user enters an invalid WhatsApp number
            const noHpInput = document.getElementById('noHpInput');
            const noHpHint = document.getElementById('noHpHint');
            function validateNoHpLive(strict) {
                if (!noHpInput || !noHpHint) return;
                const cleaned = noHpInput.value.replace(/[\s.\-()]+/g, '').trim();
                if (!cleaned || (!strict && cleaned.length < 4)) {
                    noHpHint.textContent = '';
                    noHpHint.className = 'hidden text-[11px] font-medium text-rose-600 mt-1';
                    return;
                }
                if (/^(?:\+62|62|0)8[1-9][0-9]{7,11}$/.test(cleaned)) {
                    noHpHint.textContent = '';
                    noHpHint.className = 'hidden text-[11px] font-medium text-rose-600 mt-1';
                } else {
                    noHpHint.textContent = 'Format WhatsApp tidak valid (contoh: 081234567890).';
                    noHpHint.className = 'text-[11px] font-semibold text-rose-600 mt-1';
                }
            }
            if (noHpInput && noHpHint) {
                noHpInput.addEventListener('input', () => validateNoHpLive(false));
                noHpInput.addEventListener('blur', () => validateNoHpLive(true));
            }
        });
    </script>
@endsection
