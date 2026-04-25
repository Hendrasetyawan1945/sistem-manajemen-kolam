<x-layouts.coach>
    <x-page-header 
        title="Detail Kelas: {{ $kelas->nama }}"
        subtitle="Informasi lengkap kelas dan daftar siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Kelas', 'url' => route('coach.kelas.index')],
            ['title' => $kelas->nama, 'url' => '#']
        ]"
    />

    <div class="row">
        <!-- Info Kelas -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Kelas</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th>Nama Kelas</th>
                            <td>{{ $kelas->nama }}</td>
                        </tr>
                        <tr>
                            <th>Jadwal</th>
                            <td>{{ $kelas->jadwal ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kapasitas</th>
                            <td>{{ $totalSiswa }}/{{ $kelas->kapasitas }}</td>
                        </tr>
                        <tr>
                            <th>Siswa Aktif</th>
                            <td><span class="badge bg-success">{{ $siswaAktif }}</span></td>
                        </tr>
                        <tr>
                            <th>Biaya Bulanan</th>
                            <td>{{ formatRupiah($kelas->biaya_bulanan) }}</td>
                        </tr>
                        <tr>
                            <th>Total Sesi</th>
                            <td>{{ $totalSesi }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($kelas->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    @if($kelas->deskripsi)
                        <hr>
                        <p class="text-muted small">{{ $kelas->deskripsi }}</p>
                    @endif

                    <div class="mt-3">
                        <div class="progress mb-1" style="height: 8px;">
                            <div class="progress-bar bg-{{ $kapasitasTerisi >= 100 ? 'danger' : ($kapasitasTerisi >= 80 ? 'warning' : 'success') }}" 
                                 style="width: {{ min($kapasitasTerisi, 100) }}%"></div>
                        </div>
                        <small class="text-muted">{{ round($kapasitasTerisi) }}% kapasitas terisi</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('coach.sesi.create', ['kelas_id' => $kelas->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-calendar-plus me-1"></i> Buat Sesi Latihan
                    </a>
                    <a href="{{ route('coach.sesi.index', ['kelas_id' => $kelas->id]) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-list me-1"></i> Lihat Semua Sesi
                    </a>
                    <a href="{{ route('coach.kehadiran.index', ['kelas_id' => $kelas->id]) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-check-circle me-1"></i> Rekap Kehadiran
                    </a>
                    <a href="{{ route('coach.rapor.create') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-file-alt me-1"></i> Buat Rapor
                    </a>
                </div>
            </div>
        </div>

        <!-- Daftar Siswa -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-1"></i>
                        Daftar Siswa ({{ $totalSiswa }})
                    </h6>
                </div>
                <div class="card-body">
                    @if($kelas->siswa->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kelas->siswa as $index => $siswa)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @if($siswa->foto)
                                                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama }}" class="rounded-circle me-2" width="28" height="28" style="object-fit: cover;">
                                                @endif
                                                <strong>{{ $siswa->nama }}</strong>
                                            </td>
                                            <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                            <td>
                                                @if($siswa->status == 'aktif')
                                                    <span class="badge bg-success">Aktif</span>
                                                @elseif($siswa->status == 'cuti')
                                                    <span class="badge bg-warning">Cuti</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Belum ada siswa di kelas ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sesi Terbaru -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-calendar me-1"></i>
                        Sesi Latihan Terbaru
                    </h6>
                    <a href="{{ route('coach.sesi.index', ['kelas_id' => $kelas->id]) }}" class="btn btn-sm btn-outline-info">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($kelas->sesi->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kelas->sesi as $sesi)
                                        <tr>
                                            <td>{{ formatTanggal($sesi->tanggal) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($sesi->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->waktu_selesai)->format('H:i') }}</td>
                                            <td>{{ $sesi->catatan ?? '-' }}</td>
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
                    @else
                        <p class="text-muted text-center py-3">Belum ada sesi latihan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('coach.kelas.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kelas
        </a>
    </div>
</x-layouts.coach>
