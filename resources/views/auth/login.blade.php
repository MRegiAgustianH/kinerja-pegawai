<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SPK SMART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>body{background:linear-gradient(135deg,#1e293b,#0f172a);height:100vh;display:flex;align-items:center}</style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h4 class="text-center mb-1 fw-bold text-dark">SPK SMART</h4>
                    <p class="text-center text-muted small mb-4">Penilaian Kinerja Pegawai<br>PT Alika Jaya Perkasa</p>
                    @if($errors->any())<div class="alert alert-danger py-2 small">{{ implode(' ', $errors->all()) }}</div>@endif
                    <form method="post" action="{{ route('login') }}">@csrf
                        <div class="mb-3">
                            <label class="form-label text-dark fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordInput" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 py-2 mt-2 fw-semibold">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('bi-eye');
        eyeIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('bi-eye-slash');
        eyeIcon.classList.add('bi-eye');
    }
});
</script>
</body>
</html>