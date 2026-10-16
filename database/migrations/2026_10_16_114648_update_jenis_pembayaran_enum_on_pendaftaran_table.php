
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pendaftaran
            MODIFY jenis_pembayaran
            ENUM('Umum', 'Asuransi Swasta', 'BPJS')
            NOT NULL
        ");
    }

    public function down(): void
    {
        // Tidak mengembalikan ENUM ke struktur lama
        // agar data BPJS yang sudah tersimpan tidak terhapus.
    }
};
