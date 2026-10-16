<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assesmen extends Model
{
    protected $table = 'assesmen';

    protected $primaryKey = 'id_assesmen';

    public $timestamps = true;

    protected $fillable = [
        'id_pendaftaran',
        'subjek',
        'objek',
        'assesmen',
        'plan',
        'id_kode10',
        'id_kode9',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(
            Pendaftaran::class,
            'id_pendaftaran',
            'id_pendaftaran'
        );
    }
}
