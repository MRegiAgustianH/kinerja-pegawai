<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Periode;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $idDivisi = auth()->user()->id_divisi;
        $pegawai = Pegawai::where('id_divisi', $idDivisi)->count();
        $periodeAktif = Periode::where('status', 'aktif')->get();

        return view('manajer.dashboard', compact('pegawai', 'periodeAktif'));
    }
}