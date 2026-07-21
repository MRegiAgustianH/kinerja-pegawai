<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\HasilPenilaian;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HasilController extends Controller
{
    public function index(Request $request): View
    {
        $idDivisi = auth()->user()->id_divisi;
        $periode = Periode::orderByDesc('id')->get();
        $idPeriode = $request->get('periode', Periode::where('status', 'aktif')->value('id') ?? $periode->first()?->id);
        $hasil = HasilPenilaian::with(['penilaian.pegawai.divisi', 'penilaian.periode'])
            ->whereHas('penilaian.pegawai', fn ($q) => $q->where('id_divisi', $idDivisi))
            ->when($idPeriode, fn ($q) => $q->whereHas('penilaian', fn ($q2) => $q2->where('id_periode', $idPeriode)))
            ->orderBy('rangking')->paginate(15)->withQueryString();

        return view('manajer.hasil.index', compact('periode', 'idPeriode', 'hasil'));
    }
}