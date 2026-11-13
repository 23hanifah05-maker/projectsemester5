<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assesmen', function (Blueprint $table) {
            // SUBJECTIVE
            $table->text('keluhan_utama')->nullable();
            $table->text('riwayat_penyakit_sekarang')->nullable();
            $table->text('riwayat_penyakit_dahulu')->nullable();
            $table->text('riwayat_penyakit_keluarga')->nullable();
            $table->text('riwayat_pengobatan')->nullable();
            $table->text('riwayat_sosial')->nullable();
            $table->text('riwayat_operasi')->nullable();
            $table->text('riwayat_alergi')->nullable();
            $table->text('riwayat_imunisasi')->nullable();

            // OBJECTIVE
            $table->text('pemeriksaan_fisik_lainnya')->nullable();

            // ASSESSMENT
            $table->text('dx_utama')->nullable();
            $table->text('dx_sekunder')->nullable();

            // PLAN
            $table->text('tindakan_medis')->nullable();
            $table->text('konseling_edukasi')->nullable();
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->text('diet')->nullable();
            $table->text('resep_obat')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('assesmen', function (Blueprint $table) {
            $table->dropColumn([
                'keluhan_utama',
                'riwayat_penyakit_sekarang',
                'riwayat_penyakit_dahulu',
                'riwayat_penyakit_keluarga',
                'riwayat_pengobatan',
                'riwayat_sosial',
                'riwayat_operasi',
                'riwayat_alergi',
                'riwayat_imunisasi',
                'pemeriksaan_fisik_lainnya',
                'dx_utama',
                'dx_sekunder',
                'tindakan_medis',
                'konseling_edukasi',
                'rencana_tindak_lanjut',
                'diet',
                'resep_obat',
            ]);
        });
    }
};
