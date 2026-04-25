<x-layouts.admin>
    <x-page-header 
        title="Rekap Kehadiran"
        subtitle="Laporan kehadiran siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Rekap Kehadiran', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Rekap Kehadiran</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.kehadiran.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari', $tanggalDari->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai', $tanggalSampai->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select name="kelas_id" class="form-control">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Min. Kehadiran (%)</label>
                            <input type="number" name="min_attendance" class="form-control" placeholder="0-100" value="{{ request('min_attendance') }}" min="0" max="100">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i> Tampilkan
                </button>
                <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
                <a href="{{ route('admin.laporan.kehadiran.pdf', array_filter([
                        'tanggal_dari'  => request('tanggal_dari', $tanggalDari->format('Y-m-d')),
                        'tanggal_sampai' => request('tanggal_sampai', $tanggalSampai->format('Y-m-d')),
                        'kelas_id'      => request('kelas_id'),
                    ])) }}"
                   class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Rekap Kehadiran ({{ formatTanggal($tanggalDari) }} - {{ formatTanggal($tanggalSampai) }})
            </h6>
        </div>
        <div class="card-body">
            @if($attendanceData->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Total Sesi</th>
                                <th>Hadir</th>
                                <th>Izin</th>
                                <th>Sakit</th>
                                <th>Alpha</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceData as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ route('admin.siswa.show', $data['siswa']->id) }}">
                                            {{ $data['siswa']->nama }}
                                        </a>
                                    </td>
                                    <td>{{ $data['siswa']->kelas->nama ?? '-' }}</td>
                                    <td>{{ $data['total_sesi'] }}</td>
                                    <td><span class="badge bg-success">{{ $data['hadir'] }}</span></td>
                                    <td><span class="badge bg-info">{{ $data['izin'] }}</span></td>
                                    <td><span class="badge bg-warning">{{ $data['sakit'] }}</span></td>
                                    <td><span class="badge bg-danger">{{ $data['alpha'] }}</span></td>
                                    <td>
                                        <strong class="text-{{ $data['persentase'] >= 80 ? 'success' : ($data['persentase'] >= 60 ? 'warning' : 'danger') }}">
                                            {{ $data['persentase'] }}%
                                        </strong>
                                        <div class="progress mt-1" style="height: 5px;">
                                            <div class="progress-bar bg-{{ $data['persentase'] >= 80 ? 'success' : ($data['persentase'] >= 60 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $data['persentase'] }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada data kehadiran untuk periode ini</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
