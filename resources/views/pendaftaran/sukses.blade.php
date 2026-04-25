<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Berhasil - Klub Renang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); min-height: 100vh; }
        .card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .icon-circle { width: 80px; height: 80px; border-radius: 50%; background: #27ae60; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
    </style>
</head>
<body class="d-flex align-items-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center p-5">
                <div class="icon-circle mb-4">
                    <i class="fas fa-check fa-2x text-white"></i>
                </div>
                <h3 class="fw-bold text-success mb-2">Pendaftaran Terkirim!</h3>
                <p class="text-muted mb-4">
                    Formulir pendaftaran Anda telah berhasil dikirim dan sedang menunggu review dari admin.
                    Proses review biasanya memakan waktu <strong>1-3 hari kerja</strong>.
                </p>

                <div class="alert alert-info text-start">
                    <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Langkah Selanjutnya:</h6>
                    <ol class="mb-0 ps-3">
                        <li>Admin akan mereview data pendaftaran Anda</li>
                        <li>Jika disetujui, akun login Anda akan aktif</li>
                        <li>Anda bisa login menggunakan email & password yang didaftarkan</li>
                        <li>Cek status pendaftaran kapan saja melalui link di bawah</li>
                    </ol>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('pendaftaran.cek-status') }}" class="btn btn-success">
                        <i class="fas fa-search me-2"></i> Cek Status Pendaftaran
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-sign-in-alt me-2"></i> Ke Halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
