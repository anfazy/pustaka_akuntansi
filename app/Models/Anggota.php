<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    protected $fillable = [
        'nim_no_anggota',
        'nama',
        'kelas',
        'program_studi',
        'tahun_angkatan',
        'no_hp',
        'email',
        'email_verified_at',
        'password',
        'status',
        'alamat',
        'kota',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'tahun_angkatan' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans(): HasMany
    {
        return $this->hasMany(Loan::class)->whereIn('status', ['booked', 'dipinjam', 'terlambat']);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'favorites', 'anggota_id', 'book_id')->withTimestamps();
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->nama));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1));
        }

        return strtoupper(substr($this->nama, 0, 2));
    }
}
