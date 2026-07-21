@extends('layouts.app')
@section('title','Penilaian: ' . $pegawai->nama)
@section('content')
<div class="card"><div class="card-header">
    <strong>{{ $pegawai->nik }} - {{ $pegawai->nama }}</strong><br>
    <small class="text-muted">{{ $pegawai->jabatan }} · Periode: {{ $periode->nama_periode }}</small>
</div><div class="card-body">
<form method="post" action="{{ route('manajer.penilaian.store', $pegawai) }}">@csrf
    <input type="hidden" name="id_periode" value="{{ $periode->id }}">
    <table class="table table-bordered align-middle">
        <thead class="table-light"><tr><th>Kode</th><th>Kriteria (KPI)</th><th>Bobot</th><th>Target</th><th>Nilai (1-5)</th></tr></thead>
        <tbody>
        @php $existing = $pegawai->penilaian->where('id_periode', $periode->id)->first(); @endphp
        @foreach($kriteria as $k)
            @php $val = $existing?->detailPenilaian->where('id_kriteria', $k->id)->first()?->nilai ?? 3; @endphp
            <tr>
                <td>{{ $k->kode_kriteria }}</td>
                <td>{{ $k->nama_kriteria }}<br><small class="text-muted">{{ $k->subKriteria->pluck('nilai','nama_subkriteria')->map(fn($v,$n)=>"$v=$n")->implode(', ') }}</small></td>
                <td>{{ $k->bobot }}%</td>
                <td><small>{{ $k->target }}</small></td>
                <td><select name="nilai[{{ $k->id }}]" class="form-select form-select-sm w-auto">
                    @for($i=1;$i<=5;$i++)<option value="{{ $i }}" {{ $val==$i?'selected':'' }}>{{ $i }}</option>@endfor
                </select></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <button class="btn btn-primary">Simpan Penilaian</button>
    <a href="{{ route('manajer.penilaian.index') }}" class="btn btn-outline-secondary">Batal</a>
</form>
</div></div>
@endsection