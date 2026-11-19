<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'collection_type',
        'book_code',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'sinopsis',
        'stok',
        'cover',
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'stok' => 'integer',
    ];

    /**
     * Get the human-readable label for the collection type.
     */
    public function getCollectionTypeLabelAttribute(): string
    {
        return match ($this->collection_type) {
            'ta_skripsi' => 'Tugas Akhir / Skripsi',
            'proposal_laporan_pkl' => 'Proposal TA/PKL & Laporan PKL',
            default => 'Buku Umum',
        };
    }

    /**
     * Standard loan duration in weekdays.
     * TA / Skripsi: 14 hari kerja.
     * Non-TA / Umum & Proposal / Laporan PKL: 7 hari kerja.
     */
    public function getLoanDurationWeekdays(): int
    {
        return $this->collection_type === 'ta_skripsi' ? 14 : 7;
    }

    /**
     * Get the category that this book belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all loan records for this book.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get all active loan records (dipinjam or terlambat) for this book.
     */
    public function activeLoans(): HasMany
    {
        return $this->hasMany(Loan::class)->whereIn('status', ['dipinjam', 'terlambat']);
    }
}