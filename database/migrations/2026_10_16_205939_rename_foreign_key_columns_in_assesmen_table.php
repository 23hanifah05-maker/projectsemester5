<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assesmen', function (Blueprint $table) {
            $table->renameColumn('id_pendaftaran(f)', 'id_pendaftaran');
            $table->renameColumn('id_kode10(f)', 'id_kode10');
            $table->renameColumn('id_kode9(f)', 'id_kode9');
        });
    }

    public function down(): void
    {
        Schema::table('assesmen', function (Blueprint $table) {
            $table->renameColumn('id_pendaftaran', 'id_pendaftaran(f)');
            $table->renameColumn('id_kode10', 'id_kode10(f)');
            $table->renameColumn('id_kode9', 'id_kode9(f)');
        });
    }
};
