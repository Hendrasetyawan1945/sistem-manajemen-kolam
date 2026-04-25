<x-layouts.coach>
    <x-page-header 
        title="Dashboard Coach"
        subtitle="Selamat datang, {{ Auth::user()->name }}! Kelola kelas dan siswa Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => '#']
        ]"
    />

    <!-- Statistics Cards -->
    <div class="row">
        <x-stat-card 
            title="Kelas Saya"
            :value="$kelasList->count()"
            icon="fas fa-chalkboard"
            color="primary"
            subtitle="Kelas yang diampu"
        />
        
        <x-stat-card 
            title="Total Siswa"
            :value="$totalSiswa"
            icon="fas fa-users"
            color="success"
            subtitle="Di semua kelas"
        />
        
        <x-stat-card 
            title="Sesi Bulan Ini"
            :value="$sesibulanIni"
            icon="fas fa-calendar-alt"
            color="info"
            subtitle="Sesi latihan bulan ini"
        />
        
        <x-stat-card 
            title="Kehadiran Rendah"
            :value="$lowAttendanceStudents->count()"
            icon="fas fa-exclamation-triangle"
            color="warning"
            subtitle="Siswa < 75% bulan ini"
        />
    </div>

    <div class="row">
        <!-- My Classes -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Kelas Saya</h6>
                    <a href="{{ route('coach.sesi.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Buat Sesi
                    </a>
                </div>
                <div class="card-body">
                    @if($kelasList->isEmpty())
                        <p class="text-muted text-center py-3">Belum ada kelas yang di-assign.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Kelas</th>
                                        <th>Jadwal</th>
                                        <th>Kapasitas</th>
                                        <th>Siswa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kelasList as $kelas)
                                    <tr>
                                        <td>
                                            <strong>{{ $kelas->nama }}</strong>
                                            @if($kelas->deskripsi)
                                                <br><small class="text-muted">{{ $kelas->deskripsi }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $kelas->jadwal ?? '-' }}</td>
                                        <td>{{ $kelas->kapasitas }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $kelas->siswa_count }} siswa</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('coach.kelas.show', $kelas->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Kelola
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Sessions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-calendar-check me-1"></i>
                        Sesi Mendatang (7 Hari ke Depan)
                    </h6>
                    <a href="{{ route('coach.sesi.index') }}" class="btn btn-sm btn-outline-info">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($upcomingSessions->isEmpty())
                        <p class="text-muted text-center py-3">Tidak ada sesi mendatang dalam 7 hari ke depan.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kelas</th>
                                        <th>Waktu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingSessions as $sesi)
                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('D, d M Y') }}
                                            @if(\Carbon\Carbon::parse($sesi->tanggal)->isToday())
                                                <span class="badge bg-success ms-1">Hari Ini</span>
                                            @endif
                                        </td>
                                        <td>{{ $sesi->kelas->nama ?? '-' }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($sesi->waktu_mulai)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($sesi->waktu_selesai)->format('H:i') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('coach.sesi.show', $sesi->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Rekap Kehadiran Terbaru</h6>
                    <a href="{{ route('coach.kehadiran.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentAttendance->isEmpty())
                        <p class="text-muted text-center py-3">Belum ada data kehadiran.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kelas</th>
                                        <th>Waktu</th>
                                        <th>Kehadiran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAttendance as $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item['sesi']->tanggal)->translatedFormat('d M Y') }}</td>
                                        <td>{{ $item['sesi']->kelas->nama ?? '-' }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item['sesi']->waktu_mulai)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($item['sesi']->waktu_selesai)->format('H:i') }}
                                        </td>
                                        <td>
                                            {{ $item['hadir'] }}/{{ $item['total'] }}
                                            <span class="badge {{ $item['persentase'] >= 75 ? 'bg-success' : 'bg-danger' }} ms-1">
                                                {{ $item['persentase'] }}%
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('coach.sesi.show', $item['sesi']->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Competition Results -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-trophy me-1"></i>
                        Hasil Kejuaraan Terbaru
                    </h6>
                    <a href="{{ route('coach.catatan-waktu.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentCompetitionResults->isEmpty())
                        <p class="text-muted text-center py-3">Belum ada catatan hasil kejuaraan.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Siswa</th>
                                        <th>Kelas</th>
                                        <th>Kejuaraan</th>
                                        <th>Nomor Lomba</th>
                                        <th>Waktu</th>
                                        <th>Posisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCompetitionResults as $catatan)
                                    <tr>
                                        <td>{{ $catatan->siswa->nama ?? '-' }}</td>
                                        <td>{{ $catatan->siswa->kelas->nama ?? '-' }}</td>
                                        <td>{{ $catatan->kejuaraan->nama ?? '-' }}</td>
                                        <td>{{ $catatan->nomor_lomba }}</td>
                                        <td><code>{{ $catatan->catatan_waktu }}</code></td>
                                        <td>
                                            @if($catatan->posisi)
                                                @if($catatan->posisi <= 3)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-medal me-1"></i>Juara {{ $catatan->posisi }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Posisi {{ $catatan->posisi }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Alerts -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('coach.sesi.create') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>
                            Buat Sesi Latihan
                        </a>
                        <a href="{{ route('coach.kehadiran.index') }}" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Input Kehadiran
                        </a>
                        <a href="{{ route('coach.catatan-latihan.create') }}" class="btn btn-info">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Catat Latihan
                        </a>
                        <a href="{{ route('coach.rapor.create') }}" class="btn btn-warning">
                            <i class="fas fa-file-alt me-2"></i>
                            Buat Rapor
                        </a>
                        <a href="{{ route('coach.catatan-waktu.create') }}" class="btn btn-secondary">
                            <i class="fas fa-stopwatch me-2"></i>
                            Catat Waktu Lomba
                        </a>
                    </div>
                </div>
            </div>

            <!-- Low Attendance Alert -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Siswa Kehadiran Rendah
                    </h6>
                </div>
                <div class="card-body">
                    @if($lowAttendanceStudents->isEmpty())
                        <p class="text-muted text-center py-2">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            Semua siswa memiliki kehadiran baik bulan ini.
                        </p>
                    @else
                        <p class="text-muted small mb-3">Siswa dengan kehadiran &lt; 75% bulan ini:</p>
                        @foreach($lowAttendanceStudents as $item)
                        <div class="mb-3 pb-2 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="d-block">{{ $item['siswa']->nama }}</strong>
                                    <small class="text-muted">{{ $item['siswa']->kelas->nama ?? '-' }}</small>
                                </div>
                                <span class="badge {{ $item['persentase'] < 50 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                    {{ $item['persentase'] }}%
                                </span>
                            </div>
                            <small class="text-muted">
                                Hadir {{ $item['hadir'] }} dari {{ $item['total_sesi'] }} sesi
                            </small>
                        </div>
                        @endforeach
                        <a href="{{ route('coach.kehadiran.index') }}" class="btn btn-sm btn-outline-warning w-100 mt-1">
                            Lihat Detail Kehadiran
                        </a>
                    @endif
                </div>
            </div>

            <!-- My Classes Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Kelas</h6>
                </div>
                <div class="card-body p-0">
                    @if($kelasList->isEmpty())
                        <p class="text-muted text-center py-3">Belum ada kelas.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($kelasList as $kelas)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $kelas->nama }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $kelas->jadwal ?? 'Jadwal belum diset' }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $kelas->siswa_count }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.coach>
