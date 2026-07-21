<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubKriteria extends Model
{
    protected $table = 'sub_kriteria';

    protected $fillable = ['id_kriteria', 'nama_subkriteria', 'nilai', 'keterangan'];

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }
}
