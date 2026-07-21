<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use App\Models\Periode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KehadiranController extends Controller
{
    public function index(Request $request): View
    {
        $periode = Periode::orderByDesc('id')->get();
        $idPeriode = $request->get('periode', Periode::where('status', 'aktif')->value('id') ?? $periode->first()?->id);
        $kehadiran = Kehadiran::with('pegawai')
            ->when($idPeriode, fn ($q) => $q->where('id_periode', $idPeriode))
            ->paginate(15)->withQueryString();

        return view('admin.kehadiran.index', compact('periode', 'idPeriode', 'kehadiran'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_pegawai' => 'required|exists:pegawai,id',
            'id_periode' => 'required|exists:periode,id',
            'hari_kerja' => 'required|integer|min:1',
            'hari_hadir' => 'required|integer|min:0',
            'hari_terlambat' => 'required|integer|min:0',
        ]);
        Kehadiran::updateOrCreate(
            ['id_pegawai' => $data['id_pegawai'], 'id_periode' => $data['id_periode']],
            $data
        );

        return back()->with('success', 'Data kehadiran disimpan.');
    }

    public function destroy(Kehadiran $kehadiran): RedirectResponse
    {
        $kehadiran->delete();

        return back()->with('success', 'Data kehadiran dihapus.');
    }
}