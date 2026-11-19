<?php

namespace App\Filament\Resources\LoanResource\Widgets;

use App\Models\Loan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LoanStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $bookedCount = Loan::where('status', 'booked')->count();

        $activeCount = Loan::whereIn('status', ['dipinjam', 'terlambat'])->count();

        $overdueThreshold = today()->subDays(7)->toDateString();
        $overdueCount = Loan::where('status', 'terlambat')
            ->orWhere(function ($query) use ($overdueThreshold) {
                $query->where('status', 'dipinjam')
                    ->where('tanggal_pinjam', '<', $overdueThreshold);
            })
            ->count();

        $totalFine = Loan::where('status', 'dikembalikan')->sum('fine_amount');

        return [
            Stat::make('Booking Menunggu Diambil', $bookedCount . ' Transaksi')
                ->description('Kode booking AKT-XXXX aktif')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info'),

            Stat::make('Sedang Dipinjam', $activeCount . ' Buku')
                ->description('Buku yang berada di mahasiswa')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('warning'),

            Stat::make('Melewati Jatuh Tempo', $overdueCount . ' Transaksi')
                ->description('Perlu penagihan pengembalian')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overdueCount > 0 ? 'danger' : 'success'),

            Stat::make('Total Denda Terkumpul', 'Rp ' . number_format($totalFine, 0, ',', '.'))
                ->description('Dari transaksi selesai & buku hilang')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}