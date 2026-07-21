<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_penilaian')->unique()->constrained('penilaian')->cascadeOnDelete();
            $table->decimal('nilai_smart', 6, 4)->default(0);
            $table->unsignedInteger('rangking')->default(0);
            $table->string('kategori')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_penilaian');
    }
};
