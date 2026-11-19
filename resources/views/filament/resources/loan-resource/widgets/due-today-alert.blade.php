<x-filament-widgets::widget>
    @if ($dueTodayLoans->isNotEmpty() || $overdueLoans->isNotEmpty())
        <div class="space-y-3">
            {{-- Peringatan Jatuh Tempo Hari Ini (Hari-H) --}}
            @if ($dueTodayLoans->isNotEmpty())
                <div class="p-4 rounded-xl bg-warning-50 dark:bg-warning-950/50 border border-warning-300 dark:border-warning-700 text-warning-900 dark:text-warning-100">
                    <div class="flex items-center gap-2 font-bold text-sm text-warning-800 dark:text-warning-300 mb-2">
                        <x-heroicon-o-bell-alert class="w-5 h-5 text-warning-600 dark:text-warning-400 shrink-0" />
                        <span>Peringatan Hari-H: {{ $dueTodayLoans->count() }} Peminjaman Jatuh Tempo Hari Ini ({{ now()->locale('id')->translatedFormat('d F Y') }})</span>
                    </div>
                    <div class="divide-y divide-warning-200 dark:divide-warning-800/60 text-xs">
                        @foreach ($dueTodayLoans as $loan)
                            <div class="py-2 flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <span class="font-bold">{{ $loan->anggota?->nama ?? '-' }}</span>
                                    <span class="text-warning-700 dark:text-warning-300">(NIM: {{ $loan->member_nim ?? '-' }})</span>
                                    &bull; Buku: <span class="font-semibold">{{ $loan->book?->judul ?? '-' }}</span>
                                    <span class="px-1.5 py-0.5 rounded bg-warning-200/70 dark:bg-warning-900 text-[11px] font-mono">{{ $loan->booking_code }}</span>
                                </div>
                                @if (filled($loan->anggota?->no_hp))
                                    <a href="{{ $this->getWaUrl($loan, 'hari_h') }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-warning-600 hover:bg-warning-700 text-white font-semibold transition">
                                        <x-heroicon-m-chat-bubble-left-ellipsis class="w-3.5 h-3.5" />
                                        Ingatkan WA Hari-H
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Peringatan Sudah Lewat Jatuh Tempo (Terlambat) --}}
            @if ($overdueLoans->isNotEmpty())
                <div class="p-4 rounded-xl bg-danger-50 dark:bg-danger-950/50 border border-danger-300 dark:border-danger-700 text-danger-900 dark:text-danger-100">
                    <div class="flex items-center gap-2 font-bold text-sm text-danger-800 dark:text-danger-300 mb-2">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-danger-600 dark:text-danger-400 shrink-0" />
                        <span>Peringatan Keterlambatan: {{ $overdueLoans->count() }} Peminjaman Melewati Jatuh Tempo</span>
                    </div>
                    <div class="divide-y divide-danger-200 dark:divide-danger-800/60 text-xs">
                        @foreach ($overdueLoans as $loan)
                            @php
                                $hariTelat = $loan->calculateOverdueWeekdays(today());
                            @endphp
                            <div class="py-2 flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <span class="font-bold">{{ $loan->anggota?->nama ?? '-' }}</span>
                                    <span class="text-danger-700 dark:text-danger-300">(NIM: {{ $loan->member_nim ?? '-' }})</span>
                                    &bull; Buku: <span class="font-semibold">{{ $loan->book?->judul ?? '-' }}</span>
                                    &bull; <span class="font-bold underline">Telat {{ $hariTelat }} hari kerja</span>
                                </div>
                                @if (filled($loan->anggota?->no_hp))
                                    <a href="{{ $this->getWaUrl($loan, 'terlambat') }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-danger-600 hover:bg-danger-700 text-white font-semibold transition">
                                        <x-heroicon-m-chat-bubble-left-ellipsis class="w-3.5 h-3.5" />
                                        Tagih Pengembalian WA
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</x-filament-widgets::widget>