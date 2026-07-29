<?php

namespace App\Services;

use App\Models\HasilPenilaian;
use App\Models\Penilaian;
use Illuminate\Support\Collection;

class SmartService
{
    private const CMIN = 1;
    private const CMAX = 5;

    public function utility(float $nilai, string $atribut): float
    {
        $nilai = max((float) self::CMIN, min((float) self::CMAX, $nilai));
        if ($atribut === 'cost') {
            return (self::CMAX - $nilai) / (self::CMAX - self::CMIN);
        }
        return ($nilai - self::CMIN) / (self::CMAX - self::CMIN);
    }

    public function normalisasiBobot(float $bobot, float $totalBobot): float
    {
        if ($totalBobot <= 0) {
            return 0.0;
        }
        return $bobot / $totalBobot;
    }

    public function skorAkhir(Collection $details): float
    {
        $total = $details->sum(fn ($d) => (float) $d->kriteria->bobot);
        $skor = 0.0;
        foreach ($details as $d) {
            $u = $this->utility((float) $d->nilai, $d->kriteria->atribut);
            $w = $this->normalisasiBobot((float) $d->kriteria->bobot, $total);
            $skor += $u * $w;
        }
        return round($skor, 4);
    }

    public function kategori(float $skor): string
    {
        return match (true) {
            $skor > 0.80 => 'Sangat Baik',
            $skor >= 0.60 => 'Baik',
            $skor >= 0.40 => 'Cukup',
            default => 'Kurang',
        };
    }

    public function rekomendasi(string $kategori): string
    {
        return match ($kategori) {
            'Sangat Baik' => 'Pegawai terbaik, kandidat pemberian bonus, kandidat promosi jabatan, diprioritaskan dipanggil kembali jika pegawai kontrak.',
            'Baik' => 'Kandidat pemberian bonus, dipertahankan, dapat dipanggil kembali jika pegawai kontrak.',
            'Cukup' => 'Perlu pembinaan dan peningkatan kinerja, dipertimbangkan untuk dipanggil kembali jika pegawai kontrak.',
            default => 'Perlu evaluasi lanjutan, tidak direkomendasikan dipanggil kembali jika pegawai kontrak.',
        };
    }

    public function prosesPeriode(int $idPeriode): void
    {
        $penilaians = Penilaian::with([
            'detailPenilaian.kriteria',
            'pegawai.divisi',
        ])
            ->where('id_periode', $idPeriode)
            ->where('status_penilaian', 'approved')
            ->get();

        $hasil = [];
        foreach ($penilaians as $p) {
            $skor = $this->skorAkhir($p->detailPenilaian);
            $hasil[] = [
                'penilaian' => $p,
                'skor' => $skor,
                'kelompok' => $p->pegawai->divisi->kelompok_kerja ?? '-',
            ];
        }

        $grouped = collect($hasil)->groupBy('kelompok');
        foreach ($grouped as $items) {
            $sorted = $items->sortByDesc('skor')->values();
            foreach ($sorted as $i => $row) {
                $this->simpanHasil($row['penilaian']->id, $row['skor'], $i + 1);
            }
        }
    }

    private function simpanHasil(int $idPenilaian, float $skor, int $rank): void
    {
        $kategori = $this->kategori($skor);
        HasilPenilaian::updateOrCreate(
            ['id_penilaian' => $idPenilaian],
            [
                'nilai_smart' => $skor,
                'rangking' => $rank,
                'kategori' => $kategori,
                'rekomendasi' => $this->rekomendasi($kategori),
            ]
        );
    }
}