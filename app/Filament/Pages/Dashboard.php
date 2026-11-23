<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActiveLoansWidget;
use App\Filament\Widgets\BooksByCategoryChart;
use App\Filament\Widgets\LatestBooksWidget;
use App\Filament\Widgets\LibraryStatsWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\WelcomeBannerWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Perpustakaan';

    public function getWidgets(): array
    {
        return [
            WelcomeBannerWidget::class,
            LibraryStatsWidget::class,
            QuickActionsWidget::class,
            ActiveLoansWidget::class,
            LatestBooksWidget::class,
            BooksByCategoryChart::class,
        ];
    }
}
