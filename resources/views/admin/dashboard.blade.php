<x-layouts.admin>
    <x-page-header 
        title="Dashboard Admin"
        subtitle="Selamat datang di panel administrasi sistem manajemen klub renang"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => '#']
        ]"
    />

    <!-- Statistics Cards -->
    <div class="row">
        <x-stat-card 
            title="Total Siswa Aktif"
            :value="$totalSiswaAktif"
            icon="fas fa-users"
            color="primary"
            subtitle="Siswa terdaftar"
        />
        
        <x-stat-card 
            title="Total Kelas"
            :value="$totalKelas"
            icon="fas fa-chalkboard"
            color="success"
            subtitle="Kelas aktif"
        />
        
        <x-stat-card 
            title="Pendapatan Bulan Ini"
            :value="formatRupiah($pendapatanBulanIni)"
            icon="fas fa-money-bill-wave"
            color="info"
            subtitle="Total pemasukan"
        />
        
        <x-stat-card 
            title="Tunggakan"
            :value="formatRupiah($outstandingTuition)"
            icon="fas fa-exclamation-triangle"
            color="warning"
            subtitle="Belum terbayar"
        />
    </div>

    <!-- Second Row Stats -->
    <div class="row">
        <x-stat-card 
            title="Total Coaches"
            :value="$totalCoaches"
            icon="fas fa-user-tie"
            color="success"
            subtitle="Pelatih aktif"
        />
        
        <x-stat-card 
            title="Pengeluaran Bulan Ini"
            :value="formatRupiah($pengeluaranBulanIni)"
            icon="fas fa-receipt"
            color="danger"
            subtitle="Total pengeluaran"
        />
        
        <x-stat-card 
            title="Net Balance"
            :value="formatRupiah($netBalance)"
            icon="fas fa-balance-scale"
            :color="$netBalance >= 0 ? 'success' : 'danger'"
            subtitle="Saldo bersih"
        />
        
        <x-stat-card 
            title="Kehadiran Bulan Ini"
            :value="$attendanceStats['persentase'] . '%'"
            icon="fas fa-check-circle"
            color="info"
            :subtitle="$attendanceStats['hadir'] . ' dari ' . $attendanceStats['total_kehadiran'] . ' sesi'"
        />
    </div>

    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terbaru</h6>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Siswa</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td>{{ formatTanggal($transaction['tanggal']) }}</td>
                                            <td>{{ $transaction['siswa'] }}</td>
                                            <td>{{ $transaction['jenis'] }}</td>
                                            <td>{{ formatRupiah($transaction['jumlah']) }}</td>
                                            <td><x-status-badge :status="$transaction['status']" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Belum ada transaksi bulan ini</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Attendance Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Kehadiran Bulan Ini</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h4 class="text-success">{{ $attendanceStats['hadir'] }}</h4>
                            <small class="text-muted">Hadir</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-danger">{{ $attendanceStats['alpha'] }}</h4>
                            <small class="text-muted">Alpha</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-info">{{ $attendanceStats['izin'] }}</h4>
                            <small class="text-muted">Izin</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h4 class="text-warning">{{ $attendanceStats['sakit'] }}</h4>
                            <small class="text-muted">Sakit</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h5 class="text-primary">
                            Persentase Kehadiran: {{ $attendanceStats['persentase'] }}%
                        </h5>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-{{ $attendanceStats['persentase'] >= 80 ? 'success' : ($attendanceStats['persentase'] >= 60 ? 'warning' : 'danger') }}" 
                                 role="progressbar" 
                                 style="width: {{ $attendanceStats['persentase'] }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>
                            Tambah Siswa Baru
                        </a>
                        <a href="{{ route('admin.sesi.create') }}" class="btn btn-success">
                            <i class="fas fa-calendar-plus me-2"></i>
                            Buat Sesi Latihan
                        </a>
                        <a href="{{ route('admin.iuran-rutin.create') }}" class="btn btn-info">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Input Iuran
                        </a>
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-warning">
                            <i class="fas fa-chart-bar me-2"></i>
                            Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Low Attendance Alert -->
            @if($lowAttendanceStudents->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Perhatian Khusus
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6 class="text-warning">Siswa dengan Kehadiran Rendah</h6>
                        @foreach($lowAttendanceStudents as $student)
                            <div class="mb-2">
                                <small class="text-muted">{{ $student['siswa']->nama }} - {{ $student['siswa']->kelas->nama ?? 'Belum ada kelas' }}</small><br>
                                <span class="badge bg-{{ $student['persentase'] < 50 ? 'danger' : 'warning' }}">
                                    {{ $student['persentase'] }}% kehadiran
                                </span>
                                <small class="text-muted">({{ $student['hadir'] }}/{{ $student['total_sesi'] }} sesi)</small>
                            </div>
                        @endforeach
                        <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-sm btn-outline-warning mt-2">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endif

            <!-- Upcoming Events -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kejuaraan Mendatang</h6>
                </div>
                <div class="card-body">
                    @if($upcomingCompetitions->count() > 0)
                        @foreach($upcomingCompetitions as $competition)
                            <div class="mb-3">
                                <h6 class="mb-1">{{ $competition->nama }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ formatTanggal($competition->tanggal_mulai) }}
                                </small>
                                <p class="text-sm mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $competition->lokasi }}
                                </p>
                                <p class="text-sm mb-0">{{ $competition->iuranKejuaraan->count() }} siswa terdaftar</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Tidak ada kejuaraan dalam 30 hari ke depan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>