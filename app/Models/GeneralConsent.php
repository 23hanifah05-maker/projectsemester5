<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralConsent extends Model
{
    protected $table = 'general_consent';
    protected $primaryKey = 'id_general';
    public $timestamps = false;

    protected $fillable = [
        'id_pendaftaran',
        'hak_kewajiban',
        'persetujuan_pelayanan',
        'pelepasan_informasi',
        'pengajuan_keluhan',
        'informasi_rawat_jalan',
        'kewajiban_pembayaran',
        'pernyataan_persetujuan',
        'konfirmasi',
        'anggota_keluarga',
        'ttd_pemberi',
        'nama_pemberi',
        'ttd_pasien',
        'nama_penandatangan',
        'tanggal',
        'jam',
    ];

    protected $casts = [
        'konfirmasi'       => 'boolean',
        'anggota_keluarga' => 'array',
    ];
}