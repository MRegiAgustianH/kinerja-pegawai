<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><style>
body{font-family:Helvetica,Arial,sans-serif;font-size:11px;color:#222}
h3{margin:0 0 2px}table{width:100%;border-collapse:collapse;margin-top:10px}th,td{border:1px solid #999;padding:5px 7px;text-align:left}th{background:#1e293b;color:#fff}.center{text-align:center}.right{text-align:right}
.badge{padding:2px 6px;border-radius:3px;color:#fff;font-size:9px}.sb{background:#16a34a}.bk{background:#2563eb}.ck{background:#d97706}.kr{background:#dc2626}
</style></head><body>
<div style="text-align:center;border-bottom:2px solid #1e293b;padding-bottom:8px">
<h3>LAPORAN PENILAIAN KINERJA PEGAWAI</h3>
<p style="margin:0">PT Alika Jaya Perkasa - Metode SMART</p>
@if($periode)<p style="margin:2px 0 0">Periode: {{ $periode->nama_periode }} ({{ $periode->tanggal_mulai->format('d/m/Y') }} - {{ $periode->tanggal_selesai->format('d/m/Y') }})</p>@endif
</div>
<table>
<thead><tr><th class="center">Rank</th><th>NIK</th><th>Nama</th><th>Divisi</th><th class="center">Skor SMART</th><th class="center">Kategori</th><th>Rekomendasi</th></tr></thead>
<tbody>
@foreach($hasil as $h)
@php $b=['Sangat Baik'=>'sb','Baik'=>'bk','Cukup'=>'ck','Kurang'=>'kr'][$h->kategori] ?? ''; @endphp
<tr><td class="center">{{ $h->rangking }}</td><td>{{ $h->penilaian->pegawai->nik }}</td><td>{{ $h->penilaian->pegawai->nama }}</td><td>{{ $h->penilaian->pegawai->divisi->nama_divisi }}</td><td class="center">{{ $h->nilai_smart }}</td><td class="center"><span class="badge {{ $b }}">{{ $h->kategori }}</span></td><td>{{ $h->rekomendasi }}</td></tr>
@endforeach
</tbody>
</table>
<p style="margin-top:14px;text-align:right">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</body></html>