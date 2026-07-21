@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card text-bg-primary"><div class="card-body"><div class="small">Total Pegawai</div><h3>{{ $jumlahPegawai }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-bg-success"><div class="card-body"><div class="small">Total Divisi</div><h3>{{ $jumlahDivisi }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-bg-warning"><div class="card-body"><div class="small">Total Periode</div><h3>{{ $jumlahPeriode }}</h3></div></div></div>
    <div class="col-md-3"><div class="card text-bg-info"><div class="card-body"><div class="small">Periode Aktif</div><h3>{{ $periodeAktif->count() }}</h3></div></div></div>
</div>
<div class="card"><div class="card-body">
    <h6>Periode Aktif</h6>
    @forelse($periodeAktif as $p)
        <span class="badge text-bg-light text-dark me-2">{{ $p->nama_periode }} ({{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }})</span>
    @empty <p class="text-muted small mb-0">Belum ada periode aktif.</p>@endforelse
</div></div>
@endsection