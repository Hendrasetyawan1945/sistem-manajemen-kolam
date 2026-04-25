<x-layouts.coach>
    <x-page-header 
        title="Rekap Kehadiran"
        subtitle="Rekap kehadiran siswa di kelas Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Rekap Kehadiran', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Rekap Kehadiran</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('coach.kehadiran.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-control">
                            <option value="">Semua Kelas Saya</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari', $tanggalDari->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai', $tanggalSampai->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Min. Kehadiran (%)</label>
                        <input type="number" name="min_attendance" class="form-control" min="0" max="100" value="{{ request('min_attendance') }}" placeholder="0">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($attendanceData->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th class="text-center">Total Sesi</th>
                                <th class="text-center text-success">Hadir</th>
                                <th class="text-center text-warning">Izin</th>
                                <th class="text-center text-info">Sakit</th>
                                <th class="text-center text-danger">Alpha</th>
                                <th class="text-center">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceData as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $item['siswa']->nama }}</strong></td>
                                    <td>{{ $item['siswa']->kelas->nama ?? '-' }}</td>
                                    <td class="text-center">{{ $item['total_sesi'] }}</td>
                                    <td class="text-center text-success"><strong>{{ $item['hadir'] }}</strong></td>
                                    <td class="text-center text-warning">{{ $item['izin'] }}</td>
                                    <td class="text-center text-info">{{ $item['sakit'] }}</td>
                                    <td class="text-center text-danger">{{ $item['alpha'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $item['persentase'] >= 75 ? 'success' : ($item['persentase'] >= 50 ? 'warning' : 'danger') }}">
                                            {{ $item['persentase'] }}%
                                        </span>
                                        <div class="progress mt-1" style="height: 4px;">
                                            <div class="progress-bar bg-{{ $item['persentase'] >= 75 ? 'success' : ($item['persentase'] >= 50 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $item['persentase'] }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Periode: {{ $tanggalDari->translatedFormat('d M Y') }} - {{ $tanggalSampai->translatedFormat('d M Y') }}
                    | Total siswa: {{ $attendanceData->count() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada data kehadiran untuk filter yang dipilih.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.coach>
