@extends('layouts.app')
@section('title','Akun Pengguna')
@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card"><div class="card-header">Daftar Akun</div>
            <div class="table-responsive"><table class="table table-sm table-hover mb-0 align-middle">
                <thead class="table-light"><tr><th>Nama</th><th>Username</th><th>Role</th><th>Divisi</th><th></th></tr></thead>
                <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>{{ $u->nama }}</td><td>{{ $u->username }}</td><td><span class="badge text-bg-secondary text-capitalize">{{ $u->role }}</span></td>
                        <td>{{ $u->divisi->nama_divisi ?? '-' }}</td>
                        <td class="text-end"><button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#u{{ $u->id }}">Edit</button>
                            @if($u->id !== auth()->id())<form method="post" action="{{ route('admin.user.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Hapus akun?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>@endif</td>
                    </tr>
                    <div class="modal fade" id="u{{ $u->id }}"><div class="modal-dialog"><div class="modal-body modal-content p-3">
                        <form method="post" action="{{ route('admin.user.update', $u) }}">@csrf @method('PUT')
                            <div class="mb-2"><label class="form-label">Nama</label><input name="nama" value="{{ $u->nama }}" class="form-control" required></div>
                            <div class="mb-2"><label class="form-label">Username</label><input name="username" value="{{ $u->username }}" class="form-control" required></div>
                            <div class="mb-2"><label class="form-label">Password (kosongkan jika tetap)</label><input type="password" name="password" class="form-control"></div>
                            <div class="mb-2"><label class="form-label">Role</label><select name="role" class="form-select" id="role{{ $u->id }}"><option value="admin" {{ $u->role=='admin'?'selected':'' }}>admin</option><option value="manajer" {{ $u->role=='manajer'?'selected':'' }}>manajer</option><option value="pimpinan" {{ $u->role=='pimpinan'?'selected':'' }}>pimpinan</option></select></div>
                            <div class="mb-3"><label class="form-label">Divisi (khusus manajer)</label><select name="id_divisi" class="form-select"><option value="">-</option>@foreach(\App\Models\Divisi::orderBy('nama_divisi')->get() as $d)<option value="{{ $d->id }}" {{ $u->id_divisi==$d->id?'selected':'' }}>{{ $d->nama_divisi }}</option>@endforeach</select></div>
                            <button class="btn btn-primary">Simpan</button> <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        </form>
                    </div></div></div>
                @empty <tr><td colspan="5" class="text-center text-muted py-3">Belum ada akun.</td></tr>@endforelse
                </tbody>
            </table></div>
        </div>
        {{ $users->links() }}
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-header">Tambah Akun</div><div class="card-body">
            <form method="post" action="{{ route('admin.user.store') }}">@csrf
                <div class="mb-2"><label class="form-label">Nama</label><input name="nama" class="form-control" required></div>
                <div class="mb-2"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
                <div class="mb-2"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                <div class="mb-2"><label class="form-label">Role</label><select name="role" class="form-select"><option value="admin">admin</option><option value="manajer">manajer</option><option value="pimpinan">pimpinan</option></select></div>
                <div class="mb-3"><label class="form-label">Divisi (khusus manajer)</label><select name="id_divisi" class="form-select"><option value="">-</option>@foreach(\App\Models\Divisi::orderBy('nama_divisi')->get() as $d)<option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>@endforeach</select></div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div></div>
    </div>
</div>
@endsection