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
            $table->text('target')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
