<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'admin.mockup';

    public function getLayout(): string
    {
        return 'filament.layouts.blank';
    }

    public function getWidgets(): array
    {
        return [];
    }
}
