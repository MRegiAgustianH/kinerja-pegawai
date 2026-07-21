<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KriteriaController extends Controller
{
    public function index(Request $request): View
    {
        $divisi = Divisi::orderBy('nama_divisi')->get();
        $selected = $request->get('divisi', $divisi->first()->id ?? null);
        $kriteria = collect();
        if ($selected) {
            $kriteria = Kriteria::with('subKriteria')->where('id_divisi', $selected)->orderBy('id')->get();
        }

        return view('admin.kriteria.index', compact('divisi', 'selected', 'kriteria'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_divisi' => 'required|exists:divisi,id',
            'kode_kriteria' => 'required|string|max:20',
            'nama_kriteria' => 'required|string|max:150',
            'bobot' => 'required|numeric|min:0|max:100',
            'atribut' => 'required|in:benefit,cost',
            'target' => 'nullable|string',
        ]);
        Kriteria::create($data);

        return $this->validateBobot($data['id_divisi'], back(), 'Kriteria ditambahkan.');
    }

    public function update(Request $request, Kriteria $kriteria): RedirectResponse
    {
        $data = $request->validate([
            'kode_kriteria' => 'required|string|max:20',
            'nama_kriteria' => 'required|string|max:150',
            'bobot' => 'required|numeric|min:0|max:100',
            'atribut' => 'required|in:benefit,cost',
            'target' => 'nullable|string',
        ]);
        $kriteria->update($data);

        return $this->validateBobot($kriteria->id_divisi, back(), 'Kriteria diperbarui.');
    }

    public function destroy(Kriteria $kriteria): RedirectResponse
    {
        $idDivisi = $kriteria->id_divisi;
        $kriteria->delete();

        return $this->validateBobot($idDivisi, back(), 'Kriteria dihapus.');
    }

    public function storeSub(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_kriteria' => 'required|exists:kriteria,id',
            'nama_subkriteria' => 'required|string|max:150',
            'nilai' => 'required|integer|min:1|max:5',
            'keterangan' => 'nullable|string',
        ]);
        SubKriteria::create($data);

        return back()->with('success', 'Subkriteria ditambahkan.');
    }

    public function destroySub(SubKriteria $subKriteria): RedirectResponse
    {
        $subKriteria->delete();

        return back()->with('success', 'Subkriteria dihapus.');
    }

    private function validateBobot(int $idDivisi, RedirectResponse $back, string $msg): RedirectResponse
    {
        $total = Divisi::find($idDivisi)?->totalBobot() ?? 0;
        if (abs($total - 100) > 0.01) {
            return $back->with('warning', "$msg Total bobot divisi saat ini $total% (harus 100%).");
        }

        return $back->with('success', $msg);
    }
}