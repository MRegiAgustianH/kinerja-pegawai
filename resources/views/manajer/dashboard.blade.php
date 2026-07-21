@extends('layouts.app')
@section('title','Dashboard Manajer')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-6"><div class="card text-bg-primary"><div class="card-body"><div class="small">Pegawai di Divisi Anda</div><h3>{{ $pegawai }}</h3></div></div></div>
    <div class="col-md-6"><div class="card text-bg-success"><div class="card-body"><div class="small">Periode Aktif</div><h3>{{ $periodeAktif->count() }}</h3></div></div></div>
</div>
<div class="card"><div class="card-body">
    <a href="{{ route('manajer.penilaian.index') }}" class="btn btn-primary"><i class="bi bi-clipboard-check"></i> Input Penilaian Kinerja</a>
    <a href="{{ route('manajer.hasil.index') }}" class="btn btn-outline-secondary"><i class="bi bi-trophy"></i> Lihat Hasil Divisi</a>
</div></div>
@endsection