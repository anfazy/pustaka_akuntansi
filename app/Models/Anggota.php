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
        'alamat',
    ];
}