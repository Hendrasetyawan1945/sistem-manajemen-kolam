<x-layouts.admin>
    <x-page-header
        title="Rekap Iuran Rutin"
        subtitle="Status pembayaran iuran bulanan seluruh siswa aktif"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Laporan', 'url' => route('admin.laporan.index')],
            ['title' => 'Rekap Iuran Rutin', 'url' => '#'],
        ]"
    />

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Laporan
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.iuran-rutin') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select class="form-select" id="bulan" name="bulan">
                            @foreach($namaBulan as $num => $nama)
                                <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="number" class="form-control" id="tahun" name="tahun"
                               value="{{ $tahun }}" min="2020" max="{{ date('Y') + 1 }}">
                    </div>
                    <div class="col-md-3">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select class="form-select" id="kelas_id" name="kelas_id">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Tampilkan Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Export PDF Button -->
    <div class="mb-3 text-end">
        <a href="{{ route('admin.laporan.iuran-rutin.pdf', ['bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => $kelasId]) }}"
           class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
    </div>

    <!-- Periode Info -->
    <div class="alert alert-info mb-4">
        <i class="fas fa-calendar-alt me-2"></i>
        Menampilkan rekap iuran bulan
        <strong>{{ $namaBulan[$bulan] }} {{ $tahun }}</strong>
        @if($kelasId)
            — Kelas: <strong>{{ $kelasList->firstWhere('id', $kelasId)->nama ?? '-' }}</strong>
        @else
            — <strong>Semua Kelas</strong>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card border-left-primary shadow text-center h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa</div>
                    <div class="h4 mb-0 font-weight-bold">{{ $totalSiswa }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card border-left-success shadow text-center h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Lunas</div>
                    <div class="h4 mb-0 font-weight-bold text-success">{{ $totalLunas }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card border-left-danger shadow text-center h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Belum Lunas</div>
                    <div class="h4 mb-0 font-weight-bold text-danger">{{ $totalBelumLunas }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-success shadow text-center h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Terkumpul</div>
                    <div class="h6 mb-0 font-weight-bold text-success">{{ formatRupiah($totalTerkumpul) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-warning shadow text-center h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tunggakan</div>
                    <div class="h6 mb-0 font-weight-bold text-warning">{{ formatRupiah($totalOutstanding) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    @if($totalSiswa > 0)
        @php $persenLunas = round(($totalLunas / $totalSiswa) * 100, 1); @endphp
        <div class="card shadow mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-sm font-weight-bold">Tingkat Pembayaran</span>
                    <span class="text-sm font-weight-bold">{{ $persenLunas }}%</span>
                </div>
                <div class="progress" style="height: 12px;">
                    <div class="progress-bar bg-{{ $persenLunas >= 80 ? 'success' : ($persenLunas >= 50 ? 'warning' : 'danger') }}"
                         role="progressbar" style="width: {{ $persenLunas }}%">
                    </div>
                </div>
                <small class="text-muted">{{ $totalLunas }} dari {{ $totalSiswa }} siswa sudah membayar</small>
            </div>
        </div>
    @endif

    <!-- Detail Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Detail Per Siswa
            </h6>
            <span class="badge bg-secondary">{{ $totalSiswa }} siswa</span>
        </div>
        <div class="card-body p-0">
            @if(count($data) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Jumlah</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $row)
                                <tr class="{{ $row['status'] !== 'lunas' ? 'table-warning' : '' }}">
                                    <td class="ps-3">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $row['siswa']->nama }}</strong>
                                        @if($row['siswa']->nis)
                                            <br><small class="text-muted">NIS: {{ $row['siswa']->nis }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $row['kelas']->nama ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($row['status'] === 'lunas')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Lunas
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Belum Lunas
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ formatRupiah($row['jumlah']) }}</td>
                                    <td>
                                        @if($row['tanggal_bayar'])
                                            {{ formatTanggal($row['tanggal_bayar']) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $row['metode'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="4" class="ps-3 text-end">Total:</td>
                                <td class="text-end">{{ formatRupiah($totalTerkumpul + $totalOutstanding) }}</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="4" class="ps-3 text-end">Terkumpul ({{ $totalLunas }} siswa):</td>
                                <td class="text-end text-success">{{ formatRupiah($totalTerkumpul) }}</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr class="table-warning">
                                <td colspan="4" class="ps-3 text-end">Tunggakan ({{ $totalBelumLunas }} siswa):</td>
                                <td class="text-end text-danger">{{ formatRupiah($totalOutstanding) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <p class="mb-0">Tidak ada siswa aktif pada filter yang dipilih</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
