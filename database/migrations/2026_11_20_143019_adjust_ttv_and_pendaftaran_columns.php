<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom alergi dari tabel ttv
        if (Schema::hasColumn('ttv', 'alergi')) {
            Schema::table('ttv', function (Blueprint $table) {
                $table->dropColumn('alergi');
            });
        }

        // Tambahkan nomor_bpjs ke tabel pendaftaran
        if (!Schema::hasColumn('pendaftaran', 'nomor_bpjs')) {
            Schema::table('pendaftaran', function (Blueprint $table) {
                $table->string('nomor_bpjs', 30)
                    ->nullable()
                    ->after('jenis_pembayaran');
            });
        }
    }

    public function down(): void
    {
        // Kembalikan kolom alergi jika migration di-rollback
        if (!Schema::hasColumn('ttv', 'alergi')) {
            Schema::table('ttv', function (Blueprint $table) {
                $table->string('alergi', 255)->nullable();
            });
        }

        // Hapus nomor_bpjs jika migration di-rollback
        if (Schema::hasColumn('pendaftaran', 'nomor_bpjs')) {
            Schema::table('pendaftaran', function (Blueprint $table) {
                $table->dropColumn('nomor_bpjs');
            });
        }
    }
};