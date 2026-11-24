<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Category;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions-widget';

    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    public function isServiceOpen(): bool
    {
        $now = now()->timezone('Asia/Jakarta');

        // Sabtu (6) dan Minggu (0) tutup
        if (! $now->isWeekday()) {
            return false;
        }

        $currentMinutes = ($now->hour * 60) + $now->minute;
        $openMinutes = (8 * 60) + 30; // 08.30 WIB

        // Jumat (5): 08.30 - 14.00 WIB
        if ($now->isFriday()) {
            $closeMinutes = 14 * 60; // 14.00 WIB

            return $currentMinutes >= $openMinutes && $currentMinutes < $closeMinutes;
        }

        // Senin (1) - Kamis (4): 08.30 - 15.30 WIB
        $closeMinutes = (15 * 60) + 30; // 15.30 WIB

        return $currentMinutes >= $openMinutes && $currentMinutes < $closeMinutes;
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