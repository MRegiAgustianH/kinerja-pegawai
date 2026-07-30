<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    protected $table = 'periode';

    protected $fillable = ['nama_periode', 'tanggal_mulai', 'tanggal_selesai', 'status'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }



    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'id_periode');
    }
}
