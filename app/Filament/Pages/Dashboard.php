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
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Beranda Dashboard';

    protected static ?string $title = 'Dashboard Perpustakaan Jurusan Akuntansi';

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 2,
        ];
    }

    public function getWidgets(): array
    {
        return [
            WelcomeBannerWidget::class,
            LibraryStatsWidget::class,
            QuickActionsWidget::class,
            BooksByCategoryChart::class,
            ActiveLoansWidget::class,
            LatestBooksWidget::class,
        ];
    }
}