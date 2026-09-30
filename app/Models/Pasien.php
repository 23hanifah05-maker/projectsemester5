<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    // WAJIB DITAMBAHKAN: Mengarahkan Model ke tabel 'pasien', bukan 'pasiens'
    protected $table = 'pasien'; 

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

    // CATATAN TAMBAHAN (Opsional):
    // Jika Primary Key di tabel pasien bukan kolom 'id' (misalnya 'no_rm'), 
    // hilangkan tanda komentar pada baris di bawah ini:
    // protected $primaryKey = 'no_rm';
    // public $incrementing = false;
    // protected $keyType = 'string';

    // Jika tabel pasien TIDAK memiliki kolom 'created_at' dan 'updated_at',
    // hilangkan tanda komentar pada baris di bawah ini:
    // public $timestamps = false;
}