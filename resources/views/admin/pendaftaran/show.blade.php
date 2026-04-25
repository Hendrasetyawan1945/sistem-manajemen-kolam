<x-layouts.admin>
    <x-page-header 
        title="Review Pendaftaran"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pendaftaran', 'url' => route('admin.pendaftaran.index')],
            ['title' => $pendaftaran->nama, 'url' => '#']
        ]"
    />

    <x-alert />

    <div class="row">
        {{-- Data Pendaftaran --}}
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Data Pendaftaran</h6>
                    <span class="badge bg-{{ $pendaftaran->status_badge }} fs-6">
                        {{ $pendaftaran->status_label }}
                    </span>
                </div>
                <div class="card-body">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Data Siswa</h6>
                    <table class="table table-borderless table-sm mb-4">
                        <tr>
                            <td class="text-muted" width="35%">Nama Lengkap</td>
                            <td><strong>{{ $pendaftaran->nama }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Lahir</td>
                            <td>{{ formatTanggal($pendaftaran->tanggal_lahir) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis Kelamin</td>
                            <td>{{ $pendaftaran->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alamat</td>
                            <td>{{ $pendaftaran->alamat }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kelas Pilihan</td>
                            <td>{{ $pendaftaran->kelas->nama ?? '<em class="text-muted">Belum dipilih</em>' }}</td>
                        </tr>
                    </table>

                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Data Orang Tua</h6>
                    <table class="table table-borderless table-sm mb-4">
                        <tr>
                            <td class="text-muted" width="35%">Nama Orang Tua</td>
                            <td>{{ $pendaftaran->nama_ortu }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Telepon</td>
                            <td>{{ $pendaftaran->telepon_ortu }}</td>
                        </tr>
                        @if($pendaftaran->email_ortu)
                        <tr>
                            <td class="text-muted">Email Orang Tua</td>
                            <td>{{ $pendaftaran->email_ortu }}</td>
                        </tr>
                        @endif
                    </table>

                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Akun Login</h6>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted" width="35%">Email Login</td>
                            <td><code>{{ $pendaftaran->email }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Password</td>
                            <td><em class="text-muted">Tersimpan terenkripsi</em></td>
                        </tr>
                    </table>

                    @if($pendaftaran->diproses_pada)
                        <hr>
                        <small class="text-muted">
                            Diproses oleh <strong>{{ $pendaftaran->diprosesOleh->name ?? '-' }}</strong>
                            pada {{ formatTanggal($pendaftaran->diproses_pada) }}
                        </small>
                        @if($pendaftaran->catatan_admin)
                            <br><small class="text-muted">Catatan: {{ $pendaftaran->catatan_admin }}</small>
                        @endif
                    @endif
                </div>
            </div>

            @if($pendaftaran->status === 'disetujui' && $pendaftaran->siswa)
                <div class="card shadow border-left-success">
                    <div class="card-body">
                        <h6 class="text-success fw-bold mb-2">
                            <i class="fas fa-check-circle me-2"></i>Akun Siswa Telah Dibuat
                        </h6>
                        <p class="mb-2 small">Data siswa berhasil dibuat dan dapat diakses di:</p>
                        <a href="{{ route('admin.siswa.show', $pendaftaran->siswa_id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-user me-1"></i> Lihat Data Siswa
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- Panel Aksi --}}
        <div class="col-md-5">
            @if($pendaftaran->status === 'menunggu')

                {{-- Form Setujui --}}
                <div class="card shadow mb-4 border-left-success">
                    <div class="card-header py-3 bg-success text-white">
                        <h6 class="m-0 fw-bold"><i class="fas fa-check me-2"></i>Setujui Pendaftaran</h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">
                            Menyetujui akan otomatis membuat akun login dan data siswa.
                        </p>
                        <form action="{{ route('admin.pendaftaran.approve', $pendaftaran->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tempatkan di Kelas <span class="text-danger">*</span></label>
                                <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            {{ $pendaftaran->kelas_id == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama }}
                                            ({{ $kelas->siswa_count }}/{{ $kelas->kapasitas }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan (opsional)</label>
                                <textarea name="catatan_admin" rows="2" class="form-control"
                                    placeholder="Catatan untuk siswa..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Setujui pendaftaran {{ $pendaftaran->nama }}? Akun login akan dibuat.')">
                                <i class="fas fa-check me-2"></i> Setujui & Buat Akun
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Form Tolak --}}
                <div class="card shadow border-left-danger">
                    <div class="card-header py-3 bg-danger text-white">
                        <h6 class="m-0 fw-bold"><i class="fas fa-times me-2"></i>Tolak Pendaftaran</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.pendaftaran.reject', $pendaftaran->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="catatan_admin" rows="3" class="form-control @error('catatan_admin') is-invalid @enderror"
                                    placeholder="Jelaskan alasan penolakan..." required></textarea>
                                @error('catatan_admin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return confirm('Yakin ingin menolak pendaftaran ini?')">
                                <i class="fas fa-times me-2"></i> Tolak Pendaftaran
                            </button>
                        </form>
                    </div>
                </div>

            @else
                <div class="card shadow">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-{{ $pendaftaran->status === 'disetujui' ? 'check-circle text-success' : 'times-circle text-danger' }} fa-3x mb-3"></i>
                        <h6>Pendaftaran sudah {{ $pendaftaran->status_label }}</h6>
                        <p class="text-muted small">Tidak ada aksi yang tersedia</p>
                    </div>
                </div>
            @endif

            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                @if($pendaftaran->status !== 'disetujui')
                    <form action="{{ route('admin.pendaftaran.destroy', $pendaftaran->id) }}" method="POST"
                          onsubmit="return confirm('Hapus data pendaftaran ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
