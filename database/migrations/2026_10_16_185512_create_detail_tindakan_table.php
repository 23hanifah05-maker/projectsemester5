<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_tindakan', function (Blueprint $table) {
    $table->id('id_detail_tindakan');

    $table->integer('id_assesmen');
    $table->integer('id_kode9');

    $table->timestamps();

    $table->foreign('id_assesmen')
        ->references('id_assesmen')
        ->on('assesmen')
        ->cascadeOnDelete();

    $table->foreign('id_kode9')
        ->references('id_icd9')
        ->on('kode_tindakan')
        ->restrictOnDelete();

    $table->unique(
        ['id_assesmen', 'id_kode9'],
        'detail_tindakan_unik'
    );
});
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_tindakan');
    }
};