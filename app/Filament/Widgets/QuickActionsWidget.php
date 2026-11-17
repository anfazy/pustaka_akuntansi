<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Category;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions-widget';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    public function isServiceOpen(): bool
    {
        $now = now()->timezone('Asia/Jakarta');

        // Hari Senin (1) sampai Jumat (5)
        if (! $now->isWeekday()) {
            return false;
        }

        // Pukul 08.00 - 16.00 WIB
        $currentMinutes = ($now->hour * 60) + $now->minute;

        return $currentMinutes >= (8 * 60) && $currentMinutes < (16 * 60);
    }

    public function getQuickStats(): array
    {
        return [
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'low_stock_books' => Book::where('stok', '<=', 1)->count(),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'quickStats' => $this->getQuickStats(),
            'isServiceOpen' => $this->isServiceOpen(),
        ];
    }
}
