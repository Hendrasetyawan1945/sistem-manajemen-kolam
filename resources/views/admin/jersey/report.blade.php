<x-layouts.admin>
    <x-page-header 
        title="Laporan Jersey"
        subtitle="Ringkasan dan analitik pesanan jersey siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Jersey', 'url' => route('admin.jersey.index')],
            ['title' => 'Laporan', 'url' => '#']
        ]"
    />

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pesanan</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalPesanan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tshirt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Diterima</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalDiterima }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Dipesan</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalDipesan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Dibatalkan</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalDibatalkan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breakdown per Ukuran Jersey -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-ruler-horizontal me-1"></i>
                Breakdown Pesanan per Ukuran Jersey
            </h6>
        </div>
        <div class="card-body">
            @if($bySize->where('total', '>', 0)->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Ukuran</th>
                                <th>Keterangan</th>
                                <th class="text-center">Total Pesanan</th>
                                <th class="text-center">
                                    <span class="badge bg-success">Diterima</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge bg-warning text-dark">Dipesan</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge bg-danger">Dibatalkan</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bySize as $row)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $row['ukuran'] }}</span>
                                    </td>
                                    <td>{{ $row['keterangan'] ?? '-' }}</td>
                                    <td class="text-center fw-bold">{{ $row['total'] }}</td>
                                    <td class="text-center">
                                        @if($row['diterima'] > 0)
                                            <span class="badge bg-success">{{ $row['diterima'] }}</span>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($row['dipesan'] > 0)
                                            <span class="badge bg-warning text-dark">{{ $row['dipesan'] }}</span>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($row['dibatalkan'] > 0)
                                            <span class="badge bg-danger">{{ $row['dibatalkan'] }}</span>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="2">Total</td>
                                <td class="text-center">{{ $bySize->sum('total') }}</td>
                                <td class="text-center">{{ $bySize->sum('diterima') }}</td>
                                <td class="text-center">{{ $bySize->sum('dipesan') }}</td>
                                <td class="text-center">{{ $bySize->sum('dibatalkan') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-tshirt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data pesanan jersey</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Siswa Belum Pesan Jersey -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Siswa Aktif Belum Pesan Jersey
            </h6>
        </div>
        <div class="card-body">
            <!-- Filter Kelas -->
            <form method="GET" action="{{ route('admin.jersey.report') }}" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label for="kelas_id" class="form-label">Filter berdasarkan Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                    @if($kelasId)
                        <div class="col-md-2">
                            <a href="{{ route('admin.jersey.report') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times me-1"></i> Reset
                            </a>
                        </div>
                    @endif
                </div>
            </form>

            @if($siswaWithoutJersey->count() > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-1"></i>
                    Terdapat <strong>{{ $siswaWithoutJersey->count() }}</strong> siswa aktif yang belum memiliki pesanan jersey.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswaWithoutJersey as $index => $siswa)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.jersey.create') }}?siswa_id={{ $siswa->id }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i> Buat Pesanan
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-success mb-0">
                    <i class="fas fa-check-circle me-1"></i>
                    @if($kelasId)
                        Semua siswa aktif di kelas ini sudah memiliki pesanan jersey.
                    @else
                        Semua siswa aktif sudah memiliki pesanan jersey.
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex gap-2">
        <a href="{{ route('admin.jersey.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Jersey
        </a>
        <a href="{{ route('admin.jersey.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Pesanan Jersey
        </a>
    </div>
</x-layouts.admin>
