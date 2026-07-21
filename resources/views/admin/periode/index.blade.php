@extends('layouts.app')
@section('title','Periode Penilaian')
@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card"><div class="card-header">Daftar Periode</div>
            <div class="table-responsive"><table class="table table-sm table-hover mb-0 align-middle">
                <thead class="table-light"><tr><th>Nama Periode</th><th>Mulai</th><th>Selesai</th><th>Status</th><th>Aksi</th><th></th></tr></thead>
                <tbody>
                @forelse($periode as $p)
                    <tr>
                        <td>{{ $p->nama_periode }}</td><td>{{ $p->tanggal_mulai->format('d/m/Y') }}</td><td>{{ $p->tanggal_selesai->format('d/m/Y') }}</td>
                        <td><span class="badge text-bg-{{ $p->status=='aktif'?'success':'secondary' }}">{{ $p->status }}</span></td>
                        <td><form method="post" action="{{ route('admin.periode.toggle', $p) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-{{ $p->status=='aktif'?'warning':'success' }}">{{ $p->status=='aktif'?'Tutup':'Buka' }}</button></form>
                            <form method="post" action="{{ route('admin.periode.proses', $p) }}" class="d-inline" onsubmit="return confirm('Jalankan perhitungan SMART untuk periode ini?')">@csrf<button class="btn btn-sm btn-primary"><i class="bi bi-calculator"></i> Proses SMART</button></form></td>
                        <td class="text-end"><form method="post" action="{{ route('admin.periode.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Hapus periode?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td>
                    </tr>
                @empty <tr><td colspan="6" class="text-center text-muted py-3">Belum ada periode.</td></tr>@endforelse
                </tbody>
            </table></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-header">Tambah Periode</div><div class="card-body">
            <form method="post" action="{{ route('admin.periode.store') }}">@csrf
                <div class="mb-2"><label class="form-label">Nama Periode</label><input name="nama_periode" class="form-control" placeholder="Semester 1 2026" required></div>
                <div class="row g-2 mb-3"><div class="col-6"><label class="form-label">Mulai</label><input type="date" name="tanggal_mulai" class="form-control" required></div>
                <div class="col-6"><label class="form-label">Selesai</label><input type="date" name="tanggal_selesai" class="form-control" required></div></div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div></div>
    </div>
</div>
@endsection