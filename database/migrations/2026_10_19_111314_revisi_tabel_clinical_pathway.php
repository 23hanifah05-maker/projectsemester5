<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private string $tabel = 'clinical_pathway';

    public function up(): void
    {
        $t = $this->tabel;

        // 1. Perbaiki nama kolom id_pendaftaran.
        if (Schema::hasColumn($t, 'id_pendaftaran(f)')) {
            DB::statement("ALTER TABLE {$t} CHANGE `id_pendaftaran(f)` `id_pendaftaran` INT(11) NOT NULL");
        }

        // 2. Kolom diagnosa: lebih panjang dan boleh kosong.
        DB::statement(
            "ALTER TABLE {$t}
                MODIFY dx_utama VARCHAR(255) NULL,
                MODIFY dx_sekunder VARCHAR(255) NULL,
                MODIFY dx_banding VARCHAR(255) NULL"
        );

        // 3. Kolom teks boleh kosong.
        DB::statement(
            "ALTER TABLE {$t}
                MODIFY asesment_klinis TEXT NULL,
                MODIFY pemeriksaan_fisik TEXT NULL,
                MODIFY pemeriksaan_penunjang TEXT NULL,
                MODIFY farmasi TEXT NULL"
        );

        // 4. asuhan_keperawatan -> fisioterapi.
        if (Schema::hasColumn($t, 'asuhan_keperawatan')) {
            DB::statement("ALTER TABLE {$t} CHANGE asuhan_keperawatan fisioterapi TEXT NULL");
        }

        // 5. tarif_asuhan -> tarif_fisioterapi.
        if (Schema::hasColumn($t, 'tarif_asuhan')) {
            DB::statement("ALTER TABLE {$t} CHANGE tarif_asuhan tarif_fisioterapi INT(11) NOT NULL DEFAULT 0");
        }

        // 6. Hapus tarif_asesment (form tidak punya tarif Asesmen Klinis).
        if (Schema::hasColumn($t, 'tarif_asesment')) {
            Schema::table($t, function (Blueprint $table) {
                $table->dropColumn('tarif_asesment');
            });
        }

        // 7. Kolom tarif: nilai bawaan 0.
        DB::statement(
            "ALTER TABLE {$t}
                MODIFY tarif_fisik INT(11) NOT NULL DEFAULT 0,
                MODIFY tarif_penunjang INT(11) NOT NULL DEFAULT 0,
                MODIFY tarif_farmasi INT(11) NOT NULL DEFAULT 0,
                MODIFY total_tarif INT(11) NOT NULL DEFAULT 0"
        );

        // 8. Kolom baru untuk Edukasi dan Variasi Pelayanan.
        foreach (['edukasi', 'variasi_pelayanan'] as $kolom) {
            if (!Schema::hasColumn($t, $kolom)) {
                Schema::table($t, function (Blueprint $table) use ($kolom) {
                    $table->text($kolom)->nullable();
                });
            }
        }

        // 9. Kolom Waktu per baris.
        $kolomWaktu = [
            'waktu_dx_utama', 'waktu_dx_sekunder', 'waktu_dx_banding',
            'waktu_asesmen', 'waktu_fisik', 'waktu_penunjang',
            'waktu_farmasi', 'waktu_fisioterapi', 'waktu_edukasi',
            'waktu_variasi',
        ];

        foreach ($kolomWaktu as $kolom) {
            if (!Schema::hasColumn($t, $kolom)) {
                Schema::table($t, function (Blueprint $table) use ($kolom) {
                    $table->string($kolom, 20)->nullable();
                });
            }
        }

        // 10. created_at dan updated_at.
        if (!Schema::hasColumn($t, 'created_at')) {
            Schema::table($t, function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Revisi struktur ini tidak dibatalkan otomatis.
    }
};