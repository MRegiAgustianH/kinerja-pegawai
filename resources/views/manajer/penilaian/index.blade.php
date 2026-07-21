@extends('layouts.app')
@section('title','Input Penilaian')
@section('content')
<form method="get" class="mb-3 d-flex gap-2 align-items-center">
    <label>Periode:</label>
    <select name="periode" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        @foreach($periode as $p)<option value="{{ $p->id }}" {{ $idPeriode == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>@endforeach
    </select>
</form>
<div class="card"><div class="card-body">
<div class="table-responsive"><table class="table table-sm table-hover align-middle">
    <thead class="table-light"><tr><th>NIK</th><th>Nama</th><th>Jabatan</th><th>Status Penilaian</th><th></th></tr></thead>
    <tbody>
    @forelse($pegawai as $p)
        <tr>
            <td>{{ $p->nik }}</td><td>{{ $p->nama }}</td><td>{{ $p->jabatan }}</td>
            <td>@php $st = $p->penilaian->first(); @endphp
                @if($st)<span class="badge text-bg-success">{{ $st->status_penilaian }}</span>@else <span class="badge text-bg-secondary">belum</span>@endif
            </td>
            <td><a href="{{ route('manajer.penilaian.create', $p) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Nilai</a></td>
        </tr>
    @empty <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pegawai.</td></tr>@endforelse
    </tbody>
</table></div>
</div></div>
{{ $pegawai->links() }}
@endsection