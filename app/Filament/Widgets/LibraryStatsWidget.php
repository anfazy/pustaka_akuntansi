<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use Filament\Widgets\Widget;

class LibraryStatsWidget extends Widget
{
    protected static string $view = 'filament.widgets.library-stats-widget';

    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'kategoriCount' => Category::count(),
            'judulCount' => Book::count(),
            'totalStok' => (int) Book::sum('stok'),
            'activeLoansCount' => Loan::whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'overdueLoansCount' => Loan::where('status', 'terlambat')->count(),
        ];
    }
}
