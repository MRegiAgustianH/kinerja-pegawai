<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilPenilaian;
use App\Models\Periode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HasilController extends Controller
{
    public function index(Request $request): View
    {
        $periode = Periode::orderByDesc('id')->get();
        $idPeriode = $request->get('periode', Periode::where('status', 'aktif')->value('id') ?? $periode->first()?->id);
        $hasil = HasilPenilaian::with(['penilaian.pegawai.divisi', 'penilaian.periode'])
            ->when($idPeriode, fn ($q) => $q->whereHas('penilaian', fn ($q2) => $q2->where('id_periode', $idPeriode)))
            ->orderBy('rangking')->paginate(15)->withQueryString();

        return view('admin.hasil.index', compact('periode', 'idPeriode', 'hasil'));
    }

    public function pdf(Request $request)
    {
        $idPeriode = $request->get('periode');
        $hasil = HasilPenilaian::with(['penilaian.pegawai.divisi', 'penilaian.periode'])
            ->when($idPeriode, fn ($q) => $q->whereHas('penilaian', fn ($q2) => $q2->where('id_periode', $idPeriode)))
            ->orderBy('rangking')->get();
        $periode = Periode::find($idPeriode);

        $pdf = Pdf::loadView('admin.hasil.pdf', compact('hasil', 'periode'))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penilaian-kinerja.pdf');
    }
}