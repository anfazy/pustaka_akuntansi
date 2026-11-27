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
        'cover_data',
    ];

    protected $hidden = [
        'cover_data',
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

            if ($book->isDirty('cover')) {
                $book->syncCoverDataFromDisk();
            }
        });
    }

    /**
     * Persist uploaded public-disk cover bytes into the database so covers survive ephemeral container deploys.
     */
    public function syncCoverDataFromDisk(): void
    {
        $cover = trim((string) $this->cover);
        if ($cover === '' || str_starts_with($cover, 'http://') || str_starts_with($cover, 'https://')) {
            $this->cover_data = null;

            return;
        }

        $cleanPath = ltrim($cover, '/');
        $storagePath = str_starts_with($cleanPath, 'storage/')
            ? substr($cleanPath, strlen('storage/'))
            : $cleanPath;

        if (! Storage::disk('public')->exists($storagePath)) {
            return;
        }

        $binary = Storage::disk('public')->get($storagePath);
        if ($binary === null || $binary === '') {
            return;
        }

        $mime = Storage::disk('public')->mimeType($storagePath) ?: 'image/jpeg';

        // Compress oversized uploads (> 350 KB) into crisp 720px JPEG thumbnails when GD is available
        if (strlen($binary) > 350 * 1024 && function_exists('imagecreatefromstring')) {
            $img = @imagecreatefromstring($binary);
            if ($img !== false) {
                $origW = imagesx($img);
                $origH = imagesy($img);
                $targetW = $origW > 720 ? 720 : $origW;
                $targetH = (int) round($origH * ($targetW / max(1, $origW)));
                $canvas = imagecreatetruecolor($targetW, max(1, $targetH));
                if ($canvas !== false) {
                    $white = imagecolorallocate($canvas, 255, 255, 255);
                    imagefill($canvas, 0, 0, $white);
                    imagecopyresampled($canvas, $img, 0, 0, 0, 0, $targetW, max(1, $targetH), $origW, $origH);
                    ob_start();
                    imagejpeg($canvas, null, 84);
                    $compressed = ob_get_clean();
                    imagedestroy($canvas);
                    if (is_string($compressed) && $compressed !== '') {
                        $binary = $compressed;
                        $mime = 'image/jpeg';
                    }
                }
                imagedestroy($img);
            }
        }

        $this->cover_data = 'data:'.$mime.';base64,'.base64_encode($binary);
    }

    /**
     * Decode stored cover_data URI back into MIME type and raw binary bytes.
     *
     * @return array{mime: string, binary: string}|null
     */
    public function decodeCoverBinary(): ?array
    {
        if (empty($this->cover_data)) {
            return null;
        }

        if (! preg_match('#^data:([^;]+);base64,(.+)$#s', $this->cover_data, $matches)) {
            return null;
        }

        $binary = base64_decode($matches[2], true);
        if ($binary === false) {
            return null;
        }

        return [
            'mime' => $matches[1],
            'binary' => $binary,
        ];
    }

    /**
     * Restore a missing public-disk cover file from PostgreSQL cover_data cache.
     *
     * @return array{mime: string, binary: string}|null
     */
    public static function restoreCoverFromDatabase(string $cleanPath): ?array
    {
        $book = static::where('cover', $cleanPath)
            ->orWhere('cover', '/'.$cleanPath)
            ->first(['id', 'cover', 'cover_data']);

        $decoded = $book?->decodeCoverBinary();
        if (! $decoded) {
            return null;
        }

        Storage::disk('public')->put($cleanPath, $decoded['binary']);

        return $decoded;
    }

    /**
     * Get the public URL for the book cover (supporting Storage public disk, DB cover_data, public/ files, and external URLs).
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

        if (Storage::disk('public')->exists($storagePath) || ! empty($this->cover_data)) {
            return route('storage.file', ['path' => $storagePath]);
        }

        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        return null;
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
