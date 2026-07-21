@extends('layouts.app')
@section('title','Kriteria & Bobot')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="get" class="d-flex gap-2 align-items-center bg-white p-2 rounded shadow-sm">
        <label class="fw-bold mb-0">Pilih Divisi:</label>
        <select name="divisi" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
            @foreach($divisi as $d)
                <option value="{{ $d->id }}" {{ $selected == $d->id ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
            @endforeach
        </select>
        @php $div = $divisi->firstWhere('id', $selected); @endphp
        @if($div)
            <span class="badge text-bg-{{ abs($div->totalBobot() - 100) < 0.01 ? 'success' : 'danger' }} px-3 py-2">
                Total Bobot: {{ $div->totalBobot() }}% (harus 100%)
            </span>
        @endif
    </form>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 fw-bold">
                <i class="bi bi-list-task me-2 text-primary"></i> Daftar Kriteria
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">Kode</th>
                            <th>Nama Kriteria & Target</th>
                            <th style="width: 100px;">Bobot</th>
                            <th style="width: 100px;">Atribut</th>
                            <th style="width: 120px;">Subkriteria</th>
                            <th style="width: 140px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($kriteria as $k)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $k->kode_kriteria }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $k->nama_kriteria }}</div>
                                <div class="text-muted small" style="font-size: 0.8rem;">Target: {{ $k->target ?: '-' }}</div>
                            </td>
                            <td><span class="fw-bold text-primary">{{ $k->bobot }}%</span></td>
                            <td>
                                <span class="badge text-bg-{{ $k->atribut=='benefit'?'success':'warning' }} text-capitalize">
                                    {{ $k->atribut }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $k->subKriteria->count() }} rubrik</span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editKriteria{{ $k->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#rubrikKriteria{{ $k->id }}">
                                    <i class="bi bi-list-ol"></i>
                                </button>
                                <form method="post" action="{{ route('admin.kriteria.destroy', $k) }}" class="d-inline" onsubmit="return confirm('Hapus kriteria?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada kriteria. Silakan tambahkan melalui form di kanan.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 fw-bold">
                <i class="bi bi-plus-circle me-2 text-success"></i> Tambah Kriteria
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('admin.kriteria.store') }}">
                    @csrf
                    <input type="hidden" name="id_divisi" value="{{ $selected }}">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Kriteria</label>
                        <input name="kode_kriteria" class="form-control form-control-sm" placeholder="Contoh: C1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Kriteria (KPI)</label>
                        <input name="nama_kriteria" class="form-control form-control-sm" placeholder="Contoh: Kualitas Kerja" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Bobot (%)</label>
                            <input type="number" step="0.01" name="bobot" class="form-control form-control-sm" placeholder="Contoh: 15" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Atribut</label>
                            <select name="atribut" class="form-select form-select-sm">
                                <option value="benefit">Benefit</option>
                                <option value="cost">Cost</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Target / Keterangan</label>
                        <textarea name="target" class="form-control form-control-sm" rows="3" placeholder="Contoh: Tidak ada kesalahan mayor"></textarea>
                    </div>
                    <button class="btn btn-primary btn-sm w-100"><i class="bi bi-save me-1"></i> Simpan Kriteria</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODALS SECTION - ditaruh di luar tabel agar HTML tidak rusak --}}
@foreach($kriteria as $k)
    <!-- Modal Edit Kriteria -->
    <div class="modal fade" id="editKriteria{{ $k->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Kriteria {{ $k->kode_kriteria }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('admin.kriteria.update', $k) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Kode Kriteria</label>
                            <input name="kode_kriteria" value="{{ $k->kode_kriteria }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Kriteria</label>
                            <input name="nama_kriteria" value="{{ $k->nama_kriteria }}" class="form-control" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Bobot (%)</label>
                                <input type="number" step="0.01" name="bobot" value="{{ $k->bobot }}" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Atribut</label>
                                <select name="atribut" class="form-select">
                                    <option value="benefit" {{ $k->atribut=='benefit'?'selected':'' }}>Benefit</option>
                                    <option value="cost" {{ $k->atribut=='cost'?'selected':'' }}>Cost</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Target / Keterangan</label>
                            <textarea name="target" class="form-control" rows="3">{{ $k->target }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Kelola Rubrik Subkriteria -->
    <div class="modal fade" id="rubrikKriteria{{ $k->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-list-ol me-2 text-info"></i>Rubrik Subkriteria - {{ $k->kode_kriteria }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold text-dark mb-1">Kriteria:</label>
                        <div>{{ $k->nama_kriteria }}</div>
                    </div>
                    <hr class="my-2">
                    <h6 class="fw-bold mb-2">Daftar Rubrik Penilaian (Skala 1-5):</h6>
                    <div class="list-group mb-3">
                        @forelse($k->subKriteria->sortByDesc('nilai') as $s)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <span class="badge bg-secondary me-2">Skala {{ $s->nilai }}</span>
                                    <span>{{ $s->nama_subkriteria }}</span>
                                </div>
                                <form method="post" action="{{ route('admin.kriteria.sub.destroy', $s) }}" class="d-inline" onsubmit="return confirm('Hapus rubrik ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-x-circle-fill"></i></button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center text-muted py-2 small">Belum ada rubrik subkriteria.</div>
                        @endforelse
                    </div>

                    <form method="post" action="{{ route('admin.kriteria.sub.store') }}" class="bg-light p-3 rounded border">
                        @csrf
                        <input type="hidden" name="id_kriteria" value="{{ $k->id }}">
                        <h6 class="fw-bold small mb-2"><i class="bi bi-plus-circle-fill text-primary"></i> Tambah Rubrik Baru</h6>
                        <div class="row g-2">
                            <div class="col-4">
                                <label class="form-label small fw-bold mb-1">Nilai (1-5)</label>
                                <input type="number" name="nilai" min="1" max="5" class="form-control form-control-sm" placeholder="1-5" required>
                            </div>
                            <div class="col-8">
                                <label class="form-label small fw-bold mb-1">Nama / Deskripsi</label>
                                <input name="nama_subkriteria" class="form-control form-control-sm" placeholder="Contoh: Sangat Baik" required>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-sm w-100 mt-3"><i class="bi bi-plus-lg me-1"></i> Tambah ke Rubrik</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection