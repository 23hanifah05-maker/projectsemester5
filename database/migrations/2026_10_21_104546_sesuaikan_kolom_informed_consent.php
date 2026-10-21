<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $punyaPrimary = count(DB::select("SHOW KEYS FROM informed_consent WHERE Key_name = 'PRIMARY'")) > 0;

        if (!$punyaPrimary) {
            DB::statement('ALTER TABLE informed_consent MODIFY id_informed INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY');
        }

        if (Schema::hasColumn('informed_consent', 'id_pendaftaran') && !Schema::hasColumn('informed_consent', 'id_kunjungan')) {
            Schema::table('informed_consent', function (Blueprint $table) {
                $table->renameColumn('id_pendaftaran', 'id_kunjungan');
            });
        }

        $kolom = [
            'id_kunjungan'     => fn (Blueprint $t) => $t->string('id_kunjungan', 50)->nullable()->unique(),
            'no_rm'            => fn (Blueprint $t) => $t->string('no_rm', 30)->nullable(),
            'keputusan'        => fn (Blueprint $t) => $t->string('keputusan', 10)->nullable(),
            'checklist'        => fn (Blueprint $t) => $t->text('checklist')->nullable(),
            'isi_bebas'        => fn (Blueprint $t) => $t->text('isi_bebas')->nullable(),
            'nama_dokter'      => fn (Blueprint $t) => $t->string('nama_dokter', 100)->nullable(),
            'nama_pasien'      => fn (Blueprint $t) => $t->string('nama_pasien', 100)->nullable(),
            'nama_saksi'       => fn (Blueprint $t) => $t->string('nama_saksi', 100)->nullable(),
            'tanggal_tindakan' => fn (Blueprint $t) => $t->date('tanggal_tindakan')->nullable(),
            'pukul'            => fn (Blueprint $t) => $t->time('pukul')->nullable(),
            'ttd_dokter'       => fn (Blueprint $t) => $t->longText('ttd_dokter')->nullable(),
            'ttd_pasien'       => fn (Blueprint $t) => $t->longText('ttd_pasien')->nullable(),
            'ttd_saksi'        => fn (Blueprint $t) => $t->longText('ttd_saksi')->nullable(),
            'created_at'       => fn (Blueprint $t) => $t->timestamp('created_at')->nullable(),
            'updated_at'       => fn (Blueprint $t) => $t->timestamp('updated_at')->nullable(),
        ];

        foreach ($kolom as $nama => $buat) {
            if (!Schema::hasColumn('informed_consent', $nama)) {
                Schema::table('informed_consent', function (Blueprint $table) use ($buat) {
                    $buat($table);
                });
            }
        }
    }

    public function down(): void
    {
        //
    }
};