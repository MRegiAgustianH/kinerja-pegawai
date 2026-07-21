<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kriteria')->constrained('kriteria')->cascadeOnDelete();
            $table->string('nama_subkriteria');
            $table->tinyInteger('nilai')->unsigned();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kriteria');
    }
};
