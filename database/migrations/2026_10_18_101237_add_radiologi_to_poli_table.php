<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('poli')->where('slug', 'radiologi')->exists()) {
            DB::table('poli')->insert([
                'nama_poli' => 'Radiologi',
                'slug' => 'radiologi',
                'nama_dokter' => 'Dr. Spesialis Radiologi',
            ]);
        }
    }

    public function down(): void
    {
        DB::table('poli')
            ->where('slug', 'radiologi')
            ->delete();
    }
};