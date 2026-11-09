<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('general_consent', function (Blueprint $table) {
            $table->integer('id_pendaftaran')->nullable()->unique();

            $table->string('hak_kewajiban')->nullable();
            $table->string('persetujuan_pelayanan')->nullable();
            $table->string('pelepasan_informasi')->nullable();
            $table->string('pengajuan_keluhan')->nullable();
            $table->string('informasi_rawat_jalan')->nullable();
            $table->string('kewajiban_pembayaran')->nullable();
            $table->string('pernyataan_persetujuan')->nullable();

            $table->boolean('konfirmasi')->default(false);
            $table->json('anggota_keluarga')->nullable();

            $table->string('ttd_pemberi')->nullable();
            $table->string('nama_pemberi')->nullable();
            $table->string('ttd_pasien')->nullable();
            $table->string('nama_penandatangan')->nullable();

            $table->string('tanggal')->nullable();
            $table->string('jam')->nullable();

            $table->foreign('id_pendaftaran')
                ->references('id_pendaftaran')
                ->on('pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::table('general_consent', function (Blueprint $table) {
            $table->dropForeign(['id_pendaftaran']);
            $table->dropUnique(['id_pendaftaran']);

            $table->dropColumn([
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
            ]);
        });
    }
};
