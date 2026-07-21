<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\DetailPenilaian;
use App\Models\Kriteria;
use App\Models\Pegawai;
use App\Models\Penilaian;
use App\Models\Periode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $idDivisi = auth()->user()->id_divisi;
        $periode = Periode::where('status', 'aktif')->get();
        $idPeriode = $request->get('periode', Periode::where('status', 'aktif')->value('id') ?? $periode->first()?->id);
        $pegawai = Pegawai::where('id_divisi', $idDivisi)
            ->when($idPeriode, fn ($q) => $q->with(['penilaian' => fn ($q2) => $q2->where('id_periode', $idPeriode)]))
            ->latest()->paginate(10)->withQueryString();

        return view('manajer.penilaian.index', compact('periode', 'idPeriode', 'pegawai'));
    }

    public function create(Pegawai $pegawai): View
    {
        $kriteria = Kriteria::where('id_divisi', $pegawai->id_divisi)->orderBy('id')->get();
        $periode = Periode::where('status', 'aktif')->first();
        abort_unless($periode, 403, 'Belum ada periode aktif.');

        return view('manajer.penilaian.form', compact('pegawai', 'kriteria', 'periode'));
    }

    public function store(Request $request, Pegawai $pegawai): RedirectResponse
    {
        $data = $request->validate([
            'id_periode' => 'required|exists:periode,id',
            'nilai' => 'required|array',
            'nilai.*' => 'required|integer|min:1|max:5',
        ]);

        $penilaian = Penilaian::updateOrCreate(
            ['id_pegawai' => $pegawai->id, 'id_periode' => $data['id_periode']],
            ['id_user' => auth()->id(), 'status_penilaian' => 'final']
        );
        DetailPenilaian::where('id_penilaian', $penilaian->id)->delete();
        foreach ($data['nilai'] as $idKriteria => $nilai) {
            DetailPenilaian::create([
                'id_penilaian' => $penilaian->id,
                'id_kriteria' => $idKriteria,
                'nilai' => $nilai,
            ]);
        }

        return redirect()->route('manajer.penilaian.index')->with('success', 'Penilaian disimpan.');
    }
}