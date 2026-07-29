@extends('layouts.app')
@section('title', 'Detail & Verifikasi Kinerja')
@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white py-3 fw-bold">
                <i class="bi bi-person me-2 text-primary"></i> Data Pegawai & Status Laporan
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td style="width: 150px;" class="fw-bold">Nama Pegawai</td><td>: {{ $penilaian->pegawai->nama }}</td></tr>
                    <tr><td class="fw-bold">NIK</td><td>: {{ $penilaian->pegawai->nik }}</td></tr>
                    <tr><td class="fw-bold">Jabatan</td><td>: {{ $penilaian->pegawai->jabatan }}</td></tr>
                    <tr><td class="fw-bold">Periode</td><td>: {{ $penilaian->periode->nama_periode }}</td></tr>
                    <tr><td class="fw-bold">Status</td><td>: <span class="badge text-bg-info text-uppercase">{{ $penilaian->status_penilaian }}</span></td></tr>
                </table>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 fw-bold">
                <i class="bi bi-card-checklist me-2 text-primary"></i> Detail Capaian Kinerja & Bukti
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">Kode</th>
                                <th>Kriteria (KPI)</th>
                                <th>Bobot</th>
                                <th>Tipe</th>
                                <th>Target</th>
                                <th>Realisasi</th>
                                <th>Bukti PDF</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($kriteria as $k)
                            @php
                                $detail = $penilaian->detailPenilaian->firstWhere('id_kriteria', $k->id);
                                $realVal = $detail ? $detail->realisasi : '-';
                                $pdfPath = $detail ? $detail->bukti_pdf : '';
                            @endphp
                            <tr>
                                <td><span class="badge bg-secondary">{{ $k->kode_kriteria }}</span></td>
                                <td>{{ $k->nama_kriteria }}</td>
                                <td>{{ $k->bobot }}%</td>
                                <td><span class="badge text-bg-{{ $k->tipe==='kuantitatif'?'primary':'info' }}">{{ $k->tipe }}</span></td>
                                <td>{{ $k->target }}</td>
                                <td>
                                    @if($k->tipe === 'kuantitatif')
                                        <strong>{{ $realVal }}</strong> {{ $k->satuan }}
                                    @else
                                        <span class="text-muted small">Dinilai manual</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pdfPath)
                                        <a href="{{ asset($pdfPath) }}" target="_blank" class="btn btn-sm btn-link text-danger p-0">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i>Lihat PDF
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($penilaian->status_penilaian === 'pending')
            <!-- Form Aksi Persetujuan & Penilaian Sikap -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3 fw-bold text-success">
                    <i class="bi bi-check-circle-fill me-2"></i> Setujui & Beri Nilai Sikap
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('manajer.penilaian.approve', $penilaian) }}" data-confirm="Setujui laporan kinerja ini dan kalkulasi skor SMART?" data-confirm-title="Persetujuan Laporan">
                        @csrf
                        @php
                            $kualitatif = $kriteria->where('tipe', 'kualitatif');
                        @endphp
                        @if($kualitatif->count() > 0)
                            <h6 class="fw-bold mb-2">Input Nilai Kompetensi / Sikap (Skala 1-5):</h6>
                            @foreach($kualitatif as $k)
                                <div class="mb-3">
                                    <label class="form-label small fw-bold mb-1">{{ $k->nama_kriteria }}</label>
                                    <select name="nilai[{{ $k->id }}]" class="form-select form-select-sm" required>
                                        <option value="">- pilih nilai -</option>
                                        <option value="5">5 - Sangat Baik</option>
                                        <option value="4">4 - Baik</option>
                                        <option value="3">3 - Cukup</option>
                                        <option value="2">2 - Kurang</option>
                                        <option value="1">1 - Sangat Kurang</option>
                                    </select>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small mb-3">Semua kriteria bersifat kuantitatif, tidak ada nilai sikap manual yang diperlukan.</p>
                        @endif

                        <button class="btn btn-success btn-sm w-100"><i class="bi bi-check-lg me-1"></i> Approve & Hitung SMART</button>
                    </form>
                </div>
            </div>

            <!-- Form Tolak / Revisi -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 fw-bold text-danger">
                    <i class="bi bi-x-circle-fill me-2"></i> Kembalikan untuk Revisi
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('manajer.penilaian.reject', $penilaian) }}" data-confirm="Kembalikan laporan ini ke pegawai untuk direvisi?" data-confirm-title="Tolak Laporan">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold mb-1">Catatan Revisi / Alasan Penolakan</label>
                            <textarea name="catatan_revisi" class="form-control form-control-sm" rows="3" placeholder="Masukkan instruksi perbaikan..." required></textarea>
                        </div>
                        <button class="btn btn-danger btn-sm w-100"><i class="bi bi-x-lg me-1"></i> Tolak & Minta Revisi</button>
                    </form>
                </div>
            </div>
        @else
            <!-- Info Status Selesai / Riwayat -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    @if($penilaian->status_penilaian === 'approved')
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        <h5 class="fw-bold mt-2">Laporan Disetujui</h5>
                        <p class="text-muted small mb-0">Laporan disetujui oleh Kepala Divisi dan skor SMART telah dikalkulasi.</p>
                    @else
                        <i class="bi bi-exclamation-octagon-fill text-danger fs-1"></i>
                        <h5 class="fw-bold mt-2">Butuh Revisi</h5>
                        <p class="text-muted small mb-2">Laporan dikembalikan kepada pegawai.</p>
                        <div class="bg-light p-2 rounded text-start small border">
                            <strong>Catatan:</strong><br>
                            <em>"{{ $penilaian->catatan_revisi }}"</em>
                        </div>
                    @endif
                    <a href="{{ route('manajer.penilaian.index') }}" class="btn btn-sm btn-outline-secondary w-100 mt-3">Kembali</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection