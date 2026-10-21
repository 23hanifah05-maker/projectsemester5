<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('informed_consent')) {
            Schema::create('informed_consent', function (Blueprint $table) {
                $table->increments('id_informed');
            });
        } else {
            // tabel lama dari phpMyAdmin belum punya primary key dan auto increment
            $punyaPrimary = DB::select("SHOW KEYS FROM informed_consent WHERE Key_name = 'PRIMARY'");
            if (empty($punyaPrimary)) {
                DB::statement('ALTER TABLE informed_consent
                    MODIFY id_informed INT(11) NOT NULL AUTO_INCREMENT,
                    ADD PRIMARY KEY (id_informed)');
            }
        }

        Schema::table('informed_consent', function (Blueprint $table) {
            if (!Schema::hasColumn('informed_consent', 'no_rm')) {
                $table->string('no_rm', 20)->after('id_informed');
            }
            if (!Schema::hasColumn('informed_consent', 'id_kunjungan')) {
                $table->unsignedInteger('id_kunjungan')->unique()->after('no_rm');
            }
            if (!Schema::hasColumn('informed_consent', 'tindakan')) {
                $table->string('tindakan')->nullable();
            }
            if (!Schema::hasColumn('informed_consent', 'diagnosis')) {
                $table->string('diagnosis')->nullable();
            }
            if (!Schema::hasColumn('informed_consent', 'penjelasan_tindakan')) {
                $table->text('penjelasan_tindakan')->nullable();
            }
            if (!Schema::hasColumn('informed_consent', 'risiko')) {
                $table->text('risiko')->nullable();
            }
            if (!Schema::hasColumn('informed_consent', 'alternatif')) {
                $table->text('alternatif')->nullable();
            }
            if (!Schema::hasColumn('informed_consent', 'keputusan')) {
                $table->enum('keputusan', ['setuju', 'menolak'])->default('setuju');
            }
            if (!Schema::hasColumn('informed_consent', 'nama_penandatangan')) {
                $table->string('nama_penandatangan', 100)->nullable();
            }
            if (!Schema::hasColumn('informed_consent', 'hubungan')) {
                $table->string('hubungan', 50)->nullable();
            }
            if (!Schema::hasColumn('informed_consent', 'nama_saksi')) {
                $table->string('nama_saksi', 100)->nullable();
            }
            if (!Schema::hasColumn('informed_consent', 'tanggal')) {
                $table->date('tanggal')->nullable();
            }
            if (!Schema::hasColumn('informed_consent', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        // dikosongkan agar rollback tidak menghapus tabel lama
    }
};