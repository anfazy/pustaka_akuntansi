<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeBannerWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-banner-widget';

    protected static ?int $sort = 0;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public function getUserName(): string
    {
        return Auth::user()?->name ?? 'Admin Perpustakaan';
    }

    public function getUserEmail(): ?string
    {
        return Auth::user()?->email;
    }

    public function getGreeting(): string
    {
        $hour = (int) now()->timezone(config('app.timezone', 'Asia/Jakarta'))->format('H');

        return match (true) {
            $hour >= 5 && $hour < 11 => 'Selamat Pagi',
            $hour >= 11 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };
    }

    public function getFormattedDate(): string
    {
        return Carbon::now(config('app.timezone', 'Asia/Jakarta'))->locale('id')->isoFormat('dddd, D MMMM Y');
    }

    protected function getViewData(): array
    {
        return [
            'userName' => $this->getUserName(),
            'greeting' => $this->getGreeting(),
            'formattedDate' => $this->getFormattedDate(),
        ];
    }
}
