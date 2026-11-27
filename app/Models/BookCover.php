<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BookCover extends Model
{
    public const CACHE_KEY = 'book_cover_paths';

    protected $fillable = [
        'path',
        'mime_type',
        'data',
    ];

    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function (): void {
            Cache::forget(self::CACHE_KEY);
        });
    }

    /**
     * Persist an uploaded cover file from either the uploads or public disk into the database.
     */
    public static function persistFromPublicDisk(string $storagePath): void
    {
        $sourceDisk = null;
        if (Storage::disk('uploads')->exists($storagePath)) {
            $sourceDisk = Storage::disk('uploads');
        } elseif (Storage::disk('public')->exists($storagePath)) {
            $sourceDisk = Storage::disk('public');
        }

        if (! $sourceDisk) {
            return;
        }

        $binary = $sourceDisk->get($storagePath);
        if ($binary === null || $binary === '') {
            return;
        }

        $mime = $sourceDisk->mimeType($storagePath) ?: 'image/jpeg';

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

        static::updateOrCreate(
            ['path' => $storagePath],
            [
                'mime_type' => $mime,
                'data' => base64_encode($binary),
            ]
        );
    }

    /**
     * Check whether a stored cover exists in the database (cached path lookup).
     */
    public static function existsForPath(string $storagePath): bool
    {
        $paths = Cache::remember(self::CACHE_KEY, 3600, function (): array {
            return static::query()->pluck('path')->flip()->all();
        });

        return isset($paths[$storagePath]);
    }

    /**
     * Restore a missing public-disk cover file from the database.
     *
     * @return array{mime: string, binary: string}|null
     */
    public static function restoreToPublicDisk(string $cleanPath): ?array
    {
        $record = static::where('path', $cleanPath)->first();
        if (! $record) {
            return null;
        }

        $binary = base64_decode($record->data, true);
        if ($binary === false) {
            return null;
        }

        Storage::disk('public')->put($cleanPath, $binary);

        return [
            'mime' => $record->mime_type ?: 'image/jpeg',
            'binary' => $binary,
        ];
    }
}
