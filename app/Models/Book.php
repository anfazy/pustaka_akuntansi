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