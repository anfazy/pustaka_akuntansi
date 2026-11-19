<?php

namespace App\Models;

use Database\Factories\LoanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Loan extends Model
{
    /** @use HasFactory<LoanFactory> */
    use HasFactory;

    protected $fillable = [
        'book_id',
        'member_id',
        'member_nim',
        'card_type',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'fine_amount',
        'is_lost',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'fine_amount' => 'integer',
        'is_lost' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Otomatis sinkronkan member_nim dari member jika kosong
        static::saving(function (Loan $loan): void {
            if (empty($loan->member_nim) && $loan->member_id) {
                $loan->member_nim = $loan->member?->nis_nip ?? Member::find($loan->member_id)?->nis_nip;
            }
        });

        // 1. Saat transaksi peminjaman baru dibuat: stok berkurang jika status dipinjam/terlambat
        static::created(function (Loan $loan): void {
            if (in_array($loan->status, ['dipinjam', 'terlambat'])) {
                if ($loan->book && $loan->book->stok > 0) {
                    $loan->book->decrement('stok');
                }
            }
        });

        // 2. Saat status peminjaman diubah atau buku diganti
        static::updating(function (Loan $loan): void {
            $originalStatus = $loan->getOriginal('status');
            $newStatus = $loan->status;

            $wasBorrowed = in_array($originalStatus, ['dipinjam', 'terlambat']);
            $nowReturned = $newStatus === 'dikembalikan';
            $nowBorrowed = in_array($newStatus, ['dipinjam', 'terlambat']);

            // Jika status berubah dari dipinjam -> dikembalikan:
            // Stok HANYA ditambah jika buku TIDAK hilang (is_lost = false)
            if ($wasBorrowed && $nowReturned) {
                if (! $loan->is_lost) {
                    $loan->book?->increment('stok');
                }

                if (empty($loan->tanggal_kembali)) {
                    $loan->tanggal_kembali = now()->toDateString();
                }
            }
            // Jika status diubah kembali dari dikembalikan -> dipinjam: stok berkurang lagi (jika sebelumnya tidak hilang)
            elseif (! $wasBorrowed && $nowBorrowed) {
                if (! $loan->getOriginal('is_lost') && $loan->book && $loan->book->stok > 0) {
                    $loan->book->decrement('stok');
                }
            }

            // Jika buku diganti saat transaksi masih berstatus dipinjam
            if ($loan->isDirty('book_id') && $wasBorrowed && $nowBorrowed) {
                $oldBookId = $loan->getOriginal('book_id');
                if ($oldBookId) {
                    Book::find($oldBookId)?->increment('stok');
                }
                if ($loan->book && $loan->book->stok > 0) {
                    $loan->book->decrement('stok');
                }
            }
        });

        // 3. Jika data peminjaman yang masih aktif dihapus: kembalikan stok buku jika tidak hilang
        static::deleted(function (Loan $loan): void {
            if (in_array($loan->status, ['dipinjam', 'terlambat']) && ! $loan->is_lost) {
                $loan->book?->increment('stok');
            }
        });
    }

    /**
     * Hitung tanggal jatuh tempo otomatis menggunakan addWeekdays (mengabaikan Sabtu & Minggu).
     * - TA / Skripsi: 14 hari kerja.
     * - Buku Umum & Proposal TA / PKL / Laporan PKL: 7 hari kerja.
     */
    public function getJatuhTempoAttribute(): ?Carbon
    {
        if (! $this->tanggal_pinjam) {
            return null;
        }

        $duration = $this->book?->getLoanDurationWeekdays() ?? 7;

        return $this->tanggal_pinjam->copy()->addWeekdays($duration);
    }

    /**
     * Hitung jumlah hari kerja keterlambatan setelah tanggal jatuh tempo.
     */
    public function calculateOverdueWeekdays(?Carbon $returnDate = null): int
    {
        $due = $this->jatuh_tempo;
        if (! $due) {
            return 0;
        }

        $end = ($returnDate ?? $this->tanggal_kembali ?? today())->copy()->startOfDay();
        $dueStart = $due->copy()->startOfDay();

        if ($end->lte($dueStart)) {
            return 0;
        }

        $overdueWeekdays = 0;
        $current = $dueStart->copy()->addDay();
        while ($current->lte($end)) {
            if ($current->isWeekday()) {
                $overdueWeekdays++;
            }
            $current->addDay();
        }

        return $overdueWeekdays;
    }

    /**
     * Hitung estimasi denda keterlambatan:
     * - TA / Skripsi: Rp 300 / hari kerja.
     * - Non-TA / Umum & Proposal / Laporan PKL: Rp 200 / hari kerja.
     */
    public function calculateLateFine(?Carbon $returnDate = null): int
    {
        $weekdays = $this->calculateOverdueWeekdays($returnDate);
        if ($weekdays <= 0) {
            return 0;
        }

        $rate = ($this->book?->collection_type === 'ta_skripsi') ? 300 : 200;

        return $weekdays * $rate;
    }

    /**
     * Nominal denda jika buku hilang:
     * - TA/Skripsi & Proposal/Laporan PKL: Rp 200.000.
     * - Buku Umum: 0 (mengganti fisik buku atau seharga buku).
     */
    public function calculateLostFine(): int
    {
        if (in_array($this->book?->collection_type, ['ta_skripsi', 'proposal_laporan_pkl'])) {
            return 200000;
        }

        return 0;
    }

    /**
     * Cek apakah peminjaman saat ini telah melewati jatuh tempo.
     */
    public function isOverdue(): bool
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }

        if ($this->status === 'terlambat') {
            return true;
        }

        $due = $this->jatuh_tempo;

        return $due ? today()->gt($due->startOfDay()) : false;
    }

    /**
     * Get the book that is being loaned.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the member who borrowed the book.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
