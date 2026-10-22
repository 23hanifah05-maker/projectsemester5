<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resume_medis', function (Blueprint $table) {
            $table->id();

            // Satu kunjungan = satu resume medis
            $table->unsignedBigInteger('id_pendaftaran')->unique();

            // Bagian atas
            $table->text('alamat')->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('pembayaran', 50)->nullable();
            $table->date('tgl_kunjungan')->nullable();
            $table->date('tgl_keluar')->nullable();

            // Penanggung jawab
            $table->string('pj_nama', 100)->nullable();
            $table->string('pj_hubungan', 50)->nullable();
            $table->string('pj_jk', 20)->nullable();
            $table->string('pj_hp', 20)->nullable();

            // Ringkasan klinis & penatalaksanaan
            $table->text('keluhan')->nullable();
            $table->text('kesadaran')->nullable();
            $table->text('fisik')->nullable();
            $table->text('penunjang')->nullable();
            $table->text('dx_utama')->nullable();
            $table->text('dx_sekunder')->nullable();
            $table->text('tindakan')->nullable();
            $table->text('terapi')->nullable();
            $table->text('instruksi')->nullable();

            $table->string('prognosis', 50)->nullable();
            $table->string('kondisi_keluar', 50)->nullable();
            $table->string('cara_keluar', 50)->nullable();

            // Tanda tangan DPJP
            $table->date('tanggal_ttd')->nullable();
            $table->time('jam_ttd')->nullable();

            $table->timestamps();
        });

        Schema::create('resume_medis_edukasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_medis_id')->constrained('resume_medis')->cascadeOnDelete();
            $table->string('edukasi', 150);
            $table->timestamps();

            $table->unique(['resume_medis_id', 'edukasi']);
        });

        Schema::create('resume_medis_snomed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_medis_id')->constrained('resume_medis')->cascadeOnDelete();
            $table->string('bagian', 30);
            $table->string('kode', 18);
            $table->string('deskripsi', 255);
            $table->timestamps();

            $table->unique(['resume_medis_id', 'bagian', 'kode']);
            $table->index('kode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_medis_snomed');
        Schema::dropIfExists('resume_medis_edukasi');
        Schema::dropIfExists('resume_medis');
    }
};