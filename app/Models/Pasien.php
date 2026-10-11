<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $primaryKey = 'no_rm';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'no_rm',
        'nik',
        'nama_pasien',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'pendidikan',
        'pekerjaan',
        'no_hp',
        'suku',
        'bangsa',
        'bahasa',
        'alamat',
        'kelurahan',
        'kabupaten',
        'kecamatan',
        'kode_pos',
        'pj_nama',
        'pj_hubungan',
        'pj_jenis_kelamin',
        'pj_no_hp',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];
}
