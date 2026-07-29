@extends('layouts.app')
@section('title', 'Verifikasi Laporan Kinerja')
@section('content')
<form method="get" class="mb-3 d-flex gap-2 align-items-center bg-white p-2 rounded shadow-sm w-auto d-inline-flex">
    <label class="fw-bold mb-0">Periode:</label>
    <select name="periode" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        @foreach($periode as $p)
            <option value="{{ $p->id }}" {{ $idPeriode == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
        @endforeach
    </select>
</form>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 fw-bold">
        <i class="bi bi-clipboard-check me-2 text-primary"></i> Daftar Laporan Kinerja Staf
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>NIK</th>
                    <th>Nama Pegawai</th>
                    <th>Jabatan</th>
                    <th>Status Laporan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($pegawai as $p)
                @php
                    $pen = $p->penilaian->first();
                    $badgeColor = 'secondary';
                    $statusText = 'Belum Mengisi';
                    if ($pen) {
                        $statusText = $pen->status_penilaian;
                        $badgeColor = [
                            'draft' => 'secondary',
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger'
                        ][$statusText] ?? 'light';
                    }
                @endphp
                <tr>
                    <td>{{ $p->nik }}</td>
                    <td><div class="fw-bold">{{ $p->nama }}</div></td>
                    <td>{{ $p->jabatan }}</td>
                    <td><span class="badge text-bg-{{ $badgeColor }} text-uppercase">{{ $statusText }}</span></td>
                    <td class="text-end">
                        @if($pen && $pen->status_penilaian === 'pending')
                            <a href="{{ route('manajer.penilaian.detail', $pen) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-shield-check me-1"></i> Verifikasi & Nilai
                            </a>
                        @elseif($pen && in_array($pen->status_penilaian, ['approved', 'rejected']))
                            <a href="{{ route('manajer.penilaian.detail', $pen) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye me-1"></i> Lihat Detail
                            </a>
                        @else
                            <button class="btn btn-sm btn-light" disabled>Menunggu Staf</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data pegawai di divisi Anda.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $pegawai->links() }}
@endsection