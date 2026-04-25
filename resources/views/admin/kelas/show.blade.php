<x-layouts.admin>
    <x-page-header 
        title="Detail Kelas"
        subtitle="Informasi lengkap kelas {{ $kela->nama }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Kelas', 'url' => route('admin.kelas.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="row">
        <!-- Kelas Info Card -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Kelas</h6>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $kela->nama }}</h4>
                    
                    @if($kela->is_active)
                        <span class="badge bg-success mb-3">Aktif</span>
                    @else
                        <span class="badge bg-secondary mb-3">Nonaktif</span>
                    @endif
                    
                    @if($kela->deskripsi)
                        <p class="text-muted">{{ $kela->deskripsi }}</p>
                        <hr>
                    @endif
                    
                    <div class="mb-3">
                        <i class="fas fa-user-tie text-primary me-2"></i>
                        <strong>Coach:</strong><br>
                        <span class="ms-4">{{ $kela->coach->name }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <i class="fas fa-calendar text-primary me-2"></i>
                        <strong>Jadwal:</strong><br>
                        <span class="ms-4">{{ $kela->jadwal }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <i class="fas fa-users text-primary me-2"></i>
                        <strong>Kapasitas:</strong><br>
                        <span class="ms-4">{{ $totalSiswa }}/{{ $kela->kapasitas }} siswa</span>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-{{ $kapasitasTerisi >= 100 ? 'danger' : ($kapasitasTerisi >= 80 ? 'warning' : 'success') }}" 
                                 role="progressbar" 
                                 style="width: {{ min($kapasitasTerisi, 100) }}%">
                                {{ number_format($kapasitasTerisi, 0) }}%
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <i class="fas fa-money-bill-wave text-primary me-2"></i>
                        <strong>Biaya Bulanan:</strong><br>
                        <span class="ms-4">{{ formatRupiah($kela->biaya_bulanan) }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.kelas.edit', $kela->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Kelas
                        </a>
                        <a href="{{ route('admin.sesi.create', ['kelas_id' => $kela->id]) }}" class="btn btn-success">
                            <i class="fas fa-calendar-plus me-1"></i> Buat Sesi Latihan
                        </a>
                        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">
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
                <div class="col-md-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Siswa
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $totalSiswa }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Siswa Aktif
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $siswaAktif }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Sesi
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $totalSesi }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                            <a class="nav-link active" data-bs-toggle="tab" href="#siswa">Daftar Siswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#sesi">Sesi Latihan</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Siswa Tab -->
                        <div class="tab-pane fade show active" id="siswa">
                            <h6 class="font-weight-bold mb-3">Siswa Terdaftar ({{ $totalSiswa }})</h6>
                            @if($kela->siswa->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kela->siswa as $siswa)
                                                <tr>
                                                    <td>
                                                        @if($siswa->foto)
                                                            <img src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama }}" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                                        @endif
                                                        {{ $siswa->nama }}
                                                    </td>
                                                    <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                    <td><x-status-badge :status="$siswa->status" /></td>
                                                    <td>
                                                        <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Belum ada siswa terdaftar di kelas ini</p>
                                <a href="{{ route('admin.siswa.create', ['kelas_id' => $kela->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i> Tambah Siswa
                                </a>
                            @endif
                        </div>

                        <!-- Sesi Tab -->
                        <div class="tab-pane fade" id="sesi">
                            <h6 class="font-weight-bold mb-3">Sesi Latihan Terbaru</h6>
                            @if($kela->sesi->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Waktu</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kela->sesi as $sesi)
                                                <tr>
                                                    <td>{{ formatTanggal($sesi->tanggal) }}</td>
                                                    <td>{{ $sesi->waktu_mulai }} - {{ $sesi->waktu_selesai }}</td>
                                                    <td>{{ $sesi->catatan ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('admin.sesi.index', ['kelas_id' => $kela->id]) }}" class="btn btn-sm btn-primary">
                                    Lihat Semua Sesi
                                </a>
                            @else
                                <p class="text-muted">Belum ada sesi latihan</p>
                                <a href="{{ route('admin.sesi.create', ['kelas_id' => $kela->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus me-1"></i> Buat Sesi Pertama
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
