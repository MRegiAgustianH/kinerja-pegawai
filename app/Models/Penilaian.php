<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = ['id_pegawai', 'id_user', 'id_periode', 'status_penilaian'];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'id_periode');
    }

    public function detailPenilaian(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class, 'id_penilaian');
    }

    public function hasil(): HasOne
    {
        return $this->hasOne(HasilPenilaian::class, 'id_penilaian');
    }
}
