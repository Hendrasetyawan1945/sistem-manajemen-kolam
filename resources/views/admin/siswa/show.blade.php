<x-layouts.admin>
    <x-page-header 
        title="Detail Siswa"
        subtitle="Informasi lengkap siswa {{ $siswa->nama }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Siswa', 'url' => route('admin.siswa.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="row">
        <!-- Profile Card -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    @if($siswa->foto)
                        <img src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama }}" class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-4x text-white"></i>
                        </div>
                    @endif
                    
                    <h4 class="mb-1">{{ $siswa->nama }}</h4>
                    <p class="text-muted mb-2">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    <x-status-badge :status="$siswa->status" />
                    
                    <hr>
                    
                    <div class="text-start">
                        <p class="mb-2">
                            <i class="fas fa-birthday-cake text-muted me-2"></i>
                            <strong>Tanggal Lahir:</strong><br>
                            <span class="ms-4">{{ formatTanggal($siswa->tanggal_lahir) }}</span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-chalkboard text-muted me-2"></i>
                            <strong>Kelas:</strong><br>
                            <span class="ms-4">{{ $siswa->kelas->nama ?? 'Belum ada kelas' }}</span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-user-friends text-muted me-2"></i>
                            <strong>Orang Tua:</strong><br>
                            <span class="ms-4">{{ $siswa->nama_ortu }}</span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-phone text-muted me-2"></i>
                            <strong>Telepon:</strong><br>
                            <span class="ms-4">{{ $siswa->telepon_ortu }}</span>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                            <strong>Alamat:</strong><br>
                            <span class="ms-4">{{ $siswa->alamat }}</span>
                        </p>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Data
                        </a>
                        <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Tabs -->
        <div class="col-md-8">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Kehadiran
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($attendancePercentage, 1) }}%
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Tunggakan
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ formatRupiah($siswa->iuranRutin->where('status_bayar', 'belum')->sum('jumlah') + $siswa->iuranInsidentil->where('status_bayar', 'belum')->sum('jumlah')) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kehadiran">Kehadiran</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#keuangan">Keuangan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#prestasi">Prestasi</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Kehadiran Tab -->
                        <div class="tab-pane fade show active" id="kehadiran">
                            <h6 class="font-weight-bold mb-3">Riwayat Kehadiran Terbaru</h6>
                            @if($siswa->kehadiran->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Waktu</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswa->kehadiran->sortByDesc('sesi.tanggal')->take(10) as $kehadiran)
                                                <tr>
                                                    <td>{{ formatTanggal($kehadiran->sesi->tanggal) }}</td>
                                                    <td>{{ $kehadiran->sesi->waktu_mulai }} - {{ $kehadiran->sesi->waktu_selesai }}</td>
                                                    <td><x-status-badge :status="$kehadiran->status" /></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Belum ada data kehadiran</p>
                            @endif
                        </div>

                        <!-- Keuangan Tab -->
                        <div class="tab-pane fade" id="keuangan">
                            <h6 class="font-weight-bold mb-3">Riwayat Pembayaran</h6>
                            
                            <h6 class="text-primary mt-3">Iuran Rutin</h6>
                            @if($siswa->iuranRutin->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Periode</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                                <th>Tanggal Bayar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswa->iuranRutin->sortByDesc('tahun')->sortByDesc('bulan')->take(6) as $iuran)
                                                <tr>
                                                    <td>{{ $iuran->bulan }}/{{ $iuran->tahun }}</td>
                                                    <td>{{ formatRupiah($iuran->jumlah) }}</td>
                                                    <td><x-status-badge :status="$iuran->status_bayar" /></td>
                                                    <td>{{ $iuran->tanggal_bayar ? formatTanggal($iuran->tanggal_bayar) : '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Belum ada iuran rutin</p>
                            @endif

                            <h6 class="text-primary mt-4">Iuran Insidentil</h6>
                            @if($siswa->iuranInsidentil->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswa->iuranInsidentil->sortByDesc('tanggal') as $iuran)
                                                <tr>
                                                    <td>{{ $iuran->nama_item }}</td>
                                                    <td>{{ formatRupiah($iuran->jumlah) }}</td>
                                                    <td><x-status-badge :status="$iuran->status_bayar" /></td>
                                                    <td>{{ formatTanggal($iuran->tanggal) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Belum ada iuran insidentil</p>
                            @endif
                        </div>

                        <!-- Prestasi Tab -->
                        <div class="tab-pane fade" id="prestasi">
                            <h6 class="font-weight-bold mb-3">Personal Best</h6>
                            @if($siswa->personalBest->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Nomor Lomba</th>
                                                <th>Waktu Terbaik</th>
                                                <th>Tanggal Capai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($siswa->personalBest as $pb)
                                                <tr>
                                                    <td>{{ $pb->nomor_lomba }}</td>
                                                    <td><strong>{{ $pb->waktu_terbaik }}</strong></td>
                                                    <td>{{ formatTanggal($pb->tanggal_capai) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Belum ada catatan personal best</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
