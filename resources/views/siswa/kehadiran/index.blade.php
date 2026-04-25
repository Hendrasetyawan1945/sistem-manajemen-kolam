<x-layouts.siswa>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-check-circle me-2 text-success"></i>
            Rekap Kehadiran
        </h4>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('siswa.kehadiran.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        @php
                            $namaBulan = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        @foreach($namaBulan as $num => $nama)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-left-primary text-center">
                <div class="card-body py-3">
                    <h4 class="text-primary mb-0">{{ $totalSesi }}</h4>
                    <small class="text-muted">Total Sesi</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-success text-center">
                <div class="card-body py-3">
                    <h4 class="text-success mb-0">{{ $totalHadir }}</h4>
                    <small class="text-muted">Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-info text-center">
                <div class="card-body py-3">
                    <h4 class="text-info mb-0">{{ $totalIzin }}</h4>
                    <small class="text-muted">Izin</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-warning text-center">
                <div class="card-body py-3">
                    <h4 class="text-warning mb-0">{{ $totalSakit }}</h4>
                    <small class="text-muted">Sakit</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-danger text-center">
                <div class="card-body py-3">
                    <h4 class="text-danger mb-0">{{ $totalAlpha }}</h4>
                    <small class="text-muted">Alpha</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-{{ $persentaseKehadiran >= 75 ? 'success' : 'danger' }} text-center">
                <div class="card-body py-3">
                    <h4 class="text-{{ $persentaseKehadiran >= 75 ? 'success' : 'danger' }} mb-0">
                        {{ $persentaseKehadiran }}%
                    </h4>
                    <small class="text-muted">Persentase</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    @if($totalSesi > 0)
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <span>Tingkat Kehadiran</span>
                    <strong>{{ $persentaseKehadiran }}%</strong>
                </div>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-{{ $persentaseKehadiran >= 75 ? 'success' : ($persentaseKehadiran >= 50 ? 'warning' : 'danger') }}"
                         role="progressbar"
                         style="width: {{ $persentaseKehadiran }}%"
                         aria-valuenow="{{ $persentaseKehadiran }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        {{ $persentaseKehadiran }}%
                    </div>
                </div>
                @if($persentaseKehadiran < 75)
                    <small class="text-danger mt-1 d-block">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Kehadiran di bawah 75%. Harap tingkatkan kehadiran Anda.
                    </small>
                @endif
            </div>
        </div>
    @endif

    <!-- Tabel Kehadiran -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>
                Detail Kehadiran -
                @php
                    $namaBulanArr = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                @endphp
                {{ $namaBulanArr[$bulan] }} {{ $tahun }}
            </h6>
        </div>
        <div class="card-body">
            @if($kehadiran->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <p>Tidak ada data kehadiran untuk periode ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kehadiran as $i => $k)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ formatTanggal($k->sesi->tanggal) }}</td>
                                    <td>{{ $k->sesi->kelas->nama ?? '-' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($k->sesi->waktu_mulai)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($k->sesi->waktu_selesai)->format('H:i') }}
                                    </td>
                                    <td>
                                        @php
                                            $statusColor = match($k->status) {
                                                'hadir' => 'success',
                                                'izin' => 'info',
                                                'sakit' => 'warning',
                                                'alpha' => 'danger',
                                                default => 'secondary'
                                            };
                                            $statusLabel = match($k->status) {
                                                'hadir' => 'Hadir',
                                                'izin' => 'Izin',
                                                'sakit' => 'Sakit',
                                                'alpha' => 'Alpha',
                                                default => ucfirst($k->status)
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td>{{ $k->keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts.siswa>
