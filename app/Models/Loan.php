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
        'booking_code',
        'book_id',
        'anggota_id',
        'member_id',
        'member_nim',
        'card_type',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'is_extended',
        'fine_amount',
        'is_lost',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'is_extended' => 'boolean',
        'fine_amount' => 'integer',
        'is_lost' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Generate satu format & satu urutan kode otomatis (AKT-0001, AKT-0002, dst.)
        // Berlaku sama baik saat booking dari portal User maupun input langsung di Admin
        static::creating(function (Loan $loan): void {
            if (empty($loan->booking_code)) {
                $loan->booking_code = static::generateNextSequentialCode();
            }

            if (empty($loan->tanggal_pinjam)) {
                $loan->tanggal_pinjam = now()->toDateString();
            }
        });

        static::saving(function (Loan $loan): void {
            if ($loan->anggota_id) {
                $anggota = $loan->anggota ?? Anggota::find($loan->anggota_id);
                if ($anggota && empty($loan->member_nim)) {
                    $loan->member_nim = $anggota->nim_no_anggota;
                }
            }
        });

        // 1. Saat transaksi baru dibuat: stok berkurang HANYA jika langsung dipinjam/terlambat (bukan booked/dibatalkan)
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

            // Dari dipinjam -> dikembalikan: tambah stok jika tidak hilang
            if ($wasBorrowed && $nowReturned) {
                if (! $loan->is_lost) {
                    $loan->book?->increment('stok');
                }

                if (empty($loan->tanggal_kembali)) {
                    $loan->tanggal_kembali = now()->toDateString();
                }

                if (empty($loan->fine_amount)) {
                    $loan->fine_amount = $loan->is_lost
                        ? $loan->calculateLostFine()
                        : $loan->calculateLateFine(Carbon::parse($loan->tanggal_kembali));
                }
            }
            // Dari booked / dikembalikan -> dipinjam: kurangi stok
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

        // 3. Jika data peminjaman aktif dihapus: kembalikan stok buku jika tidak hilang
        static::deleted(function (Loan $loan): void {
            if (in_array($loan->status, ['dipinjam', 'terlambat']) && ! $loan->is_lost) {
                $loan->book?->increment('stok');
            }
        });
    }

    /**
     * Menghasilkan nomor urut transaksi berikutnya dengan format AKT-0001, AKT-0002, dst.
     */
    public static function generateNextSequentialCode(): string
    {
        $maxNumber = 0;

        $existingCodes = static::whereNotNull('booking_code')
            ->pluck('booking_code');

        foreach ($existingCodes as $code) {
            if (preg_match('/^AKT-(\d+)$/i', (string) $code, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        // Pastikan nomor urut minimal mengikuti ID terakhir jika sudah ada data sebelumnya
        $lastId = (int) static::max('id');
        $nextNumber = max($maxNumber, $lastId) + 1;

        do {
            $candidate = 'AKT-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (static::where('booking_code', $candidate)->exists());

        return $candidate;
    }

    public function getJaminanAttribute(): ?string
    {
        return $this->card_type;
    }

    public function setJaminanAttribute(?string $value): void
    {
        $this->attributes['card_type'] = $value;
    }

    /**
     * Hitung tanggal jatuh tempo otomatis:
     * - TA / Skripsi: 14 hari kerja
     * - Non-TA / Skripsi, Proposal TA/PKL & Laporan PKL: 7 hari kerja
     * - Ditambah 3 hari kerja jika diperpanjang
     */
    public function getJatuhTempoAttribute(): ?Carbon
    {
        if (! $this->tanggal_pinjam) {
            return null;
        }

        $baseDuration = $this->book?->getLoanDurationWeekdays()
            ?? (($this->book?->collection_type === 'ta_skripsi') ? 14 : 7);
        $extraDuration = $this->is_extended ? 3 : 0;

        return $this->tanggal_pinjam->copy()->addWeekdays($baseDuration + $extraDuration);
    }

    public function calculateOverdueDays(?Carbon $returnDate = null): int
    {
        if (in_array($this->status, ['booked', 'dibatalkan'])) {
            return 0;
        }

        $due = $this->jatuh_tempo;
        if (! $due) {
            return 0;
        }

        $end = ($returnDate ?? $this->tanggal_kembali ?? today())->copy()->startOfDay();
        $dueStart = $due->copy()->startOfDay();

        if ($end->lte($dueStart)) {
            return 0;
        }

        return abs((int) $dueStart->diffInDays($end));
    }

    public function calculateOverdueWeekdays(?Carbon $returnDate = null): int
    {
        if (in_array($this->status, ['booked', 'dibatalkan'])) {
            return 0;
        }

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
     * Denda keterlambatan:
     * - TA / Skripsi: Rp 300 / hari kerja
     * - Non-TA / Skripsi & Proposal/Laporan PKL: Rp 200 / hari kerja
     */
    public function calculateLateFine(?Carbon $returnDate = null): int
    {
        $rate = $this->book?->getDendaPerHari()
            ?? (($this->book?->collection_type === 'ta_skripsi') ? 300 : 200);

        $weekdays = $this->calculateOverdueWeekdays($returnDate);
        if ($weekdays > 0) {
            return $weekdays * $rate;
        }

        if ($this->isOverdue()) {
            $days = $this->calculateOverdueDays($returnDate);
            if ($days > 0) {
                return $days * $rate;
            }
        }

        return 0;
    }

    /**
     * Denda kehilangan:
     * - TA / Skripsi & Proposal/Laporan PKL: Rp 200.000
     * - Non-TA / Skripsi: Mengganti buku yang sama atau seharga buku yang dihilangkan (diinput manual oleh admin)
     */
    public function calculateLostFine(): int
    {
        if (in_array($this->book?->collection_type, ['ta_skripsi', 'proposal_laporan_pkl'])) {
            return 200000;
        }

        return 0;
    }

    /**
     * Cek apakah jatuh tempo peminjaman tepat pada hari ini (Hari-H).
     */
    public function isDueToday(): bool
    {
        if (! in_array($this->status, ['dipinjam', 'terlambat'])) {
            return false;
        }

        $due = $this->jatuh_tempo;

        return $due ? today()->equalTo($due->copy()->startOfDay()) : false;
    }

    public function isOverdue(): bool
    {
        if (in_array($this->status, ['dikembalikan', 'booked', 'dibatalkan'])) {
            return false;
        }

        if ($this->status === 'terlambat') {
            return true;
        }

        $due = $this->jatuh_tempo;

        return $due ? today()->gt($due->copy()->startOfDay()) : false;
    }

    /**
     * Susun URL WhatsApp (wa.me) pengingat jatuh tempo Hari-H atau keterlambatan.
     */
    public function getWhatsAppReminderUrl(): ?string
    {
        $rawPhone = $this->anggota?->no_hp
            ?? $this->anggota?->no_telepon
            ?? $this->anggota?->telepon
            ?? $this->member?->no_telepon
            ?? $this->member?->telepon
            ?? null;

        if (empty($rawPhone)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', (string) $rawPhone);
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        if (strlen($digits) < 10) {
            return null;
        }

        $nama = $this->anggota?->nama ?? $this->member?->nama ?? 'Mahasiswa';
        $kode = $this->booking_code ?? '-';
        $judulBuku = $this->book?->judul ?? 'Buku Perpustakaan';
        $jatuhTempo = $this->jatuh_tempo ? $this->jatuh_tempo->locale('id')->translatedFormat('d F Y') : '-';
        $jamTutup = now()->timezone('Asia/Jakarta')->isFriday() ? '14.00 WIB' : '15.30 WIB';

        if ($this->isOverdue()) {
            $hariTelat = max(1, $this->calculateOverdueWeekdays());
            $denda = number_format($this->calculateLateFine(), 0, ',', '.');
            $statusPesan = "*TERLAMBAT {$hariTelat} hari kerja* (Estimasi denda: *Rp {$denda}*). Mohon segera mengembalikan buku ke Perpustakaan Jurusan Akuntansi agar denda tidak terus bertambah.";
        } elseif ($this->isDueToday()) {
            $statusPesan = "*JATUH TEMPO HARI INI ({$jatuhTempo})*. Mohon segera mengembalikan atau memperpanjang peminjaman buku sebelum jam layanan tutup ({$jamTutup}).";
        } else {
            $statusPesan = "Akan jatuh tempo pada *{$jatuhTempo}*. Mohon mengembalikan buku tepat waktu ke Perpustakaan Jurusan Akuntansi.";
        }

        $pesan = "Halo *{$nama}*,\n\n"
            . "Pengingat Sirkulasi *Perpustakaan Jurusan Akuntansi*:\n"
            . "• Kode Transaksi: *{$kode}*\n"
            . "• Judul Buku: *{$judulBuku}*\n"
            . "• Jatuh Tempo: *{$jatuhTempo}*\n\n"
            . "Status: {$statusPesan}\n"
            . "Jam Layanan: Senin–Kamis (08.30–15.30 WIB) | Jumat (08.30–14.00 WIB)\n"
            . "WA Perpustakaan: 0895-3363-84919\n\n"
            . "Terima kasih.";

        return 'https://wa.me/' . $digits . '?text=' . rawurlencode($pesan);
    }

    /**
     * Batas waktu pengambilan booking maksimal 1x24 jam sejak booking dibuat.
     */
    public function getBookingExpiresAtAttribute(): ?Carbon
    {
        if ($this->status !== 'booked' || ! $this->created_at) {
            return null;
        }

        return $this->created_at->copy()->addHours(24);
    }

    /**
     * Cek apakah booking sudah hangus melewati batas 1x24 jam.
     */
    public function isBookingExpired(): bool
    {
        if ($this->status !== 'booked' || ! $this->created_at) {
            return false;
        }

        return now()->gt($this->created_at->copy()->addHours(24));
    }

    public function getTransactionCodeLabelAttribute(): string
    {
        return $this->status === 'booked' ? 'Kode Booking' : 'Kode Pinjam';
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}