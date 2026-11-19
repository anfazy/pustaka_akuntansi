<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateLoan extends CreateRecord
{
    protected static string $resource = LoanResource::class;

    protected function beforeCreate(): void
    {
        // 1. Validasi stok buku tersedia
        $book = Book::find($this->data['book_id'] ?? null);

        if (! $book || $book->stok <= 0) {
            Notification::make()
                ->title('Stok Buku Habis')
                ->body("Buku '{$book?->judul}' tidak dapat dipinjam karena stok sedang kosong (0).")
                ->danger()
                ->send();

            $this->halt();
        }

        // 2. Sinkronisasi NIM
        $nim = $this->data['member_nim'] ?? null;
        if (empty($nim) && ! empty($this->data['member_id'])) {
            $nim = Member::find($this->data['member_id'])?->nis_nip;
            $this->data['member_nim'] = $nim;
        }

        // 3. Validasi aturan operasional: maksimal 2 buku per NIM yang sedang aktif dipinjam
        if ($nim) {
            $activeLoansCount = Loan::where('member_nim', $nim)
                ->whereIn('status', ['dipinjam', 'terlambat'])
                ->count();

            if ($activeLoansCount >= 2) {
                Notification::make()
                    ->title('Batas Maksimal Peminjaman')
                    ->body("Mahasiswa dengan NIM {$nim} sudah memiliki {$activeLoansCount} buku yang sedang dipinjam. Batas maksimal peminjaman adalah 2 buku per kartu identitas.")
                    ->danger()
                    ->persistent()
                    ->send();

                $this->halt();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
