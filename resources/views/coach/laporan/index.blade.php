<x-layouts.coach>
    <x-page-header
        title="Laporan Kelas"
        subtitle="Rekap kehadiran dan prestasi siswa di kelas Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Laporan', 'url' => '#']
        ]"
    />

    {{-- Filter --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Laporan
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('coach.laporan.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control"
                            value="{{ request('tanggal_dari', $tanggalDari->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control"
                            value="{{ request('tanggal_sampai', $tanggalSampai->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">Semua Kelas Saya</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="text-xs text-muted text-uppercase mb-1">Total Siswa</div>
                    <div class="h4 fw-bold">{{ $siswaList->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <div class="text-xs text-muted text-uppercase mb-1">Total Sesi</div>
                    <div class="h4 fw-bold">{{ $totalSesi }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="text-xs text-muted text-uppercase mb-1">Rata-rata Kehadiran</div>
                    <div class="h4 fw-bold">
                        {{ $rekapKehadiran->count() > 0
                            ? number_format($rekapKehadiran->avg('persen'), 1)
                            : 0 }}%
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow">
                <div class="card-body">
                    <div class="text-xs text-muted text-uppercase mb-1">Kehadiran < 75%</div>
                    <div class="h4 fw-bold text-warning">
                        {{ $rekapKehadiran->where('persen', '<', 75)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekap Kehadiran --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-check-circle me-2"></i>
                Rekap Kehadiran
                ({{ formatTanggal($tanggalDari) }} — {{ formatTanggal($tanggalSampai) }})
            </h6>
        </div>
        <div class="card-body">
            @if($rekapKehadiran->count() > 0)
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
                            @foreach($rekapKehadiran as $data)
                                <tr class="{{ $data['persen'] < 75 ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $data['siswa']->nama }}</strong></td>
                                    <td>{{ $data['siswa']->kelas->nama ?? '-' }}</td>
                                    <td>{{ $data['total'] }}</td>
                                    <td><span class="badge bg-success">{{ $data['hadir'] }}</span></td>
                                    <td><span class="badge bg-info">{{ $data['izin'] }}</span></td>
                                    <td><span class="badge bg-warning text-dark">{{ $data['sakit'] }}</span></td>
                                    <td><span class="badge bg-danger">{{ $data['alpha'] }}</span></td>
                                    <td>
                                        <strong class="text-{{ $data['persen'] >= 80 ? 'success' : ($data['persen'] >= 60 ? 'warning' : 'danger') }}">
                                            {{ $data['persen'] }}%
                                        </strong>
                                        <div class="progress mt-1" style="height:5px">
                                            <div class="progress-bar bg-{{ $data['persen'] >= 80 ? 'success' : ($data['persen'] >= 60 ? 'warning' : 'danger') }}"
                                                 style="width:{{ $data['persen'] }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada data kehadiran untuk periode ini</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Personal Best --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-medal me-2"></i>Personal Best Siswa
            </h6>
        </div>
        <div class="card-body">
            @if($personalBests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Gaya Renang</th>
                                <th>Jarak</th>
                                <th>Waktu Terbaik</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personalBests as $siswaId => $bests)
                                @foreach($bests as $pb)
                                    <tr>
                                        @if($loop->first)
                                            <td rowspan="{{ $bests->count() }}">
                                                <strong>{{ $pb->siswa->nama }}</strong>
                                            </td>
                                            <td rowspan="{{ $bests->count() }}">
                                                {{ $pb->siswa->kelas->nama ?? '-' }}
                                            </td>
                                        @endif
                                        <td>{{ $pb->gaya_renang }}</td>
                                        <td>{{ $pb->jarak }}m</td>
                                        <td><strong class="text-primary">{{ $pb->catatan_waktu }}</strong></td>
                                        <td>{{ formatTanggal($pb->tanggal) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-medal fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data personal best untuk siswa di kelas Anda</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.coach>
