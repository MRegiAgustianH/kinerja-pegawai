@extends('layouts.app')
@section('title','Data Kehadiran')
@section('content')
<form method="get" class="mb-3 d-flex gap-2 align-items-center">
    <label>Periode:</label>
    <select name="periode" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        @foreach($periode as $p)<option value="{{ $p->id }}" {{ $idPeriode == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>@endforeach
    </select>
</form>
<div class="row g-3">
    <div class="col-md-8">
        <div class="card"><div class="card-header">Rekap Kehadiran</div>
            <div class="table-responsive"><table class="table table-sm table-hover mb-0 align-middle">
                <thead class="table-light"><tr><th>NIK</th><th>Nama</th><th>Hari Kerja</th><th>Hadir</th><th>Terlambat</th><th>% Hadir</th><th></th></tr></thead>
                <tbody>
                @forelse($kehadiran as $k)
                    <tr>
                        <td>{{ $k->pegawai->nik }}</td><td>{{ $k->pegawai->nama }}</td><td>{{ $k->hari_kerja }}</td><td>{{ $k->hari_hadir }}</td><td>{{ $k->hari_terlambat }}</td>
                        <td>{{ $k->hari_kerja ? round($k->hari_hadir / $k->hari_kerja * 100) : 0 }}%</td>
                        <td class="text-end"><form method="post" action="{{ route('admin.kehadiran.destroy', $k) }}" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td>
                    </tr>
                @empty <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data.</td></tr>@endforelse
                </tbody>
            </table></div>
        </div>
        {{ $kehadiran->links() }}
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-header">Input Kehadiran</div><div class="card-body">
            <form method="post" action="{{ route('admin.kehadiran.store') }}">@csrf
                <input type="hidden" name="id_periode" value="{{ $idPeriode }}">
                <div class="mb-2"><label class="form-label">Pegawai</label><select name="id_pegawai" class="form-select" required><option value="">- pilih -</option>@foreach(\App\Models\Pegawai::orderBy('nama')->get() as $pg)<option value="{{ $pg->id }}">{{ $pg->nik }} - {{ $pg->nama }}</option>@endforeach</select></div>
                <div class="row g-2 mb-2"><div class="col-4"><label class="form-label">Hari Kerja</label><input type="number" name="hari_kerja" value="240" class="form-control" required></div>
                <div class="col-4"><label class="form-label">Hadir</label><input type="number" name="hari_hadir" class="form-control" required></div>
                <div class="col-4"><label class="form-label">Terlambat</label><input type="number" name="hari_terlambat" value="0" class="form-control" required></div></div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div></div>
    </div>
</div>
@endsection