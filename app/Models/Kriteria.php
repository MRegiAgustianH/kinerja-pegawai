<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = [
        'id_divisi', 'kode_kriteria', 'nama_kriteria', 'bobot', 'atribut', 'target',
    ];

    protected function casts(): array
    {
        return ['bobot' => 'decimal:2'];
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    public function subKriteria(): HasMany
    {
        return $this->hasMany(SubKriteria::class, 'id_kriteria');
    }

    public function detailPenilaian(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class, 'id_kriteria');
    }
}
