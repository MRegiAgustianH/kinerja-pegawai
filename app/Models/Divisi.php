<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    protected $table = 'divisi';

    protected $fillable = ['nama_divisi', 'kelompok_kerja'];

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'id_divisi');
    }

    public function kriteria(): HasMany
    {
        return $this->hasMany(Kriteria::class, 'id_divisi');
    }

    public function totalBobot(): float
    {
        return (float) $this->kriteria()->sum('bobot');
    }
}
