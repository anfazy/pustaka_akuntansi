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

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Loan::query()
                    ->with(['book', 'member'])
                    ->whereIn('status', ['dipinjam', 'terlambat'])
                    ->latest('tanggal_pinjam')
            )
            ->columns([
                Tables\Columns\TextColumn::make('book.judul')
                    ->label('Judul Buku')
                    ->weight('bold')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('member.nama')
                    ->label('Nama Mahasiswa Peminjam')
                    ->description(fn (Loan $record): string => trim(($record->member?->nis_nip ?? '-') . ' • ' . ($record->member?->kelas_jabatan ?? '-')))
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Jatuh Tempo (SOP 7 Hari)')
                    ->getStateUsing(fn (Loan $record) => $record->tanggal_kembali ?? $record->tanggal_pinjam?->copy()->addDays(7))
                    ->date('d M Y')
                    ->sortable()
                    ->description(function (Loan $record): ?string {
                        $dueDate = $record->tanggal_kembali ?? $record->tanggal_pinjam?->copy()->addDays(7);
                        if (! $dueDate) {
                            return null;
                        }

                        $today = today();
                        if ($dueDate->startOfDay()->lt($today)) {
                            return 'Lewat ' . $dueDate->diffForHumans($today, true);
                        }

                        return 'Sisa ' . $today->diffForHumans($dueDate, true);
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Sirkulasi')
                    ->badge()
                    ->formatStateUsing(function (string $state, Loan $record): string {
                        $dueDate = $record->tanggal_kembali ?? $record->tanggal_pinjam?->copy()->addDays(7);
                        $isOverdue = $state === 'terlambat' || ($dueDate && $dueDate->startOfDay()->lt(today()));

                        if ($isOverdue) {
                            return 'Terlambat / Overdue';
                        }

                        return 'Dipinjam (Aktif)';
                    })
                    ->color(function (string $state, Loan $record): string {
                        $dueDate = $record->tanggal_kembali ?? $record->tanggal_pinjam?->copy()->addDays(7);
                        $isOverdue = $state === 'terlambat' || ($dueDate && $dueDate->startOfDay()->lt(today()));

                        return $isOverdue ? 'danger' : 'warning';
                    })
                    ->icon(function (string $state, Loan $record): string {
                        $dueDate = $record->tanggal_kembali ?? $record->tanggal_pinjam?->copy()->addDays(7);
                        $isOverdue = $state === 'terlambat' || ($dueDate && $dueDate->startOfDay()->lt(today()));

                        return $isOverdue ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-clock';
                    })
                    ->sortable(),
            ])
            ->emptyStateHeading('Tidak ada buku yang sedang dipinjam')
            ->emptyStateDescription('Semua koleksi saat ini tersedia di rak Ruang Baca Polines.')
            ->emptyStateIcon('heroicon-o-check-badge');
    }
}
