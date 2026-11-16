<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LibraryStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Kategori', Category::count())
                ->description('Jenis kategori buku terdaftar')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'),

            Stat::make('Total Judul Buku', Book::count())
                ->description('Koleksi judul di katalog')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Total Stok Tersedia', Book::sum('stock'))
                ->description('Eksemplar siap dipinjam')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}