<?php

namespace App\Models;

use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis_nip',
        'kelas_jabatan',
        'no_hp',
    ];

    /**
     * Get all loan records for this member.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
