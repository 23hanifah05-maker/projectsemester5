<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ttv', function (Blueprint $table) {
            $table->renameColumn('id_pendafataran', 'id_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::table('ttv', function (Blueprint $table) {
            $table->renameColumn('id_pendaftaran', 'id_pendafataran');
        });
    }
};