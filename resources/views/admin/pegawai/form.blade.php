@extends('layouts.app')
@section('title', $pegawai ? 'Edit Pegawai' : 'Tambah Pegawai')
@section('content')
<div class="card"><div class="card-body">
<form method="post" action="{{ $pegawai ? route('admin.pegawai.update', $pegawai) : route('admin.pegawai.store') }}">@csrf @method($pegawai ? 'PUT' : 'POST')
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">NIK</label><input name="nik" value="{{ old('nik', $pegawai->nik ?? '') }}" class="form-control @error('nik') is-invalid @enderror" required></div>
        <div class="col-md-8"><label class="form-label">Nama</label><input name="nama" value="{{ old('nama', $pegawai->nama ?? '') }}" class="form-control @error('nama') is-invalid @enderror" required></div>
        <div class="col-md-6"><label class="form-label">Jabatan</label><input name="jabatan" value="{{ old('jabatan', $pegawai->jabatan ?? '') }}" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Divisi</label><select name="id_divisi" class="form-select" required>
            @foreach($divisi as $d)<option value="{{ $d->id }}" {{ (old('id_divisi', $pegawai->id_divisi ?? '') == $d->id) ? 'selected' : '' }}>{{ $d->nama_divisi }} ({{ $d->kelompok_kerja }})</option>@endforeach
        </select></div>
        <div class="col-md-3"><label class="form-label">Jenis Kelamin</label><select name="jenis_kelamin" class="form-select"><option value="L" {{ (old('jenis_kelamin', $pegawai->jenis_kelamin ?? 'L')=='L')?'selected':'' }}>Laki-laki</option><option value="P" {{ (old('jenis_kelamin', $pegawai->jenis_kelamin ?? '')=='P')?'selected':'' }}>Perempuan</option></select></div>
        <div class="col-md-3"><label class="form-label">Tanggal Masuk</label><input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $pegawai->tanggal_masuk?->format('Y-m-d')) }}" class="form-control" required></div>
        <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="Aktif" {{ (old('status', $pegawai->status ?? 'Aktif')=='Aktif')?'selected':'' }}>Aktif</option><option value="Nonaktif" {{ (old('status', $pegawai->status ?? '')=='Nonaktif')?'selected':'' }}>Nonaktif</option></select></div>
        <div class="col-md-3"><label class="form-label">Status Pegawai</label><select name="status_pegawai" class="form-select"><option value="Kontrak" {{ (old('status_pegawai', $pegawai->status_pegawai ?? 'Kontrak')=='Kontrak')?'selected':'' }}>Kontrak</option><option value="Tetap" {{ (old('status_pegawai', $pegawai->status_pegawai ?? '')=='Tetap')?'selected':'' }}>Tetap</option></select></div>
    </div>
    <div class="mt-3"><button class="btn btn-primary">Simpan</button> <a href="{{ route('admin.pegawai.index') }}" class="btn btn-outline-secondary">Batal</a></div>
</form>
</div></div>
@endsection