<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\HasilPenilaian;
use App\Models\Pegawai;
use App\Models\Periode;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('pimpinan.dashboard', [
            'jumlahPegawai' => Pegawai::count(),
            'periodeAktif' => Periode::where('status', 'aktif')->get(),
            'terbaik' => HasilPenilaian::with('penilaian.pegawai.divisi')
                ->whereHas('penilaian', fn ($q) => $q->whereIn('id_periode', Periode::where('status', 'aktif')->pluck('id')))
                ->orderBy('rangking')->limit(5)->get(),
        ]);
    }
}