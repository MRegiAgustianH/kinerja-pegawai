<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('jabatan');
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->enum('status_pegawai', ['Tetap', 'Kontrak'])->default('Kontrak');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_masuk');
            $table->foreignId('id_divisi')->constrained('divisi')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
