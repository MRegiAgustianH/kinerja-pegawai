<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilPenilaian;
use App\Models\Periode;
use App\Models\Divisi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HasilController extends Controller
{
    public function index(Request $request): View
    {
        $periode = Periode::orderByDesc('id')->get();
        $idPeriode = $request->get('periode', Periode::where('status', 'aktif')->value('id') ?? $periode->first()?->id);
        
        $kelompokKerja = $request->get('kelompok_kerja');
        $idDivisi = $request->get('divisi');
        
        $divisiList = Divisi::orderBy('nama_divisi')->get();

        $query = HasilPenilaian::with(['penilaian.pegawai.divisi', 'penilaian.periode'])
            ->when($idPeriode, fn ($q) => $q->whereHas('penilaian', fn ($q2) => $q2->where('id_periode', $idPeriode)))
            ->when($kelompokKerja, fn ($q) => $q->whereHas('penilaian.pegawai.divisi', fn ($q2) => $q2->where('kelompok_kerja', $kelompokKerja)))
            ->when($idDivisi, fn ($q) => $q->whereHas('penilaian.pegawai', fn ($q2) => $q2->where('id_divisi', $idDivisi)));

        $hasil = $query->orderBy('rangking')->paginate(15)->withQueryString();

        return view('admin.hasil.index', compact('periode', 'idPeriode', 'hasil', 'divisiList', 'kelompokKerja', 'idDivisi'));
    }

    public function pdf(Request $request)
    {
        $idPeriode = $request->get('periode');
        $kelompokKerja = $request->get('kelompok_kerja');
        $idDivisi = $request->get('divisi');

        $query = HasilPenilaian::with(['penilaian.pegawai.divisi', 'penilaian.periode'])
            ->when($idPeriode, fn ($q) => $q->whereHas('penilaian', fn ($q2) => $q2->where('id_periode', $idPeriode)))
            ->when($kelompokKerja, fn ($q) => $q->whereHas('penilaian.pegawai.divisi', fn ($q2) => $q2->where('kelompok_kerja', $kelompokKerja)))
            ->when($idDivisi, fn ($q) => $q->whereHas('penilaian.pegawai', fn ($q2) => $q2->where('id_divisi', $idDivisi)));

        $hasil = $query->orderBy('rangking')->get();
        $periode = Periode::find($idPeriode);
        $selectedDivisi = $idDivisi ? Divisi::find($idDivisi) : null;

        $pdf = Pdf::loadView('admin.hasil.pdf', compact('hasil', 'periode', 'kelompokKerja', 'selectedDivisi'))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-penilaian-kinerja.pdf');
    }
}