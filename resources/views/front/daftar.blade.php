@extends('front.auth-layout')

@section('title', 'Daftar Anggota Baru — Perpustakaan Jurusan Akuntansi')

@section('content')
    <div class="glass-card w-full max-w-[720px] rounded-[32px] p-8 sm:p-11 shadow-2xl animate-rise">
        <!-- Back to Beranda Link & Login Shortcut -->
        <div class="flex items-center justify-between gap-3 mb-6">
            <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-emerald-800 dark:hover:text-emerald-400 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Beranda</span>
            </a>

            <a href="{{ route('masuk') }}" class="inline-flex items-center gap-1.5 rounded-full liquid-badge-emerald px-3 py-1 text-xs font-semibold hover:opacity-90 transition">
                <span>Sudah punya akun? Masuk</span>
            </a>
        </div>

        <!-- Brand Header with Official HMJ Logo -->
        <div class="flex items-center gap-3.5 mb-6">
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-white p-1 shadow-md shadow-emerald-950/15 border border-white/80 dark:border-white/20 overflow-hidden">
                <img src="{{ asset('images/logo-hmj-akuntansi.jpg') }}" alt="Logo Perpustakaan Jurusan Akuntansi" class="h-full w-full object-contain">
            </div>
            <div>
                <span class="block text-[17px] font-bold leading-tight tracking-tight text-slate-900">Perpustakaan Jurusan Akuntansi</span>
                <span class="block text-xs text-emerald-800 dark:text-emerald-400 font-medium">Ruang Baca Jurusan Polines</span>
            </div>
        </div>

        <h1 class="text-[28px] sm:text-[32px] font-bold tracking-tight text-slate-900 mb-6">Daftar Anggota Baru</h1>

        <!-- REGISTRATION FORM -->
        <form action="{{ route('daftar.proses') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                <!-- Nama Lengkap (Full width for long names & symmetric 2-col rows below) -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-2xl px-4 py-3">
                        <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap" class="w-full bg-transparent text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('nama') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- NIM -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">NIM <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-2xl px-4 py-3">
                        <input type="text" name="nim_no_anggota" value="{{ old('nim_no_anggota') }}" required placeholder="Contoh: 4.41.24.0.12" class="w-full bg-transparent text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('nim_no_anggota') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Kelas <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-2xl px-4 py-3">
                        <input type="text" name="kelas" value="{{ old('kelas') }}" required placeholder="Contoh: AKT-3A" class="w-full bg-transparent text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('kelas') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Program Studi (Apple Liquid Glass Searchable & Selectable Combobox) -->
                <div class="relative" id="prodiCombobox">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Program Studi <span class="text-rose-500">*</span></label>
                    </div>
                    <div class="glass-field relative flex items-center rounded-2xl px-4 py-3">
                        <input type="text"
                               name="program_studi"
                               id="prodiInput"
                               value="{{ old('program_studi') }}"
                               required
                               autocomplete="off"
                               placeholder="Pilih atau ketik prodi..."
                               class="w-full bg-transparent text-[14px] font-medium text-slate-800 outline-none pr-8 placeholder:text-slate-400">
                        <button type="button"
                                id="prodiToggleBtn"
                                class="absolute right-3 grid h-7 w-7 place-items-center rounded-full text-slate-400 hover:text-emerald-700 transition"
                                title="Buka Pilihan Prodi">
                            <svg id="prodiChevron" class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>
                    @error('program_studi') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror

                    <!-- Floating Apple Liquid Glass Dropdown Options -->
                    <div id="prodiDropdown" class="hidden absolute left-0 right-0 z-50 mt-2 max-h-60 overflow-y-auto rounded-2xl border border-white/80 bg-white/90 p-2 shadow-2xl backdrop-blur-xl transition-all">
                        <div class="px-2 py-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Pilihan Jurusan Akuntansi Polines</div>
                        <div id="prodiOptionsList" class="space-y-1 mt-1">
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-left text-[13px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Komputerisasi Akuntansi (S. Tr)">
                                <span>Komputerisasi Akuntansi (S. Tr)</span>
                                <span class="rounded-full bg-emerald-100/80 px-2 py-0.5 text-[10px] font-bold text-emerald-800">S. Tr</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-left text-[13px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Perbankan Syariah (S. Tr)">
                                <span>Perbankan Syariah (S. Tr)</span>
                                <span class="rounded-full bg-emerald-100/80 px-2 py-0.5 text-[10px] font-bold text-emerald-800">S. Tr</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-left text-[13px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Analis Keuangan (S. Tr)">
                                <span>Analis Keuangan (S. Tr)</span>
                                <span class="rounded-full bg-emerald-100/80 px-2 py-0.5 text-[10px] font-bold text-emerald-800">S. Tr</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-left text-[13px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Akuntansi Manajerial (S. Tr)">
                                <span>Akuntansi Manajerial (S. Tr)</span>
                                <span class="rounded-full bg-emerald-100/80 px-2 py-0.5 text-[10px] font-bold text-emerald-800">S. Tr</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-left text-[13px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Akuntansi (D3)">
                                <span>Akuntansi (D3)</span>
                                <span class="rounded-full bg-amber-100/80 px-2 py-0.5 text-[10px] font-bold text-amber-800">D3</span>
                            </button>
                            <button type="button" class="prodi-item group flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-left text-[13px] font-medium text-slate-700 hover:bg-emerald-500/10 hover:text-emerald-900 transition-colors" data-label="Keuangan dan Perbankan (D3)">
                                <span>Keuangan dan Perbankan (D3)</span>
                                <span class="rounded-full bg-amber-100/80 px-2 py-0.5 text-[10px] font-bold text-amber-800">D3</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Angkatan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Angkatan <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-2xl px-4 py-3">
                        <input type="number" name="tahun_angkatan" value="{{ old('tahun_angkatan') }}" min="2000" max="{{ date('Y') + 1 }}" required placeholder="Contoh: 2024" class="w-full bg-transparent text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('tahun_angkatan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nomor WhatsApp -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-2xl px-4 py-3">
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="Contoh: 081234567890" class="w-full bg-transparent text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('no_hp') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email Kampus -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Email Kampus <span class="text-rose-500">*</span></label>
                    <div class="glass-field rounded-2xl px-4 py-3">
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@mahasiswa.polines.ac.id" class="w-full bg-transparent text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    </div>
                    @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="registerPasswordInput" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Buat Kata Sandi <span class="text-rose-500">*</span></label>
                    <div class="glass-field relative flex items-center rounded-2xl px-4 py-3">
                        <input type="password" name="password" id="registerPasswordInput" required placeholder="Minimal 6 karakter" class="w-full bg-transparent pr-9 text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                        <button type="button" onclick="togglePasswordVisibility('registerPasswordInput', this)" aria-label="Tampilkan atau sembunyikan kata sandi" class="absolute right-3 grid h-7 w-7 place-items-center rounded-full text-slate-400 hover:text-emerald-700 transition">
                            <svg class="eye-open h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg class="eye-closed hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div>
                    <label for="registerPasswordConfirmInput" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                    <div class="glass-field relative flex items-center rounded-2xl px-4 py-3">
                        <input type="password" name="password_confirmation" id="registerPasswordConfirmInput" required placeholder="Ulangi kata sandi" class="w-full bg-transparent pr-9 text-[14px] font-medium text-slate-800 outline-none placeholder:text-slate-400">
                        <button type="button" onclick="togglePasswordVisibility('registerPasswordConfirmInput', this)" aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi" class="absolute right-3 grid h-7 w-7 place-items-center rounded-full text-slate-400 hover:text-emerald-700 transition">
                            <svg class="eye-open h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg class="eye-closed hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button (Apple Liquid Amber / Honey Gold) -->
            <div class="mt-7">
                <button type="submit" class="btn-amber-gold w-full h-12 rounded-full text-[15px] font-semibold shadow-md">
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
        });
    </script>
@endsection
