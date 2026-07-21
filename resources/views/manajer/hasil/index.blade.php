@extends('layouts.app')
@section('title','Hasil Divisi')
@section('content')
<form method="get" class="mb-3 d-flex gap-2 align-items-center">
    <label>Periode:</label>
    <select name="periode" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        @foreach($periode as $p)<option value="{{ $p->id }}" {{ $idPeriode == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>@endforeach
    </select>
</form>
<div class="card"><div class="card-body">
<div class="table-responsive"><table class="table table-sm table-hover align-middle">
    <thead class="table-light"><tr><th>Rank</th><th>NIK</th><th>Nama</th><th>Skor SMART</th><th>Kategori</th><th>Rekomendasi</th></tr></thead>
    <tbody>
    @forelse($hasil as $h)
        <tr>
            <td><span class="badge text-bg-warning">#{{ $h->rangking }}</span></td>
            <td>{{ $h->penilaian->pegawai->nik }}</td><td>{{ $h->penilaian->pegawai->nama }}</td>
            <td><strong>{{ $h->nilai_smart }}</strong></td>
            <td>@php $c=['Sangat Baik'=>'success','Baik'=>'primary','Cukup'=>'warning','Kurang'=>'danger'][$h->kategori] ?? 'secondary'; @endphp<span class="badge text-bg-{{ $c }}">{{ $h->kategori }}</span></td>
            <td><small>{{ $h->rekomendasi }}</small></td>
        </tr>
    @empty <tr><td colspan="6" class="text-center text-muted py-3">Belum ada hasil. Admin perlu menjalankan Proses SMART.</td></tr>@endforelse
    </tbody>
</table></div>
</div></div>
{{ $hasil->links() }}
@endsection