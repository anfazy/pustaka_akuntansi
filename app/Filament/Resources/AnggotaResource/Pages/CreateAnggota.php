<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnggota extends CreateRecord
{
    protected static string $resource = AnggotaResource::class;

    // Mengubah judul halaman utama menjadi "Tambah Anggota"
    public function getTitle(): string
    {
        return 'Tambah Anggota';
    }

    // Mengubah teks navigasi kecil (breadcrumb) dari "Create" menjadi "Tambah"
    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }

    // Mengubah teks tombol utama menjadi "Simpan"
    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan');
    }

    // Mengubah teks tombol kedua menjadi "Simpan & Buat Lagi"
    protected function getCreateAnotherFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & Buat Lagi');
    }

    // Mengubah teks tombol batal menjadi "Batal"
    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}