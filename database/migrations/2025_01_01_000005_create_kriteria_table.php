<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_divisi')->constrained('divisi')->cascadeOnDelete();
            $table->string('kode_kriteria');
            $table->string('nama_kriteria');
            $table->decimal('bobot', 5, 2)->default(0);
            $table->enum('atribut', ['benefit', 'cost'])->default('benefit');
            $table->enum('tipe', ['kuantitatif', 'kualitatif'])->default('kuantitatif');
            $table->string('satuan')->nullable();
            $table->decimal('target_angka', 12, 2)->nullable();
            $table->text('target')->nullable(); // Target penjelasan / teks
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};