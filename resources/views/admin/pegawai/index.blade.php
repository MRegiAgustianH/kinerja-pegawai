@extends('layouts.app')
@section('title','Data Pegawai')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <form method="get" class="d-flex gap-2"><input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama/NIK..."><button class="btn btn-sm btn-outline-secondary">Cari</button></form>
        <a href="{{ route('admin.pegawai.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah</a>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>NIK</th><th>Nama</th><th>Jabatan</th><th>Divisi</th><th>Status</th><th>Pegawai</th><th></th></tr></thead>
            <tbody>
            @forelse($pegawai as $p)
                <tr>
                    <td>{{ $p->nik }}</td><td>{{ $p->nama }}</td><td>{{ $p->jabatan }}</td><td>{{ $p->divisi->nama_divisi }}</td>
                    <td><span class="badge text-bg-{{ $p->status === 'Aktif' ? 'success' : 'secondary' }}">{{ $p->status }}</span></td>
                    <td>{{ $p->status_pegawai }}</td>
                    <td class="text-end"><a href="{{ route('admin.pegawai.edit', $p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form method="post" action="{{ route('admin.pegawai.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Hapus pegawai?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td>
                </tr>
            @empty <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data.</td></tr>@endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $pegawai->links() }}
@endsection