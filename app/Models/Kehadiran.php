<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';

    protected $fillable = [
        'id_pegawai', 'id_periode', 'hari_kerja', 'hari_hadir', 'hari_terlambat',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'id_periode');
    }
}
