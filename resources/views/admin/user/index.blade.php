@extends('layouts.app')
@section('title','Akun Pengguna')
@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 fw-bold"><i class="bi bi-person-gear me-2 text-primary"></i> Daftar Akun</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light"><tr><th>Nama</th><th>Username</th><th>Role</th><th>Divisi</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $u->nama }}</td>
                            <td>{{ $u->username }}</td>
                            <td><span class="badge text-bg-secondary text-capitalize">{{ $u->role }}</span></td>
                            <td>{{ $u->divisi->nama_divisi ?? '-' }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUser{{ $u->id }}"><i class="bi bi-pencil-square"></i></button>
                                @if($u->id !== auth()->id())
                                    <form method="post" action="{{ route('admin.user.destroy', $u) }}" class="d-inline" data-confirm="Hapus akun?" data-confirm-title="Konfirmasi">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada akun.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $users->links() }}
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 fw-bold"><i class="bi bi-plus-circle me-2 text-success"></i> Tambah Akun</div>
            <div class="card-body">
                <form method="post" action="{{ route('admin.user.store') }}">@csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama</label>
                        <input name="nama" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input name="username" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password <small class="text-muted">(min. 6 karakter)</small></label>
                        <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Role</label>
                        <select name="role" class="form-select form-select-sm" id="roleSelect">
                            <option value="admin">Admin</option>
                            <option value="manajer">Manajer</option>
                            <option value="pimpinan">Pimpinan</option>
                        </select>
                    </div>
                    <div class="mb-3" id="divisiField" style="display:none;">
                        <label class="form-label small fw-bold">Divisi (khusus Manajer)</label>
                        <select name="id_divisi" class="form-select form-select-sm">
                            <option value="">- pilih divisi -</option>
                            @foreach(\App\Models\Divisi::orderBy('nama_divisi')->get() as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary btn-sm w-100"><i class="bi bi-save me-1"></i> Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach($users as $u)
    <div class="modal fade" id="editUser{{ $u->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="{{ route('admin.user.update', $u) }}">@csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label small fw-bold">Nama</label><input name="nama" value="{{ $u->nama }}" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label small fw-bold">Username</label><input name="username" value="{{ $u->username }}" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label small fw-bold">Password <small class="text-muted">(kosongkan jika tetap, min. 6 karakter)</small></label><input type="password" name="password" class="form-control @error('password') is-invalid @enderror"></div>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div class="mb-3"><label class="form-label small fw-bold">Role</label>
                            <select name="role" class="form-select">
                                <option value="admin" {{ $u->role=='admin'?'selected':'' }}>Admin</option>
                                <option value="manajer" {{ $u->role=='manajer'?'selected':'' }}>Manajer</option>
                                <option value="pimpinan" {{ $u->role=='pimpinan'?'selected':'' }}>Pimpinan</option>
                            </select>
                        </div>
                        <div class="mb-3"><label class="form-label small fw-bold">Divisi (khusus Manajer)</label>
                            <select name="id_divisi" class="form-select">
                                <option value="">-</option>
                                @foreach(\App\Models\Divisi::orderBy('nama_divisi')->get() as $d)
                                    <option value="{{ $d->id }}" {{ $u->id_divisi==$d->id?'selected':'' }}>{{ $d->nama_divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
document.getElementById('roleSelect')?.addEventListener('change', function(){
    document.getElementById('divisiField').style.display = this.value === 'manajer' ? '' : 'none';
});
</script>
@endsection