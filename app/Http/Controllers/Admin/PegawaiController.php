<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Pegawai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $pegawai = Pegawai::with('divisi')
            ->when($request->search, fn ($q, $s) => $q->where('nama', 'like', "%$s%")->orWhere('nik', 'like', "%$s%"))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.pegawai.index', compact('pegawai'));
    }

    public function create(): View
    {
        return view('admin.pegawai.form', ['divisi' => Divisi::orderBy('nama_divisi')->get(), 'pegawai' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nik' => 'required|string|unique:pegawai,nik',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'status' => 'required|in:Aktif,Nonaktif',
            'status_pegawai' => 'required|in:Tetap,Kontrak',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_masuk' => 'required|date',
            'id_divisi' => 'required|exists:divisi,id',
        ]);
        Pegawai::create($data);

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai ditambahkan.');
    }

    public function edit(Pegawai $pegawai): View
    {
        return view('admin.pegawai.form', ['divisi' => Divisi::orderBy('nama_divisi')->get(), 'pegawai' => $pegawai]);
    }

    public function update(Request $request, Pegawai $pegawai): RedirectResponse
    {
        $data = $request->validate([
            'nik' => 'required|string|unique:pegawai,nik,' . $pegawai->id,
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'status' => 'required|in:Aktif,Nonaktif',
            'status_pegawai' => 'required|in:Tetap,Kontrak',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_masuk' => 'required|date',
            'id_divisi' => 'required|exists:divisi,id',
        ]);
        $pegawai->update($data);

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai diperbarui.');
    }

    public function destroy(Pegawai $pegawai): RedirectResponse
    {
        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai dihapus.');
    }
}