<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActiveLoansWidget extends BaseWidget
{
    protected static ?string $heading = 'Sirkulasi Buku Sedang Dipinjam';

    protected static ?int $sort = 4;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Loan::query()
                    ->with(['book', 'anggota', 'member'])
                    ->whereIn('status', ['dipinjam', 'terlambat'])
                    ->latest('tanggal_pinjam')
            )
            ->columns([
                Tables\Columns\TextColumn::make('book.judul')
                    ->label('Judul Buku')
                    ->weight('bold')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('anggota.nama')
                    ->label('Nama Mahasiswa Peminjam')
                    ->getStateUsing(fn (Loan $record): string => $record->anggota?->nama ?? $record->member?->nama ?? $record->member_nim ?? '-')
                    ->description(function (Loan $record): string {
                        $nim = $record->member_nim ?? $record->anggota?->nim_no_anggota ?? $record->member?->nis_nip ?? '-';
                        $kelas = $record->anggota?->kelas ?? $record->member?->kelas_jabatan ?? '-';

                        return "NIM: {$nim} • Kelas: {$kelas}";
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->getStateUsing(fn (Loan $record) => $record->jatuh_tempo ?? $record->tanggal_kembali ?? $record->tanggal_pinjam?->copy()->addDays(7))
                    ->date('d M Y')
                    ->sortable()
                    ->description(function (Loan $record): ?string {
                        $dueDate = $record->jatuh_tempo ?? $record->tanggal_kembali ?? $record->tanggal_pinjam?->copy()->addDays(7);
                        if (! $dueDate) {
                            return null;
                        }

                        $today = today();
                        if ($dueDate->copy()->startOfDay()->lt($today)) {
                            return 'Lewat ' . $dueDate->diffForHumans($today, true);
                        }

                        return 'Sisa ' . $today->diffForHumans($dueDate, true);
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Sirkulasi')
                    ->badge()
                    ->formatStateUsing(function (string $state, Loan $record): string {
                        $isOverdue = $record->isOverdue();

                        if ($isOverdue) {
                            return 'Terlambat / Overdue';
                        }

                        return 'Dipinjam (Aktif)';
                    })
                    ->color(function (string $state, Loan $record): string {
                        return $record->isOverdue() ? 'danger' : 'warning';
                    })
                    ->icon(function (string $state, Loan $record): string {
                        return $record->isOverdue() ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-clock';
                    })
                    ->sortable(),
            ])
            ->emptyStateHeading('Tidak ada buku yang sedang dipinjam')
            ->emptyStateDescription('Semua koleksi saat ini tersedia di rak Ruang Baca Polines.')
            ->emptyStateIcon('heroicon-o-check-badge');
    }
}
