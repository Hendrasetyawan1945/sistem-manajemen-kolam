<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Siswa - Klub Renang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #c0392b 0%, #e74c3c 50%, #f39c12 100%); min-height: 100vh; }
        .card { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .card-header { background: linear-gradient(135deg, #c0392b, #e74c3c); border-radius: 16px 16px 0 0 !important; }
        .step-badge { width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; }
        .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #c0392b; border-bottom: 2px solid #e74c3c; padding-bottom: 6px; margin-bottom: 16px; }
    </style>
</head>
<body class="py-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Header --}}
            <div class="text-center mb-4">
                <i class="fas fa-water text-white fa-3x mb-2"></i>
                <h3 class="text-white fw-bold mb-1">Klub Renang</h3>
                <p class="text-white-50">Formulir Pendaftaran Siswa Baru</p>
            </div>

            {{-- Alur pendaftaran --}}
            <div class="card mb-4">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                        <div class="text-center">
                            <div class="step-badge bg-danger text-white mb-1">1</div>
                            <div style="font-size:12px" class="fw-semibold">Isi Formulir</div>
                        </div>
                        <i class="fas fa-arrow-right text-muted"></i>
                        <div class="text-center">
                            <div class="step-badge bg-warning text-dark mb-1">2</div>
                            <div style="font-size:12px" class="fw-semibold">Menunggu Review</div>
                        </div>
                        <i class="fas fa-arrow-right text-muted"></i>
                        <div class="text-center">
                            <div class="step-badge bg-success text-white mb-1">3</div>
                            <div style="font-size:12px" class="fw-semibold">Disetujui Admin</div>
                        </div>
                        <i class="fas fa-arrow-right text-muted"></i>
                        <div class="text-center">
                            <div class="step-badge bg-primary text-white mb-1">4</div>
                            <div style="font-size:12px" class="fw-semibold">Login & Latihan</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="text-white mb-0 fw-bold">
                        <i class="fas fa-user-plus me-2"></i>Formulir Pendaftaran
                    </h5>
                </div>
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terdapat kesalahan:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pendaftaran.store') }}" method="POST">
                        @csrf

                        {{-- Data Siswa --}}
                        <div class="section-title"><i class="fas fa-child me-2"></i>Data Siswa</div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama') }}" placeholder="Nama lengkap siswa" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                    <option value="">Pilih</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" required>
                                @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-7 mb-3">
                                <label class="form-label fw-semibold">Kelas yang Diminati</label>
                                <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror">
                                    <option value="">-- Belum tahu / pilih nanti --</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama }}
                                            @if($kelas->jadwal) — {{ $kelas->jadwal }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Admin akan menentukan kelas final saat menyetujui pendaftaran</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror"
                                placeholder="Jl. ..." required>{{ old('alamat') }}</textarea>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Data Orang Tua --}}
                        <div class="section-title mt-4"><i class="fas fa-users me-2"></i>Data Orang Tua / Wali</div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nama Orang Tua / Wali <span class="text-danger">*</span></label>
                                <input type="text" name="nama_ortu" class="form-control @error('nama_ortu') is-invalid @enderror"
                                    value="{{ old('nama_ortu') }}" placeholder="Nama lengkap orang tua" required>
                                @error('nama_ortu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" name="telepon_ortu" class="form-control @error('telepon_ortu') is-invalid @enderror"
                                    value="{{ old('telepon_ortu') }}" placeholder="08xxxxxxxxxx" required>
                                @error('telepon_ortu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Orang Tua</label>
                            <input type="email" name="email_ortu" class="form-control @error('email_ortu') is-invalid @enderror"
                                value="{{ old('email_ortu') }}" placeholder="email@contoh.com (opsional)">
                            @error('email_ortu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Akun Login --}}
                        <div class="section-title mt-4"><i class="fas fa-lock me-2"></i>Akun Login Siswa</div>
                        <div class="alert alert-info py-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Email dan password ini akan digunakan siswa untuk login setelah pendaftaran disetujui.
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="email@contoh.com" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('login') }}" class="text-muted small">
                                <i class="fas fa-arrow-left me-1"></i> Sudah punya akun? Login
                            </a>
                            <button type="submit" class="btn btn-danger btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('pendaftaran.cek-status') }}" class="text-white-50 small">
                    <i class="fas fa-search me-1"></i> Cek status pendaftaran saya
                </a>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
