<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    // Arahkan ke tabel yang benar
    protected $table = 'poli';

    // Beritahu Laravel bahwa Primary Key-nya adalah 'id_poli'
    protected $primaryKey = 'id_poli';

    // Matikan timestamps karena tabel tidak punya kolom created_at & updated_at
    public $timestamps = false;

    // Daftarkan kolom yang boleh diisi sesuai database
    protected $fillable = [
        'nama_poli',
        'slug',
        'nama_dokter',
    ];
}