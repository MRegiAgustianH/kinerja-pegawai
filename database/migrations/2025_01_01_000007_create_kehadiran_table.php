<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pegawai')->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('id_periode')->constrained('periode')->cascadeOnDelete();
            $table->unsignedSmallInteger('hari_kerja')->default(240);
            $table->unsignedSmallInteger('hari_hadir')->default(0);
            $table->unsignedSmallInteger('hari_terlambat')->default(0);
            $table->timestamps();
            $table->unique(['id_pegawai', 'id_periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};
