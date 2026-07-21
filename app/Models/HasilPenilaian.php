<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilPenilaian extends Model
{
    protected $table = 'hasil_penilaian';

    protected $fillable = ['id_penilaian', 'nilai_smart', 'rangking', 'kategori', 'rekomendasi'];

    protected function casts(): array
    {
        return ['nilai_smart' => 'decimal:4'];
    }

    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class, 'id_penilaian');
    }
}
