<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
    public $timestamps = false;
    protected $guarded = [];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rm', 'no_rm');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli', 'id_poli');
    }
}