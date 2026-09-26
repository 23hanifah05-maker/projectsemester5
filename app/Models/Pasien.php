<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_rm',
        'nama_pasien',
        'nik',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];
}