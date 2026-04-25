<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Status Pendaftaran - Klub Renang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #2980b9 0%, #3498db 100%); min-height: 100vh; }
        .card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
    </style>
</head>
<body class="d-flex align-items-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <i class="fas fa-search text-white fa-2x mb-2"></i>
                <h4 class="text-white fw-bold">Cek Status Pendaftaran</h4>
            </div>

            <div class="card p-4">
                <form method="GET" action="{{ route('pendaftaran.cek-status') }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email yang didaftarkan</label>
                        <div class="input-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ request('email') }}" placeholder="email@contoh.com" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cek
                            </button>
                        </div>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </form>

                @if(request()->filled('email'))
                    <hr>
                    @if($pendaftaran)
                        <div class="text-center mb-3">
                            <span class="badge bg-{{ $pendaftaran->status_badge }} fs-6 px-3 py-2">
                                {{ $pendaftaran->status_label }}
                            </span>
                        </div>

                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted" width="40%">Nama</td>
                                <td><strong>{{ $pendaftaran->nama }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Daftar</td>
                                <td>{{ formatTanggal($pendaftaran->created_at) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kelas Pilihan</td>
                                <td>{{ $pendaftaran->kelas->nama ?? 'Belum ditentukan' }}</td>
                            </tr>
                            @if($pendaftaran->diproses_pada)
                            <tr>
                                <td class="text-muted">Diproses Pada</td>
                                <td>{{ formatTanggal($pendaftaran->diproses_pada) }}</td>
                            </tr>
                            @endif
                        </table>

                        @if($pendaftaran->status === 'menunggu')
                            <div class="alert alert-warning py-2 mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Pendaftaran Anda sedang dalam proses review. Harap bersabar.
                            </div>
                        @elseif($pendaftaran->status === 'disetujui')
                            <div class="alert alert-success py-2 mb-2">
                                <i class="fas fa-check-circle me-2"></i>
                                Selamat! Pendaftaran Anda telah disetujui. Silakan login.
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-success w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> Login Sekarang
                            </a>
                        @elseif($pendaftaran->status === 'ditolak')
                            <div class="alert alert-danger py-2 mb-0">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>Pendaftaran ditolak.</strong>
                                @if($pendaftaran->catatan_admin)
                                    <br><small>Alasan: {{ $pendaftaran->catatan_admin }}</small>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Email <strong>{{ request('email') }}</strong> tidak ditemukan dalam data pendaftaran.
                        </div>
                    @endif
                @endif
            </div>

            <div class="text-center mt-3 d-flex justify-content-center gap-3">
                <a href="{{ route('pendaftaran.create') }}" class="text-white-50 small">
                    <i class="fas fa-user-plus me-1"></i> Daftar Baru
                </a>
                <a href="{{ route('login') }}" class="text-white-50 small">
                    <i class="fas fa-sign-in-alt me-1"></i> Login
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
