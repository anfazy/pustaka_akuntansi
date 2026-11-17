<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LibraryStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalStok = (int) Book::sum('stok');
        $activeLoansCount = Loan::whereIn('status', ['dipinjam', 'terlambat'])->count();
        $overdueLoansCount = Loan::where('status', 'terlambat')->count();

        return [
            Stat::make('Total Kategori', Category::count() . ' Kategori')
                ->description('Klik untuk kelola kategori')
                ->descriptionIcon('heroicon-m-arrow-top-right-on-square')
                ->chart([2, 4, 3, 5, 6, 8])
                ->color('info')
                ->url(route('filament.admin.resources.categories.index'))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:ring-2 hover:ring-info-500 transition',
                ]),

            Stat::make('Koleksi Judul Buku', Book::count() . ' Judul')
                ->description('Klik untuk lihat katalog buku')
                ->descriptionIcon('heroicon-m-arrow-top-right-on-square')
                ->chart([5, 8, 12, 10, 15, 20])
                ->color('primary')
                ->url(route('filament.admin.resources.books.index'))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:ring-2 hover:ring-primary-500 transition',
                ]),

            Stat::make('Stok Eksemplar Tersedia', $totalStok . ' Buku')
                ->description($totalStok > 0 ? 'Siap dipinjam mahasiswa' : 'Belum ada stok buku')
                ->descriptionIcon($totalStok > 0 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->chart([10, 14, 12, 18, 22, 25])
                ->color($totalStok > 0 ? 'success' : 'warning')
                ->url(route('filament.admin.resources.books.create'))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:ring-2 hover:ring-success-500 transition',
                ]),

            Stat::make('Sedang Dipinjam', $activeLoansCount . ' Eksemplar')
                ->description($overdueLoansCount > 0 ? "Peringatan: {$overdueLoansCount} buku terlambat!" : ($activeLoansCount > 0 ? 'Sirkulasi aktif berjalan' : 'Tidak ada pinjaman aktif'))
                ->descriptionIcon($overdueLoansCount > 0 ? 'heroicon-m-exclamation-triangle' : ($activeLoansCount > 0 ? 'heroicon-m-arrow-path' : 'heroicon-m-check-badge'))
                ->chart([2, 3, 4, 3, 5, max(1, $activeLoansCount)])
                ->color($overdueLoansCount > 0 ? 'danger' : ($activeLoansCount > 0 ? 'warning' : 'success'))
                ->extraAttributes([
                    'class' => 'transition' . ($overdueLoansCount > 0 ? ' ring-2 ring-danger-500/50' : ''),
                ]),
        ];
    }
}
