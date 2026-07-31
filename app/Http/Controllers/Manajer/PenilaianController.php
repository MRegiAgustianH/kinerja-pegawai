<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\DetailPenilaian;
use App\Models\Kriteria;
use App\Models\Pegawai;
use App\Models\Penilaian;
use App\Models\Periode;
use App\Services\SmartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $idDivisi = auth()->user()->id_divisi;
        $periode = Periode::latest()->get();
        $idPeriode = $request->get('periode', Periode::where('status', 'aktif')->value('id') ?? $periode->first()?->id);

        // Ambil semua pegawai di divisi kadiv tersebut
        $pegawai = Pegawai::where('id_divisi', $idDivisi)
            ->with(['penilaian' => fn ($q) => $q->where('id_periode', $idPeriode)])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('manajer.penilaian.index', compact('periode', 'idPeriode', 'pegawai'));
    }

    public function detail(Penilaian $penilaian): View
    {
        $penilaian->load(['pegawai.divisi', 'detailPenilaian.kriteria']);
        $kriteria = Kriteria::where('id_divisi', $penilaian->pegawai->id_divisi)->orderBy('id')->get();

        return view('manajer.penilaian.detail', compact('penilaian', 'kriteria'));
    }

    public function approve(Request $request, Penilaian $penilaian, SmartService $smart): RedirectResponse
    {
        $data = $request->validate([
            'nilai' => 'nullable|array',
            'nilai.*' => 'nullable|numeric|min:1|max:5',
        ]);

        // Simpan nilai kualitatif yang diinput Kadiv
        foreach (($data['nilai'] ?? []) as $idKriteria => $nilaiVal) {
            DetailPenilaian::updateOrCreate(
                [
                    'id_penilaian' => $penilaian->id,
                    'id_kriteria' => $idKriteria,
                ],
                [
                    'nilai' => $nilaiVal,
                ]
            );
        }

        // Set status approved & penilai (id_user)
        $penilaian->update([
            'status_penilaian' => 'approved',
            'id_user' => auth()->id(),
            'catatan_revisi' => null,
        ]);

        // Jalankan perhitungan SMART otomatis setelah disetujui (Back-end)
        $smart->prosesPeriode($penilaian->id_periode);

        return redirect()->route('manajer.penilaian.index')->with('success', 'Laporan disetujui dan nilai berhasil dikalkulasi otomatis.');
    }

    public function reject(Request $request, Penilaian $penilaian): RedirectResponse
    {
        $data = $request->validate([
            'catatan_revisi' => 'required|string',
        ]);

        $penilaian->update([
            'status_penilaian' => 'rejected',
            'catatan_revisi' => $data['catatan_revisi'],
            'id_user' => auth()->id(),
        ]);

        return redirect()->route('manajer.penilaian.index')->with('success', 'Laporan ditolak untuk revisi pegawai.');
    }
}