<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'password',
        'status',
        'alamat',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'tahun_angkatan' => 'integer',
    ];

    public function loans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Loan::class)->whereIn('status', ['booked', 'dipinjam', 'terlambat']);
    }

    public function favorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteBooks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'favorites', 'anggota_id', 'book_id')->withTimestamps();
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->nama));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        return strtoupper(substr($this->nama, 0, 2));
    }
}