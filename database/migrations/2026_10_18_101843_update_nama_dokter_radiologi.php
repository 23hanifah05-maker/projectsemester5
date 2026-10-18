<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('poli')
            ->where('slug', 'radiologi')
            ->update([
                'nama_dokter' => 'Dr. Spesialis Radiologi',
            ]);
    }

    public function down(): void
    {
        DB::table('poli')
            ->where('slug', 'radiologi')
            ->update([
                'nama_dokter' => 'Dokter Spesialis Radiologi',
            ]);
    }
};