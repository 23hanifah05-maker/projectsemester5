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

        // SUBJECTIVE
        'keluhan_utama',
        'riwayat_penyakit_sekarang',
        'riwayat_penyakit_dahulu',
        'riwayat_penyakit_keluarga',
        'riwayat_pengobatan',
        'riwayat_sosial',
        'riwayat_operasi',
        'riwayat_alergi',
        'riwayat_imunisasi',

        // OBJECTIVE
        'pemeriksaan_fisik_lainnya',

        // ASSESSMENT
        'dx_utama',
        'dx_sekunder',

        // PLAN
        'tindakan_medis',
        'konseling_edukasi',
        'rencana_tindak_lanjut',
        'diet',
        'resep_obat',

        // KODE ICD
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
