<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_trail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username', 100)->nullable();
            $table->string('role', 50)->nullable();
            $table->string('aktivitas', 30);
            $table->string('modul', 50);
            $table->string('no_rm', 20)->nullable();
            $table->string('bidang_kolom', 100)->nullable();
            $table->text('sebelum')->nullable();
            $table->text('sesudah')->nullable();
            $table->string('status', 20)->default('Berhasil');
            $table->string('ip_address', 45)->nullable();
            $table->string('nama_perangkat', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_trail');
    }
};