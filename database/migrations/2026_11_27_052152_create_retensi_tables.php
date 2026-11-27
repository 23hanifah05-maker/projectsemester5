
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel utama retensi
        Schema::create('retensi', function (Blueprint $table) {
            $table->increments('id_retensi');
            $table->string('no_rm', 20);
            $table->date('tanggal_kunjungan_terakhir')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->string('status_retensi', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('no_rm');
        });

        // Tabel penilaian retensi
        Schema::create('retensi_penilaian', function (Blueprint $table) {
            $table->increments('id_penilaian');
            $table->unsignedInteger('id_retensi');
            $table->date('tanggal_penilaian')->nullable();
            $table->string('hasil_penilaian', 100)->nullable();
            $table->text('alasan_penilaian')->nullable();
            $table->string('petugas_penilai', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_retensi')
                ->references('id_retensi')
                ->on('retensi')
                ->restrictOnDelete();
        });

        // Tabel pemusnahan
        Schema::create('retensi_pemusnahan', function (Blueprint $table) {
            $table->increments('id_pemusnahan');
            $table->unsignedInteger('id_retensi');
            $table->date('tanggal_pemusnahan')->nullable();
            $table->string('metode_pemusnahan', 100)->nullable();
            $table->string('nomor_berita_acara', 100)->nullable();
            $table->string('file_berita_acara', 255)->nullable();
            $table->string('petugas_pelaksana', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_retensi')
                ->references('id_retensi')
                ->on('retensi')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retensi_pemusnahan');
        Schema::dropIfExists('retensi_penilaian');
        Schema::dropIfExists('retensi');
    }
};
