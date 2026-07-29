@extends('layouts.app')
@section('title', 'Kinerja Saya')
@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-1">Selamat Datang, {{ $pegawai->nama }}</h5>
        <p class="text-muted mb-0">Divisi: {{ $pegawai->divisi->nama_divisi }} | Jabatan: {{ $pegawai->jabatan }}</p>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="get" class="d-flex gap-2 align-items-center bg-white p-2 rounded shadow-sm">
        <label class="fw-bold mb-0">Pilih Periode:</label>
        <select name="periode" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
            @foreach($periode as $p)
                <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
            @endforeach
        </select>
    </form>

    @if($penilaian)
        @php
            $badgeColor = [
                'draft' => 'secondary',
                'pending' => 'warning',
                'approved' => 'success',
                'rejected' => 'danger'
            ][$penilaian->status_penilaian] ?? 'light';
        @endphp
        <div>
            <span class="badge text-bg-{{ $badgeColor }} px-3 py-2 text-uppercase fs-6">
                Status Laporan: {{ $penilaian->status_penilaian }}
            </span>
        </div>
    @else
        <div>
            <span class="badge text-bg-light px-3 py-2 text-uppercase fs-6">Belum Mengisi Laporan</span>
        </div>
    @endif
</div>

@if($penilaian && $penilaian->status_penilaian === 'rejected')
    <div class="alert alert-danger mb-4">
        <h6 class="fw-bold"><i class="bi bi-exclamation-octagon-fill me-2"></i>Laporan Ditolak / Butuh Revisi:</h6>
        <p class="mb-0 italic">"{{ $penilaian->catatan_revisi }}"</p>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 fw-bold">
        <i class="bi bi-card-list me-2 text-primary"></i> Target KPI & Pengisian Realisasi
    </div>
    <div class="card-body">
        @if(!$selectedPeriodeId)
            <div class="text-center text-muted py-4">Belum ada periode penilaian aktif.</div>
        @else
            <form method="post" action="{{ route('pegawai.kinerja.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_periode" value="{{ $selectedPeriodeId }}">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">Kode</th>
                                <th>Kriteria & Sasaran Kerja</th>
                                <th style="width: 100px;">Bobot</th>
                                <th style="width: 120px;">Tipe</th>
                                <th>Target</th>
                                <th style="width: 180px;">Realisasi</th>
                                <th style="width: 250px;">Dokumen Bukti (PDF)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($kriteria as $k)
                            @php
                                $detail = $penilaian ? $penilaian->detailPenilaian->firstWhere('id_kriteria', $k->id) : null;
                                $realVal = $detail ? $detail->realisasi : '';
                                $pdfPath = $detail ? $detail->bukti_pdf : '';
                                $isLocked = $penilaian && in_array($penilaian->status_penilaian, ['pending', 'approved']);
                            @endphp
                            <tr>
                                <td><span class="badge bg-secondary">{{ $k->kode_kriteria }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $k->nama_kriteria }}</div>
                                </td>
                                <td><span class="fw-bold">{{ $k->bobot }}%</span></td>
                                <td>
                                    <span class="badge text-bg-{{ $k->tipe === 'kuantitatif' ? 'primary' : 'info' }}">
                                        {{ $k->tipe }}
                                    </span>
                                </td>
                                <td>{{ $k->target ?: '-' }}</td>
                                <td>
                                    @if($k->tipe === 'kuantitatif')
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" name="realisasi[{{ $k->id }}]" 
                                                value="{{ old('realisasi.' . $k->id, $realVal) }}"
                                                class="form-control" 
                                                placeholder="Capaian"
                                                {{ $isLocked ? 'disabled' : 'required' }}>
                                            @if($k->satuan)
                                                <span class="input-group-text small">{{ $k->satuan }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">Dinilai Kepala Divisi</span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->tipe === 'kuantitatif')
                                        @if(!$isLocked)
                                            <input type="file" name="bukti_pdf[{{ $k->id }}]" class="form-control form-control-sm" accept="application/pdf">
                                        @endif
                                        @if($pdfPath)
                                            <div class="mt-1">
                                                <a href="{{ asset($pdfPath) }}" target="_blank" class="btn btn-sm btn-link text-primary p-0">
                                                    <i class="bi bi-file-earmark-pdf-fill me-1 text-danger"></i>Lihat Bukti
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if(!$penilaian || in_array($penilaian->status_penilaian, ['draft', 'rejected']))
                    <div class="mt-4 text-end">
                        <button class="btn btn-primary"><i class="bi bi-send me-1"></i> Submit Laporan Kinerja</button>
                    </div>
                @else
                    <div class="alert alert-info mt-3 text-center">
                        <i class="bi bi-info-circle-fill me-2"></i>Laporan telah dikirim dan dikunci. Anda tidak dapat mengubah data kecuali laporan ditolak/revisi oleh Kepala Divisi.
                    </div>
                @endif
            </form>
        @endif
    </div>
</div>
@endsection