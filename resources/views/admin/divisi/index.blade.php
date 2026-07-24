@extends('layouts.app')
@section('title','Divisi & Kelompok Kerja')
@section('content')
<div class="row g-3">
    <div class="col-md-7">
        <div class="card"><div class="card-header">Daftar Divisi</div>
            <div class="table-responsive"><table class="table table-sm table-hover mb-0 align-middle">
                <thead class="table-light"><tr><th>Divisi</th><th>Kelompok Kerja</th><th>Pegawai</th><th>Kriteria</th><th></th></tr></thead>
                <tbody>
                @forelse($divisi as $d)
                    <tr>
                        <td>{{ $d->nama_divisi }}</td><td><span class="badge text-bg-{{ $d->kelompok_kerja === 'Lapangan' ? 'info' : 'light' }} text-dark">{{ $d->kelompok_kerja }}</span></td>
                        <td>{{ $d->pegawai_count }}</td><td>{{ $d->kriteria_count }}</td>
                        <td class="text-end"><button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#edit{{ $d->id }}">Edit</button>
                            <form method="post" action="{{ route('admin.divisi.destroy', $d) }}" class="d-inline" data-confirm="Hapus divisi?" data-confirm-title="Konfirmasi">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td>
                    </tr>
                    <div class="modal fade" id="edit{{ $d->id }}"><div class="modal-dialog"><div class="modal-content"><div class="modal-body">
                        <form method="post" action="{{ route('admin.divisi.update', $d) }}">@csrf @method('PUT')
                            <label class="form-label">Nama Divisi</label><input name="nama_divisi" value="{{ $d->nama_divisi }}" class="form-control mb-2" required>
                            <label class="form-label">Kelompok Kerja</label><select name="kelompok_kerja" class="form-select mb-3"><option value="Lapangan" {{ $d->kelompok_kerja=='Lapangan'?'selected':'' }}>Lapangan</option><option value="Kantor" {{ $d->kelompok_kerja=='Kantor'?'selected':'' }}>Kantor</option></select>
                            <button class="btn btn-primary">Simpan</button> <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        </form>
                    </div></div></div></div>
                @empty <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data.</td></tr>@endforelse
                </tbody>
            </table></div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card"><div class="card-header">Tambah Divisi</div><div class="card-body">
            <form method="post" action="{{ route('admin.divisi.store') }}">@csrf
                <div class="mb-2"><label class="form-label">Nama Divisi</label><input name="nama_divisi" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Kelompok Kerja</label><select name="kelompok_kerja" class="form-select"><option value="Lapangan">Lapangan</option><option value="Kantor">Kantor</option></select></div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div></div>
    </div>
</div>
@endsection