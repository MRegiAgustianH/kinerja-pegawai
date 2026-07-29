<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        DB::transaction(function () use ($data) {
            // 1. Buat user otomatis untuk login pegawai
            $user = User::create([
                'nama' => $data['nama'],
                'username' => $data['nik'], // Username default = NIK
                'password' => Hash::make($data['nik'] . '123'), // Password default = NIK123
                'role' => 'pegawai',
                'id_divisi' => $data['id_divisi'],
            ]);

            // 2. Hubungkan pegawai ke user tersebut
            $data['id_user'] = $user->id;
            Pegawai::create($data);
        });

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai ditambahkan dan akun pengguna otomatis dibuat.');
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

        DB::transaction(function () use ($data, $pegawai) {
            $pegawai->update($data);

            // Update user terkait jika ada perubahan
            if ($pegawai->user) {
                $pegawai->user->update([
                    'nama' => $data['nama'],
                    'username' => $data['nik'],
                    'id_divisi' => $data['id_divisi'],
                ]);
            }
        });

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai diperbarui.');
    }

    public function destroy(Pegawai $pegawai): RedirectResponse
    {
        DB::transaction(function () use ($pegawai) {
            $user = $pegawai->user;
            $pegawai->delete();
            if ($user) {
                $user->delete(); // Hapus akun user juga saat pegawai dihapus
            }
        });

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai dan akun penggunanya dihapus.');
    }
}