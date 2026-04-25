<x-layouts.siswa>
    <!-- Hero Section -->
    <div class="hero-section rounded mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-6 fw-bold mb-3">
                        Selamat Datang, {{ $siswa->nama }}!
                    </h1>
                    <p class="lead mb-0">
                        Pantau perkembangan latihan dan prestasi renang Anda
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-swimmer fa-5x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Info Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card info-card border-left-primary h-100">
                <div class="card-body text-center">
                    @if($siswa->foto_url)
                        <img src="{{ $siswa->foto_url }}" alt="Foto" class="rounded-circle mb-3" style="width:64px;height:64px;object-fit:cover;">
                    @else
                        <i class="fas fa-user-circle fa-3x text-primary mb-3"></i>
                    @endif
                    <h6 class="card-title">Profil Saya</h6>
                    <p class="card-text">
                        <strong>Kelas:</strong> {{ $siswa->kelas->nama ?? '-' }}<br>
                        <strong>Status:</strong> <x-status-badge :status="$siswa->status" />
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card info-card border-left-success h-100">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h6 class="card-title">Kehadiran</h6>
                    <h4 class="{{ $attendancePercentage >= 75 ? 'text-success' : 'text-danger' }}">
                        {{ $attendancePercentage }}%
                    </h4>
                    <p class="card-text">
                        Bulan ini ({{ $totalHadir }}/{{ $totalSesi }} sesi)
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card info-card border-left-warning h-100">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave fa-3x text-warning mb-3"></i>
                    <h6 class="card-title">Keuangan</h6>
                    @if($iuranBulanIni)
                        <x-status-badge :status="$iuranBulanIni->status_bayar === 'lunas' ? 'lunas' : 'belum_lunas'" />
                        <br>
                        <small class="text-muted">Iuran {{ $iuranBulanIni->periode_text }}</small>
                    @else
                        <span class="badge bg-secondary">Belum Ada Data</span>
                        <br>
                        <small class="text-muted">Bulan ini</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card info-card border-left-info h-100">
                <div class="card-body text-center">
                    <i class="fas fa-medal fa-3x text-info mb-3"></i>
                    <h6 class="card-title">Personal Best</h6>
                    @if($latestPersonalBest)
                        <h6 class="text-info">{{ $latestPersonalBest->jarak }}m {{ $latestPersonalBest->gaya_renang }}</h6>
                        <p class="card-text">{{ $latestPersonalBest->catatan_waktu }}</p>
                    @else
                        <p class="card-text text-muted">Belum ada data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Jadwal Latihan -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Jadwal Latihan 7 Hari ke Depan
                    </h6>
                </div>
                <div class="card-body">
                    @if($upcomingSessions->isEmpty())
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                            <p>Tidak ada jadwal latihan dalam 7 hari ke depan.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Kelas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingSessions as $sesi)
                                        <tr>
                                            <td>{{ formatTanggal($sesi->tanggal) }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($sesi->waktu_mulai)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($sesi->waktu_selesai)->format('H:i') }}
                                            </td>
                                            <td>{{ $sesi->kelas->nama ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Personal Best Records -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy me-2"></i>
                        Catatan Waktu Terbaik
                    </h6>
                    <a href="{{ route('siswa.prestasi.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($personalBests->isEmpty())
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-medal fa-2x mb-2"></i>
                            <p>Belum ada catatan personal best.</p>
                        </div>
                    @else
                        <div class="row">
                            @php $colors = ['success', 'info', 'warning', 'danger', 'primary']; @endphp
                            @foreach($personalBests->take(4) as $i => $pb)
                                <div class="col-md-6">
                                    <div class="card border-left-{{ $colors[$i % count($colors)] }} mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $pb->jarak }}m {{ $pb->gaya_renang }}</h6>
                                            <h4 class="text-{{ $colors[$i % count($colors)] }}">{{ $pb->catatan_waktu }}</h4>
                                            <small class="text-muted">
                                                {{ $pb->keterangan ?? formatTanggal($pb->tanggal) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Payment Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-credit-card me-2"></i>
                        Status Iuran Rutin
                    </h6>
                    <a href="{{ route('siswa.keuangan.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($recentIuran->isEmpty())
                        <p class="text-muted text-center">Belum ada data iuran.</p>
                    @else
                        @foreach($recentIuran as $iuran)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $iuran->periode_text }}</span>
                                    <x-status-badge :status="$iuran->status_bayar === 'lunas' ? 'lunas' : 'belum_lunas'" />
                                </div>
                                @if($iuran->tanggal_bayar)
                                    <small class="text-muted">Dibayar: {{ $iuran->formatted_tanggal_bayar }}</small>
                                @else
                                    <small class="text-danger">Belum dibayar</small>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Rapor Terbaru -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>
                        Rapor Terbaru
                    </h6>
                    <a href="{{ route('siswa.rapor.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if($latestRapor)
                        <h6>Periode: {{ $latestRapor->periode }}</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-success">{{ $latestRapor->teknik_renang }}</h4>
                                    <small class="text-muted">Teknik</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info">{{ $latestRapor->kondisi_fisik }}</h4>
                                <small class="text-muted">Fisik</small>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-warning">{{ $latestRapor->kedisiplinan }}</h4>
                                    <small class="text-muted">Kedisiplinan</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-danger">{{ $latestRapor->semangat_berlatih }}</h4>
                                <small class="text-muted">Semangat</small>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h5 class="text-primary">Rata-rata: {{ number_format($latestRapor->rata_rata, 1) }}</h5>
                            <x-status-badge :status="$latestRapor->status" />
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                            <p>Belum ada rapor.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.siswa>
