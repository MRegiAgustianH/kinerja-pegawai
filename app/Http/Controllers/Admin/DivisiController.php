<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DivisiController extends Controller
{
    public function index(): View
    {
        $divisi = Divisi::withCount(['pegawai', 'kriteria'])->orderBy('nama_divisi')->get();

        return view('admin.divisi.index', compact('divisi'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_divisi' => 'required|string|max:100',
            'kelompok_kerja' => 'required|in:Lapangan,Kantor',
        ]);
        Divisi::create($data);

        return redirect()->route('admin.divisi.index')->with('success', 'Divisi ditambahkan.');
    }

    public function update(Request $request, Divisi $divisi): RedirectResponse
    {
        $data = $request->validate([
            'nama_divisi' => 'required|string|max:100',
            'kelompok_kerja' => 'required|in:Lapangan,Kantor',
        ]);
        $divisi->update($data);

        return redirect()->route('admin.divisi.index')->with('success', 'Divisi diperbarui.');
    }

    public function destroy(Divisi $divisi): RedirectResponse
    {
        $divisi->delete();

        return redirect()->route('admin.divisi.index')->with('success', 'Divisi dihapus.');
    }
}