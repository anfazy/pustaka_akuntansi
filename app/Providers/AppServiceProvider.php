<?php

namespace App\Providers;

use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function (Login $event) {
            $namaAdmin = $event->user->name ?? 'Admin';

            Notification::make()
                ->title('Selamat Datang, Admin!')
                ->body("Halo {$namaAdmin}, selamat bertugas mengelola Ruang Baca Jurusan Akuntansi Polines.")
                ->success()
                ->icon('heroicon-o-sparkles')
                ->duration(6000)
                ->send();
        });
    }
}