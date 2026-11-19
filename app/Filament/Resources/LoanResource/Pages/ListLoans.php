<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use App\Filament\Resources\LoanResource\Widgets\LoanStatsOverview;
use App\Models\Loan;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListLoans extends ListRecords
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            LoanStatsOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportLaporan')
                ->label('Export Laporan Sirkulasi (CSV)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function (): StreamedResponse {
                    $fileName = 'laporan-sirkulasi-pustaka-akuntansi-' . now()->format('Y-m-d_His') . '.csv';

                    $loans = $this->getFilteredTableQuery()
                        ->with(['anggota', 'book'])
                        ->latest()
                        ->get();

                    return response()->streamDownload(function () use ($loans) {
                        $handle = fopen('php://output', 'w');

                        // BOM UTF-8 agar rapi saat dibuka di Microsoft Excel
                        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

                        fputcsv($handle, [
                            'No',
                            'Kode Booking',
                            'Nama Anggota',
                            'NIM / No. Anggota',
                            'Kelas',
                            'No. HP',
                            'Kartu Jaminan',
                            'Kode Buku',
                            'Judul Buku',
                            'Jenis Koleksi',
                            'Tanggal Pinjam',
                            'Jatuh Tempo',
                            'Tanggal Kembali',
                            'Status',
                            'Buku Hilang',
                            'Nominal Denda (Rp)',
                        ], ';');

                        foreach ($loans as $index => $loan) {
                            /** @var Loan $loan */
                            $statusLabel = match (true) {
                                $loan->is_lost => 'Buku Hilang',
                                $loan->status === 'booked' => 'Belum Diambil (Booking)',
                                $loan->status === 'dibatalkan' => 'Dibatalkan',
                                $loan->status === 'dikembalikan' => 'Dikembalikan',
                                $loan->isOverdue() => 'Terlambat',
                                default => 'Sudah Diambil (Dipinjam)',
                            };

                            fputcsv($handle, [
                                $index + 1,
                                $loan->booking_code ?? '-',
                                $loan->anggota?->nama ?? '-',
                                $loan->member_nim ?? '-',
                                $loan->anggota?->kelas ?? '-',
                                $loan->anggota?->no_hp ?? '-',
                                $loan->card_type ?? '-',
                                $loan->book?->book_code ?? '-',
                                $loan->book?->judul ?? '-',
                                $loan->book?->collection_type_label ?? '-',
                                $loan->tanggal_pinjam?->format('d/m/Y') ?? '-',
                                in_array($loan->status, ['booked', 'dibatalkan']) ? '-' : ($loan->jatuh_tempo?->format('d/m/Y') ?? '-'),
                                $loan->tanggal_kembali?->format('d/m/Y') ?? '-',
                                $statusLabel,
                                $loan->is_lost ? 'Ya' : 'Tidak',
                                $loan->fine_amount ?? 0,
                            ], ';');
                        }

                        fclose($handle);
                    }, $fileName, [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                    ]);
                }),

            Actions\CreateAction::make()
                ->label('Catat Peminjaman Baru')
                ->icon('heroicon-m-plus'),
        ];
    }
}