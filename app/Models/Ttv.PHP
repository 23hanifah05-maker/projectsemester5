<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ttv extends Model
{
    protected $table = 'ttv';

    protected $primaryKey = 'id_ttv';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_pendaftaran',
        'tekanan_darah',
        'suhu',
        'heart_rate',
        'respiratory_rate',
        'spo2',
        'alergi',
    ];

    protected $casts = [
        'suhu' => 'decimal:1',
        'heart_rate' => 'integer',
        'respiratory_rate' => 'integer',
        'spo2' => 'integer',
    ];
}