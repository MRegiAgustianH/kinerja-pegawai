@extends('layouts.app')
@section('title','Dashboard Pimpinan')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="card text-bg-primary"><div class="card-body"><div class="small">Total Pegawai</div><h3>{{ $jumlahPegawai }}</h3></div></div></div>
    <div class="col-md-6"><div class="card text-bg-info"><div class="card-body"><div class="small">Periode Aktif</div><h3>{{ $periodeAktif->count() }}</h3></div></div></div>
</div>
<div class="card"><div class="card-header d-flex justify-content-between align-items-center">
    <span>Top 5 Pegawai (Periode Aktif)</span>
    <a href="{{ route('pimpinan.hasil.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
</div><div class="card-body">
    @forelse($terbaik as $h)
        <div class="d-flex justify-content-between border-bottom py-2">
            <span><span class="badge text-bg-warning">#{{ $h->rangking }}</span> {{ $h->penilaian->pegawai->nama }} <small class="text-muted">{{ $h->penilaian->pegawai->divisi->nama_divisi }}</small></span>
            <span><strong>{{ $h->nilai_smart }}</strong> · <span class="badge text-bg-secondary">{{ $h->kategori }}</span></span>
        </div>
    @empty <p class="text-muted">Belum ada hasil penilaian.</p>@endforelse
</div></div>
@endsection