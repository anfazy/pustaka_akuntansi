<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    /**
     * Daftar resmi Program Studi Jurusan Akuntansi Polines (selaras dengan form pendaftaran anggota).
     *
     * @var array<string, string>
     */
    public const PROGRAM_STUDI_OPTIONS = [
        'Komputerisasi Akuntansi (S. Tr)' => 'Komputerisasi Akuntansi (S. Tr)',
        'Perbankan Syariah (S. Tr)' => 'Perbankan Syariah (S. Tr)',
        'Analis Keuangan (S. Tr)' => 'Analis Keuangan (S. Tr)',
        'Akuntansi Manajerial (S. Tr)' => 'Akuntansi Manajerial (S. Tr)',
        'Akuntansi (D3)' => 'Akuntansi (D3)',
        'Keuangan dan Perbankan (D3)' => 'Keuangan dan Perbankan (D3)',
    ];

    protected $fillable = [
        'category_id',
        'collection_type',
        'program_studi',
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

    protected static function booted(): void
    {
        static::saving(function (Book $book): void {
            if ($book->category_id) {
                $category = $book->category ?? Category::find($book->category_id);
                if ($category) {
                    $nama = strtolower($category->nama);
                    $slug = strtolower((string) $category->slug);

                    if (str_contains($nama, 'pkl') || str_contains($nama, 'proposal') || str_contains($slug, 'pkl') || str_contains($slug, 'proposal')) {
                        $book->collection_type = 'proposal_laporan_pkl';
                    } elseif (
                        str_contains($nama, 'skripsi')
                        || str_contains($nama, 'tugas akhir')
                        || preg_match('/\bta\b/', $nama)
                        || str_contains($slug, 'ta-skripsi')
                        || str_contains($slug, 'tugas-akhir')
                    ) {
                        $book->collection_type = 'ta_skripsi';
                    } else {
                        $book->collection_type = 'umum';
                    }
                }
            }

            $book->program_studi = static::normalizeProgramStudi($book->program_studi);
        });

        static::saved(function (Book $book): void {
            if ($book->wasChanged('cover') || $book->wasRecentlyCreated) {
                $book->syncCoverToDatabase();
            }
        });
    }

    /**
     * Persist uploaded cover bytes into the book_covers table so covers survive container deploys.
     */
    public function syncCoverToDatabase(): void
    {
        $cover = trim((string) $this->cover);
        if ($cover === '' || str_starts_with($cover, 'http://') || str_starts_with($cover, 'https://')) {
            return;
        }

        $cleanPath = ltrim($cover, '/');
        $storagePath = str_starts_with($cleanPath, 'storage/')
            ? substr($cleanPath, strlen('storage/'))
            : $cleanPath;

        BookCover::persistFromPublicDisk($storagePath);
    }

    /**
     * Get the public URL for the book cover (supporting uploads, public disk, DB book_covers, public/ files, and external URLs).
     */
    public function getCoverUrlAttribute(): ?string
    {
        $cover = trim((string) $this->cover);
        if ($cover === '') {
            return null;
        }

        if (str_starts_with($cover, 'http://') || str_starts_with($cover, 'https://')) {
            return $cover;
        }

        $cleanPath = ltrim($cover, '/');
        $storagePath = str_starts_with($cleanPath, 'storage/')
            ? substr($cleanPath, strlen('storage/'))
            : $cleanPath;

        if (Storage::disk('uploads')->exists($storagePath)) {
            return asset('uploads/'.$storagePath);
        }

        if (Storage::disk('public')->exists($storagePath) || BookCover::existsForPath($storagePath)) {
            return route('storage.file', ['path' => $storagePath]);
        }

        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        return null;
    }

    /**
     * Normalisasi nama atau singkatan prodi (termasuk kode sheet spreadsheet seperti D4 KA / D3 AK)
     * menjadi nama Program Studi standar.
     */
    public static function normalizeProgramStudi(?string $value): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '' || strtolower($raw) === 'semua') {
            return null;
        }

        if (isset(self::PROGRAM_STUDI_OPTIONS[$raw])) {
            return $raw;
        }

        $normalized = strtolower(preg_replace('/\s+/', ' ', str_replace(['.', '(', ')'], ['', ' ', ' '], $raw)) ?? $raw);
        $compact = preg_replace('/[^a-z0-9]/', '', strtolower($raw)) ?? '';

        return match (true) {
            in_array($compact, ['d4ka', 'ka', 'komputerisasiakuntansi', 'komputerisasiakuntansistr'], true)
                || str_contains($normalized, 'komputerisasi') => 'Komputerisasi Akuntansi (S. Tr)',
            in_array($compact, ['d4ps', 'ps', 'perbankansyariah', 'perbankansyariahstr'], true)
                || str_contains($normalized, 'syariah') => 'Perbankan Syariah (S. Tr)',
            in_array($compact, ['d4ku', 'ku', 'analiskeuangan', 'analiskeuanganstr', 'analisiskeuangan', 'analisiskeuanganstr'], true)
                || str_contains($normalized, 'analis keuangan')
                || str_contains($normalized, 'analisis keuangan') => 'Analis Keuangan (S. Tr)',
            in_array($compact, ['d4am', 'am', 'akuntansimanajerial', 'akuntansimanajerialstr'], true)
                || str_contains($normalized, 'manajerial') => 'Akuntansi Manajerial (S. Tr)',
            in_array($compact, ['d3kp', 'kp', 'keuangandanperbankan', 'keuangandanperbankand3'], true)
                || str_contains($normalized, 'keuangan dan perbankan') => 'Keuangan dan Perbankan (D3)',
            in_array($compact, ['d3ak', 'ak', 'akuntansid3', 'akuntansi'], true)
                || $normalized === 'akuntansi d3' => 'Akuntansi (D3)',
            default => $raw,
        };
    }

    /**
     * Label jenjang ringkas untuk badge UI (D3 atau S. Tr).
     */
    public function getProgramStudiLevelAttribute(): string
    {
        return str_contains((string) $this->program_studi, '(D3)') ? 'D3' : 'S. Tr';
    }

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
        return ($this->collection_type === 'ta_skripsi' || $this->category?->slug === 'ta-skripsi') ? 14 : 7;
    }

    /**
     * Tarif denda keterlambatan per hari.
     * TA / Skripsi: Rp 300 / hari.
     * Kategori lainnya (Non-TA / Umum, Proposal TA/PKL & Laporan PKL): Rp 200 / hari.
     */
    public function getDendaPerHari(): int
    {
        $catSlug = $this->category?->slug;
        $isTa = ($this->collection_type === 'ta_skripsi') || ($catSlug === 'ta-skripsi');

        return $isTa ? 300 : 200;
    }

    public function getDendaPerHariAttribute(): int
    {
        return $this->getDendaPerHari();
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

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavoritedBy(?Anggota $student): bool
    {
        if (! $student) {
            return false;
        }

        return $this->favorites()->where('anggota_id', $student->id)->exists();
    }
}
