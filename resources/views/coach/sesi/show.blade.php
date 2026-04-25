<x-layouts.coach>
    <x-page-header 
        title="Detail Sesi Latihan"
        subtitle="{{ $sesi->kelas->nama }} - {{ formatTanggal($sesi->tanggal) }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Sesi Latihan', 'url' => route('coach.sesi.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <x-alert />

    <div class="row">
        <!-- Info Sesi -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Sesi</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th>Kelas</th>
                            <td><strong>{{ $sesi->kelas->nama }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ formatTanggal($sesi->tanggal) }}</td>
                        </tr>
                        <tr>
                            <th>Waktu</th>
                            <td>{{ \Carbon\Carbon::parse($sesi->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->waktu_selesai)->format('H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $sesi->catatan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Statistik Kehadiran -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Statistik Kehadiran</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h4 text-success">{{ $hadir }}</div>
                            <small class="text-muted">Hadir</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 text-warning">{{ $izin }}</div>
                            <small class="text-muted">Izin</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-info">{{ $sakit }}</div>
                            <small class="text-muted">Sakit</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-danger">{{ $alpha }}</div>
                            <small class="text-muted">Alpha</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="h5 text-primary">{{ round($persentaseHadir, 1) }}%</div>
                        <small class="text-muted">Persentase Kehadiran</small>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-{{ $persentaseHadir >= 75 ? 'success' : 'danger' }}" style="width: {{ $persentaseHadir }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Input Kehadiran -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-check-circle me-1"></i>
                        Input Kehadiran Siswa
                    </h6>
                </div>
                <div class="card-body">
                    @if($sesi->kehadiran->count() > 0)
                        <form action="{{ route('coach.sesi.updateAttendance', $sesi->id) }}" method="POST">
                            @csrf

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Nama Siswa</th>
                                            <th width="200">Status Kehadiran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sesi->kehadiran as $index => $kehadiran)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if($kehadiran->siswa->foto)
                                                        <img src="{{ asset('storage/' . $kehadiran->siswa->foto) }}" alt="{{ $kehadiran->siswa->nama }}" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                                    @endif
                                                    <strong>{{ $kehadiran->siswa->nama }}</strong>
                                                </td>
                                                <td>
                                                    <select name="kehadiran[{{ $kehadiran->id }}]" class="form-control form-control-sm" required>
                                                        <option value="hadir" {{ $kehadiran->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                        <option value="izin" {{ $kehadiran->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                                        <option value="sakit" {{ $kehadiran->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                        <option value="alpha" {{ $kehadiran->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Kehadiran
                                </button>
                                <a href="{{ route('coach.sesi.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Tidak ada siswa terdaftar di sesi ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.coach>
