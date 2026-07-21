<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nik', 'nama', 'jabatan', 'status', 'status_pegawai',
        'jenis_kelamin', 'tanggal_masuk', 'id_divisi',
    ];

    protected function casts(): array
    {
        return ['tanggal_masuk' => 'date'];
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    public function kehadiran(): HasMany
    {
        return $this->hasMany(Kehadiran::class, 'id_pegawai');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'id_pegawai');
    }
}
