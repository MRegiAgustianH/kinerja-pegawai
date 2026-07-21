<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pegawai')->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('users')->restrictOnDelete();
            $table->foreignId('id_periode')->constrained('periode')->cascadeOnDelete();
            $table->enum('status_penilaian', ['draft', 'final'])->default('draft');
            $table->timestamps();
            $table->unique(['id_pegawai', 'id_periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
