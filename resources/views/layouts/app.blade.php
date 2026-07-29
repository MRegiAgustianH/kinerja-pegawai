<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Penilaian Kinerja Pegawai') - PT Alika Jaya Perkasa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f5f6f8; }
        .sidebar { min-height:100vh; background:#1e293b; }
        .sidebar a { color:#cbd5e1; text-decoration:none; padding:.6rem 1rem; display:block; border-radius:.4rem; }
        .sidebar a:hover, .sidebar a.active { background:#334155; color:#fff; }
        .sidebar .brand { color:#fff; font-weight:700; padding:1rem; font-size:1.05rem; border-bottom:1px solid #334155; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar py-3">
            <div class="brand">SPK SMART<br><small class="text-secondary">PT Alika Jaya Perkasa</small></div>
            <div class="px-2 mt-2 text-secondary small text-uppercase">Menu</div>
            @if(auth()->user()->role === 'admin')
                <div class="px-2 mt-1"><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a></div>
                <div class="px-2"><a href="{{ route('admin.pegawai.index') }}" class="{{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Pegawai</a></div>
                <div class="px-2"><a href="{{ route('admin.divisi.index') }}" class="{{ request()->routeIs('admin.divisi.*') ? 'active' : '' }}"><i class="bi bi-diagram-3"></i> Divisi</a></div>
                <div class="px-2"><a href="{{ route('admin.kriteria.index') }}" class="{{ request()->routeIs('admin.kriteria.*') ? 'active' : '' }}"><i class="bi bi-list-check"></i> Kriteria & Bobot</a></div>
                <div class="px-2"><a href="{{ route('admin.kehadiran.index') }}" class="{{ request()->routeIs('admin.kehadiran.*') ? 'active' : '' }}"><i class="bi bi-calendar-check"></i> Kehadiran</a></div>
                <div class="px-2"><a href="{{ route('admin.periode.index') }}" class="{{ request()->routeIs('admin.periode.*') ? 'active' : '' }}"><i class="bi bi-calendar3"></i> Periode</a></div>
                <div class="px-2"><a href="{{ route('admin.user.index') }}" class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}"><i class="bi bi-person-gear"></i> Akun Pengguna</a></div>
                <div class="px-2"><a href="{{ route('admin.hasil.index') }}" class="{{ request()->routeIs('admin.hasil.*') ? 'active' : '' }}"><i class="bi bi-trophy"></i> Hasil Penilaian</a></div>
            @elseif(auth()->user()->role === 'kadiv')
                <div class="px-2 mt-1"><a href="{{ route('manajer.dashboard') }}" class="{{ request()->routeIs('manajer.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a></div>
                <div class="px-2"><a href="{{ route('manajer.penilaian.index') }}" class="{{ request()->routeIs('manajer.penilaian.*') ? 'active' : '' }}"><i class="bi bi-clipboard-check"></i> Input Penilaian</a></div>
                <div class="px-2"><a href="{{ route('manajer.hasil.index') }}" class="{{ request()->routeIs('manajer.hasil.*') ? 'active' : '' }}"><i class="bi bi-trophy"></i> Hasil Divisi</a></div>
            @else
                <div class="px-2 mt-1"><a href="{{ route('pimpinan.dashboard') }}" class="{{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a></div>
                <div class="px-2"><a href="{{ route('pimpinan.hasil.index') }}" class="{{ request()->routeIs('pimpinan.hasil.*') ? 'active' : '' }}"><i class="bi bi-trophy"></i> Hasil Penilaian</a></div>
            @endif
        </nav>
        <main class="col-md-10 ms-sm-auto px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge text-bg-secondary text-capitalize">{{ auth()->user()->role }}</span>
                    <span class="text-muted small">{{ auth()->user()->nama }}</span>
                    <form method="post" action="{{ route('logout') }}">@csrf <button class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Keluar</button></form>
                </div>
            </div>
            @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @if(session('warning'))<div class="alert alert-warning alert-dismissible fade show">{{ session('warning') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<!-- Modal Konfirmasi Global -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i><span id="confirmTitle">Konfirmasi</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body"><span id="confirmMessage">Apakah Anda yakin?</span></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmOkBtn"><i class="bi bi-check-lg me-1"></i> Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>
<script>
(function(){
    let activeForm = null;
    const modalEl = document.getElementById('confirmModal');
    const bsModal = new bootstrap.Modal(modalEl);
    document.addEventListener('submit', function(e){
        const form = e.target;
        if (form.dataset.confirm && !form.dataset.confirmed) {
            e.preventDefault();
            activeForm = form;
            document.getElementById('confirmTitle').textContent = form.dataset.confirmTitle || 'Konfirmasi';
            document.getElementById('confirmMessage').textContent = form.dataset.confirm;
            bsModal.show();
        }
    });
    document.getElementById('confirmOkBtn').addEventListener('click', function(){
        if (activeForm) {
            activeForm.dataset.confirmed = '1';
            bsModal.hide();
            activeForm.submit();
            activeForm.dataset.confirmed = '';
        }
    });
})();
</script></body>
</html>