<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Klub Renang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #c0392b 0%, #e74c3c 50%, #c0392b 100%); min-height: 100vh; }
        .card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
        .btn-login { background: linear-gradient(135deg, #c0392b, #e74c3c); border: none; }
        .btn-login:hover { background: linear-gradient(135deg, #a93226, #c0392b); }
        .divider { display: flex; align-items: center; gap: 12px; color: #aaa; font-size: 13px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e0e0e0; }
    </style>
</head>
<body class="d-flex align-items-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            {{-- Brand --}}
            <div class="text-center mb-4">
                <i class="fas fa-water text-white fa-3x mb-2"></i>
                <h4 class="text-white fw-bold mb-0">Sistem Klub Renang</h4>
                <p class="text-white-50 small">Masuk ke akun Anda</p>
            </div>

            <div class="card p-4">

                {{-- Session status --}}
                @if(session('status'))
                    <div class="alert alert-success py-2 mb-3">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                            <input id="email" type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="email@contoh.com"
                                required autofocus autocomplete="username">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                            <input id="password" type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Password" required autocomplete="current-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Ingat saya</label>
                        </div>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-danger">Lupa password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-login btn-danger w-100 fw-semibold py-2">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </button>
                </form>

                <div class="divider my-4">atau</div>

                {{-- Daftar baru --}}
                <div class="text-center">
                    <p class="text-muted small mb-2">Belum terdaftar sebagai siswa?</p>
                    <a href="{{ route('pendaftaran.create') }}" class="btn btn-outline-danger w-100">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sebagai Siswa Baru
                    </a>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('pendaftaran.cek-status') }}" class="text-muted small">
                        <i class="fas fa-search me-1"></i> Cek status pendaftaran
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
