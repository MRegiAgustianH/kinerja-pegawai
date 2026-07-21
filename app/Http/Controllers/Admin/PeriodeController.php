<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use App\Services\SmartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodeController extends Controller
{
    public function index(): View
    {
        $periode = Periode::latest()->get();

        return view('admin.periode.index', compact('periode'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_periode' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);
        $data['status'] = 'aktif';
        Periode::create($data);

        return redirect()->route('admin.periode.index')->with('success', 'Periode ditambahkan.');
    }

    public function toggleStatus(Periode $periode): RedirectResponse
    {
        if ($periode->status === 'aktif') {
            $periode->update(['status' => 'tutup']);
        } else {
            $periode->update(['status' => 'aktif']);
        }

        return back()->with('success', 'Status periode diperbarui.');
    }

    public function proses(Periode $periode, SmartService $smart): RedirectResponse
    {
        $smart->prosesPeriode($periode->id);

        return back()->with('success', 'Perhitungan SMART dijalankan untuk periode ini.');
    }

    public function destroy(Periode $periode): RedirectResponse
    {
        $periode->delete();

        return redirect()->route('admin.periode.index')->with('success', 'Periode dihapus.');
    }
}