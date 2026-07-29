<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penilaian')->constrained('penilaian')->cascadeOnDelete();
            $table->foreignId('id_kriteria')->constrained('kriteria')->restrictOnDelete();
            $table->foreignId('id_sub_kriteria')->nullable()->constrained('sub_kriteria')->nullOnDelete();
            $table->decimal('realisasi', 12, 2)->nullable();
            $table->string('bukti_pdf')->nullable();
            $table->decimal('nilai', 5, 2)->default(1.00); // Nilai final skala 1-5 setelah input / kalkulasi
            $table->timestamps();
            $table->unique(['id_penilaian', 'id_kriteria']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penilaian');
    }
};