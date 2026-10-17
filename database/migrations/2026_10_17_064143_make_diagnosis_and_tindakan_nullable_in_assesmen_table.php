<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assesmen', function (Blueprint $table) {
            $table->integer('id_kode10')
                ->nullable()
                ->default(null)
                ->change();

            $table->integer('id_kode9')
                ->nullable()
                ->default(null)
                ->change();
        });
    }

    public function down(): void
    {
        // Tidak mengembalikan kolom ke NOT NULL karena
        // data asesmen mungkin sudah tersimpan tanpa kode.
    }
};
