<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\DetailPenilaian;
use App\Models\Kriteria;
use App\Models\Pegawai;
use App\Models\Penilaian;
use App\Models\Periode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KinerjaController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $pegawai = Pegawai::where('id_user', $user->id)->first();
        abort_unless($pegawai, 404, 'Data pegawai Anda tidak ditemukan.');

        $periode = Periode::where('status', 'aktif')->get();
        $selectedPeriodeId = request('periode', Periode::where('status', 'aktif')->value('id'));

        $kriteria = Kriteria::where('id_divisi', $pegawai->id_divisi)->orderBy('id')->get();

        $penilaian = null;
        if ($selectedPeriodeId) {
            $penilaian = Penilaian::with('detailPenilaian')
                ->where('id_pegawai', $pegawai->id)
                ->where('id_periode', $selectedPeriodeId)
                ->first();
        }

        return view('pegawai.kinerja', compact('pegawai', 'periode', 'selectedPeriodeId', 'kriteria', 'penilaian'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $pegawai = Pegawai::where('id_user', $user->id)->first();
        abort_unless($pegawai, 404, 'Data pegawai Anda tidak ditemukan.');

        $data = $request->validate([
            'id_periode' => 'required|exists:periode,id',
            'realisasi' => 'required|array',
            'realisasi.*' => 'nullable|numeric|min:0',
            'bukti_pdf' => 'nullable|array',
            'bukti_pdf.*' => 'nullable|file|mimes:pdf|max:5120', // Maksimal 5MB
        ]);

        $penilaian = Penilaian::updateOrCreate(
            [
                'id_pegawai' => $pegawai->id,
                'id_periode' => $data['id_periode'],
            ],
            [
                'status_penilaian' => 'pending', // Menunggu verifikasi
                'catatan_revisi' => null,
            ]
        );

        $kriteria = Kriteria::where('id_divisi', $pegawai->id_divisi)->get();

        foreach ($kriteria as $k) {
            $realisasiVal = $data['realisasi'][$k->id] ?? null;
            $existingDetail = DetailPenilaian::where('id_penilaian', $penilaian->id)
                ->where('id_kriteria', $k->id)
                ->first();

            $pdfPath = $existingDetail?->bukti_pdf;

            // Handle file upload jika ada file baru diunggah
            if ($request->hasFile("bukti_pdf.{$k->id}")) {
                $file = $request->file("bukti_pdf.{$k->id}");
                $filename = 'bukti_' . $penilaian->id . '_' . $k->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/bukti'), $filename);
                $pdfPath = 'uploads/bukti/' . $filename;
            }

            // Untuk kriteria kuantitatif, hitung nilai awal skala 1-5 berdasarkan realisasi vs target
            $nilaiAwal = 3.0; // Default tengah
            if ($k->tipe === 'kuantitatif' && $realisasiVal !== null && $k->target_angka > 0) {
                if ($k->atribut === 'cost') {
                    // Cost: Makin kecil realisasi dari target makin bagus.
                    // Jika realisasi <= target, nilai maksimal (5).
                    if ($realisasiVal <= $k->target_angka) {
                        $nilaiAwal = 5.0;
                    } else {
                        // Deviasi negatif: Nilai turun proporsional
                        $pencapaian = $k->target_angka / $realisasiVal;
                        $nilaiAwal = max(1.0, round($pencapaian * 5, 2));
                    }
                } else {
                    // Benefit: Makin besar realisasi dari target makin bagus.
                    $pencapaian = $realisasiVal / $k->target_angka;
                    $nilaiAwal = min(5.0, max(1.0, round($pencapaian * 5, 2)));
                }
            }

            DetailPenilaian::updateOrCreate(
                [
                    'id_penilaian' => $penilaian->id,
                    'id_kriteria' => $k->id,
                ],
                [
                    'realisasi' => $k->tipe === 'kuantitatif' ? $realisasiVal : null,
                    'bukti_pdf' => $pdfPath,
                    'nilai' => $existingDetail?->nilai ?? $nilaiAwal, // Jangan override nilai jika sudah diinput Kadiv
                ]
            );
        }

        return redirect()->route('pegawai.kinerja.index')->with('success', 'Laporan kinerja berhasil dikirim. Status menunggu verifikasi.');
    }
}