<?php

namespace App\Filament\Resources\LoanResource\Widgets;

use App\Models\Loan;
use Filament\Widgets\Widget;

class DueTodayAlertWidget extends Widget
{
    protected static string $view = 'filament.resources.loan-resource.widgets.due-today-alert';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $activeLoans = Loan::with(['anggota', 'book'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->where('is_lost', false)
            ->get();

        $dueTodayLoans = $activeLoans->filter(function (Loan $loan): bool {
            return $loan->jatuh_tempo && $loan->jatuh_tempo->copy()->startOfDay()->isToday();
        });

        $overdueLoans = $activeLoans->filter(function (Loan $loan): bool {
            $due = $loan->jatuh_tempo?->copy()->startOfDay();

            return $due && today()->gt($due) && $loan->calculateOverdueWeekdays(today()) > 0;
        });

        return [
            'dueTodayLoans' => $dueTodayLoans,
            'overdueLoans' => $overdueLoans,
        ];
    }

    public function getWaUrl(Loan $loan, string $tipe): string
    {
        $noHp = $loan->anggota?->no_hp ?? '';
        $phone = preg_replace('/[^0-9]/', '', $noHp);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $nama = $loan->anggota?->nama ?? $loan->member_nim ?? 'Mahasiswa';
        $kodeBooking = $loan->booking_code ?? '-';
        $judulBuku = $loan->book?->judul ?? 'Buku Perpustakaan';
        $jatuhTempoTeks = $loan->jatuh_tempo
            ? $loan->jatuh_tempo->copy()->locale('id')->translatedFormat('l, d F Y')
            : '-';

        if ($tipe === 'terlambat') {
            $hariTelat = $loan->calculateOverdueWeekdays(today());
            $estimasiDenda = number_format($loan->calculateLateFine(today()), 0, ',', '.');
            $statusBaris = "*TERLAMBAT ({$hariTelat} hari kerja)*";
            $catatan = "Estimasi denda sementara: *Rp {$estimasiDenda}*.\nMohon segera mengembalikan buku ke perpustakaan agar denda tidak terus bertambah.";
        } else {
            $statusBaris = "*JATUH TEMPO HARI INI*";
            $catatan = "Batas pengembalian berakhir *hari ini* sebelum jam tutup layanan. Mohon segera dikembalikan agar tidak terkena denda keterlambatan.";
        }

        $pesan = "Halo *{$nama}*,\n\n"
            . "Pengingat Peminjaman Buku — *Perpustakaan Pustaka Akuntansi*\n\n"
            . "• Kode Booking : *{$kodeBooking}*\n"
            . "• Judul Buku : *{$judulBuku}*\n"
            . "• Jatuh Tempo : *{$jatuhTempoTeks}*\n"
            . "• Status : {$statusBaris}\n\n"
            . "{$catatan}\n\n"
            . "Terima kasih.";

        return 'https://wa.me/' . $phone . '?text=' . urlencode($pesan);
    }
}