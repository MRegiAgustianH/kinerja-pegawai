@extends('layouts.app')
@section('title','Hasil Penilaian')
@section('content')
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body py-3">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Periode</label>
                <select name="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($periode as $p)
                        <option value="{{ $p->id }}" {{ $idPeriode == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Kelompok Kerja</label>
                <select name="kelompok_kerja" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="Lapangan" {{ $kelompokKerja === 'Lapangan' ? 'selected' : '' }}>Lapangan</option>
                    <option value="Kantor" {{ $kelompokKerja === 'Kantor' ? 'selected' : '' }}>Kantor</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Divisi</label>
                <select name="divisi" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Divisi</option>
                    @foreach($divisiList as $d)
                        <option value="{{ $d->id }}" {{ $idDivisi == $d->id ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('admin.hasil.pdf', ['periode' => $idPeriode, 'kelompok_kerja' => $kelompokKerja, 'divisi' => $idDivisi]) }}" class="btn btn-sm btn-outline-danger w-100"><i class="bi bi-file-pdf me-1"></i> Cetak PDF</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0"><div class="card-body">
<div class="table-responsive"><table class="table table-sm table-hover align-middle">
    <thead class="table-light"><tr><th>Rank</th><th>NIK</th><th>Nama</th><th>Divisi</th><th>Kelompok</th><th>Skor SMART</th><th>Kategori</th><th>Rekomendasi</th></tr></thead>
    <tbody>
    @forelse($hasil as $h)
        <tr>
            <td><span class="badge text-bg-warning fs-6">#{{ $h->rangking }}</span></td>
            <td>{{ $h->penilaian->pegawai->nik }}</td>
            <td>{{ $h->penilaian->pegawai->nama }}</td>
            <td>{{ $h->penilaian->pegawai->divisi->nama_divisi }}</td>
            <td><span class="badge text-bg-{{ $h->penilaian->pegawai->divisi->kelompok_kerja === 'Lapangan' ? 'info' : 'light' }} text-dark">{{ $h->penilaian->pegawai->divisi->kelompok_kerja }}</span></td>
            <td><strong>{{ $h->nilai_smart }}</strong></td>
            <td>@php $c=['Sangat Baik'=>'success','Baik'=>'primary','Cukup'=>'warning','Kurang'=>'danger'][$h->kategori] ?? 'secondary'; @endphp<span class="badge text-bg-{{ $c }}">{{ $h->kategori }}</span></td>
            <td><small>{{ $h->rekomendasi }}</small></td>
        </tr>
    @empty <tr><td colspan="8" class="text-center text-muted py-3">Belum ada hasil. Jalankan Proses SMART pada periode terkait.</td></tr>@endforelse
    </tbody>
</table></div>
</div></div>
{{ $hasil->links() }}
@endsection