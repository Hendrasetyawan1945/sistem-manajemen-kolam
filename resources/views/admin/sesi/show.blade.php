<x-layouts.admin>
    <x-page-header 
        title="Detail Sesi Latihan"
        subtitle="Informasi lengkap sesi latihan dan kehadiran siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Sesi Latihan', 'url' => route('admin.sesi.index')],
            ['title' => 'Detail Sesi', 'url' => '#']
        ]"
    />

    <x-alert />

    <div class="row mb-4">
        <!-- Info Sesi -->
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Informasi Sesi
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="40%">Tanggal</td>
                            <td><strong>{{ formatTanggal($sesi->tanggal) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kelas</td>
                            <td><strong>{{ $sesi->kelas->nama }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Coach</td>
                            <td>{{ $sesi->coach->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Waktu</td>
                            <td>{{ $sesi->waktu_mulai }} - {{ $sesi->waktu_selesai }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Catatan</td>
                            <td>{{ $sesi->catatan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistik Kehadiran -->
        <div class="col-md-6">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Statistik Kehadiran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0 text-success">{{ $hadir }}</div>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0 text-warning">{{ $izin }}</div>
                                <small class="text-muted">Izin</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0 text-info">{{ $sakit }}</div>
                                <small class="text-muted">Sakit</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border rounded p-2">
                                <div class="h4 mb-0 text-danger">{{ $alpha }}</div>
                                <small class="text-muted">Alpha</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Persentase Hadir</span>
                        <strong>{{ number_format($persentaseHadir, 1) }}%</strong>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $persentaseHadir }}%"
                             aria-valuenow="{{ $persentaseHadir }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted">Total {{ $totalSiswa }} siswa</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Kehadiran -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Daftar Kehadiran Siswa
            </h6>
            <a href="{{ route('admin.sesi.attendance', $sesi->id) }}" class="btn btn-sm btn-success">
                <i class="fas fa-edit me-1"></i> Edit Kehadiran
            </a>
        </div>
        <div class="card-body">
            @if($sesi->kehadiran->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sesi->kehadiran as $index => $k)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $k->siswa->nama }}</td>
                                    <td>
                                        @php
                                            $badge = match($k->status) {
                                                'hadir'  => 'success',
                                                'izin'   => 'warning',
                                                'sakit'  => 'info',
                                                'alpha'  => 'danger',
                                                default  => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badge }}">
                                            {{ ucfirst($k->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data kehadiran untuk sesi ini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('admin.sesi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <a href="{{ route('admin.sesi.edit', $sesi->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Sesi
        </a>
        <form action="{{ route('admin.sesi.destroy', $sesi->id) }}" method="POST" 
              onsubmit="return confirm('Yakin ingin menghapus sesi ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Hapus Sesi
            </button>
        </form>
    </div>
</x-layouts.admin>
