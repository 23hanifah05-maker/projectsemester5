<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('pendaftaran', function (Blueprint $table) {
        $table->string('status_periksa', 20)
              ->default('periksa');
    });
}

public function down(): void
{
    Schema::table('pendaftaran', function (Blueprint $table) {
        $table->dropColumn('status_periksa');
    });
}
};
