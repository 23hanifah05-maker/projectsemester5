<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_diagnosis', function (Blueprint $table) {
    $table->id('id_detail_diagnosis');

    $table->integer('id_assesmen');
    $table->integer('id_kode10');

    $table->timestamps();

    $table->foreign('id_assesmen')
        ->references('id_assesmen')
        ->on('assesmen')
        ->cascadeOnDelete();

    $table->foreign('id_kode10')
        ->references('id_icd10')
        ->on('kode_diagnosis')
        ->restrictOnDelete();

    $table->unique(
        ['id_assesmen', 'id_kode10'],
        'detail_diagnosis_unik'
    );
});
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_diagnosis');
    }
};