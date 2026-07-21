<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SPK SMART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:linear-gradient(135deg,#1e293b,#0f172a);height:100vh;display:flex;align-items:center}</style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h4 class="text-center mb-1">SPK SMART</h4>
                    <p class="text-center text-muted small mb-4">Penilaian Kinerja Pegawai<br>PT Alika Jaya Perkasa</p>
                    @if($errors->any())<div class="alert alert-danger py-2 small">{{ implode(' ', $errors->all()) }}</div>@endif
                    <form method="post" action="{{ route('login') }}">@csrf
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100">Masuk</button>
                    </form>
                    <div class="mt-4 small text-muted">
                        <p class="mb-1"><strong>Akun demo:</strong></p>
                        <code>admin / admin123</code> · <code>manajer / manajer123</code> · <code>pimpinan / pimpinan123</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>