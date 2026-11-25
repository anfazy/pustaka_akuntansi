<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class AdminLogin extends BaseLogin
{
    public function getTitle(): string|Htmlable
    {
        return 'Masuk Admin';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Masuk Admin';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Masuk dengan akun pustakawan untuk mengelola katalog & sirkulasi.';
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->label('Alamat Email')
            ->placeholder('admin@perpus.test');
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->label('Kata Sandi')
            ->placeholder('Masukkan kata sandi');
    }

    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()
            ->label('Ingat saya');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('Masuk');
    }
}
