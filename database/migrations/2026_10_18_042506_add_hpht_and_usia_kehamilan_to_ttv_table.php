<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ttv', function (Blueprint $table) {
            $table->date('hpht')->nullable();
            $table->unsignedSmallInteger('usia_kehamilan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ttv', function (Blueprint $table) {
            $table->dropColumn(['hpht', 'usia_kehamilan']);
        });
    }
};