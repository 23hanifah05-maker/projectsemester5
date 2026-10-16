<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ttv extends Model
{
    protected $table = 'ttv';

    protected $primaryKey = 'id_ttv';

    protected $fillable = [
        'id_pendaftaran',
        'tekanan_darah',
        'suhu',
        'heart_rate',
        'respiratory_rate',
        'spo2',
        'alergi',
    ];
}