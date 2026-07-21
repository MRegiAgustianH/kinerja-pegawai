<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenilaian extends Model
{
    protected $table = 'detail_penilaian';

    protected $fillable = ['id_penilaian', 'id_kriteria', 'id_sub_kriteria', 'nilai'];

    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class, 'id_penilaian');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }

    public function subKriteria(): BelongsTo
    {
        return $this->belongsTo(SubKriteria::class, 'id_sub_kriteria');
    }
}
