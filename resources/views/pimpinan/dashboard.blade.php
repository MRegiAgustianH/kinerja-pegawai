@extends('layouts.app')
@section('title', 'Dashboard Eksekutif')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-white-50">Total Pegawai</div>
                        <h2 class="fw-bold mb-0">{{ $jumlahPegawai }}</h2>
                    </div>
                    <i class="bi bi-people fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-white-50">Periode Aktif</div>
                        <h2 class="fw-bold mb-0">{{ $periodeAktif->count() }}</h2>
                    </div>
                    <i class="bi bi-calendar-event fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-white-50">Top Staff Score</div>
                        <h2 class="fw-bold mb-0">{{ $terbaik->first()?->nilai_smart ?? '0.00' }}</h2>
                    </div>
                    <i class="bi bi-award fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark"><i class="bi bi-trophy me-2 text-warning"></i> Top 5 Pegawai Terbaik (Periode Aktif)</span>
                <a href="{{ route('pimpinan.hasil.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @forelse($terbaik as $h)
                    @php
                        $badgeColor = [
                            'Sangat Baik' => 'success',
                            'Baik' => 'primary',
                            'Cukup' => 'warning',
                            'Kurang' => 'danger'
                        ][$h->kategori] ?? 'secondary';
                    @endphp
                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                        <div>
                            <span class="badge text-bg-warning me-2">Rank #{{ $h->rangking }}</span>
                            <span class="fw-bold text-dark">{{ $h->penilaian->pegawai->nama }}</span>
                            <div class="text-muted small ps-5" style="font-size: 0.8rem;">Divisi: {{ $h->penilaian->pegawai->divisi->nama_divisi }}</div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-primary fs-5">{{ $h->nilai_smart }}</span><br>
                            <span class="badge text-bg-{{ $badgeColor }} text-uppercase">{{ $h->kategori }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">Belum ada hasil penilaian periode ini.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 fw-bold">
                <i class="bi bi-graph-up-arrow me-2 text-success"></i> Grafik Performa Kategori
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <div style="width: 280px; height: 280px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    @php
        $counts = [
            'Sangat Baik' => $terbaik->where('kategori', 'Sangat Baik')->count(),
            'Baik' => $terbaik->where('kategori', 'Baik')->count(),
            'Cukup' => $terbaik->where('kategori', 'Cukup')->count(),
            'Kurang' => $terbaik->where('kategori', 'Kurang')->count(),
        ];
        // Tambahkan fallback jika kosong agar chart tetap tampil
        if (array_sum($counts) === 0) {
            $counts['Belum Dinilai'] = 1;
        }
    @endphp

    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($counts)) !!},
            datasets: [{
                data: {!! json_encode(array_values($counts)) !!},
                backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#dc3545', '#6c757d'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
@endsection