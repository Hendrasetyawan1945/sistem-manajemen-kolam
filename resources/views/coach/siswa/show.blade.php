<x-layouts.coach>
    <x-page-header
        title="Detail Siswa"
        subtitle="{{ $siswa->nama }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Siswa', 'url' => route('coach.siswa.index')],
            ['title' => $siswa->nama, 'url' => '#']
        ]"
    />

    <div class="row">
        {{-- Info Siswa --}}
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    @if($siswa->foto)
                        <img src="{{ Storage::url($siswa->foto) }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit:cover">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mx-auto mb-3" style="width:100px;height:100px;font-size:36px">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <h5 class="fw-bold">{{ $siswa->nama }}</h5>
                    <span class="badge bg-{{ $siswa->status_badge }} mb-2">{{ ucfirst($siswa->status) }}</span>
                    <p class="text-muted small mb-0">{{ $siswa->kelas->nama ?? '-' }}</p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pribadi</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr><td class="text-muted">Tanggal Lahir</td><td>{{ formatTanggal($siswa->tanggal_lahir) }}</td></tr>
                        <tr><td class="text-muted">Jenis Kelamin</td><td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                        <tr><td class="text-muted">Alamat</td><td>{{ $siswa->alamat ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Orang Tua</td><td>{{ $siswa->nama_ortu }}</td></tr>
                        <tr><td class="text-muted">Telepon</td><td>{{ $siswa->telepon_ortu }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            {{-- Statistik Kehadiran --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Kehadiran</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-3">
                            <div class="h4 fw-bold text-success">{{ $siswa->kehadiran->where('status','hadir')->count() }}</div>
                            <small class="text-muted">Hadir</small>
                        </div>
                        <div class="col-3">
                            <div class="h4 fw-bold text-info">{{ $siswa->kehadiran->where('status','izin')->count() }}</div>
                            <small class="text-muted">Izin</small>
                        </div>
                        <div class="col-3">
                            <div class="h4 fw-bold text-warning">{{ $siswa->kehadiran->where('status','sakit')->count() }}</div>
                            <small class="text-muted">Sakit</small>
                        </div>
                        <div class="col-3">
                            <div class="h4 fw-bold text-danger">{{ $siswa->kehadiran->where('status','alpha')->count() }}</div>
                            <small class="text-muted">Alpha</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Persentase Kehadiran</span>
                        <strong class="text-{{ $attendance >= 80 ? 'success' : ($attendance >= 60 ? 'warning' : 'danger') }}">
                            {{ $attendance }}%
                        </strong>
                    </div>
                    <div class="progress" style="height:10px">
                        <div class="progress-bar bg-{{ $attendance >= 80 ? 'success' : ($attendance >= 60 ? 'warning' : 'danger') }}"
                             style="width:{{ $attendance }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Personal Best --}}
            @if($siswa->personalBest->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Best</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Gaya Renang</th>
                                    <th>Jarak</th>
                                    <th>Waktu Terbaik</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa->personalBest as $pb)
                                    <tr>
                                        <td>{{ $pb->gaya_renang }}</td>
                                        <td>{{ $pb->jarak }}m</td>
                                        <td><strong class="text-primary">{{ $pb->catatan_waktu }}</strong></td>
                                        <td>{{ formatTanggal($pb->tanggal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Rapor Terbaru --}}
            @if($siswa->rapor->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rapor Terbaru</h6>
                </div>
                <div class="card-body">
                    @php $latestRapor = $siswa->rapor->sortByDesc('periode')->first(); @endphp
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="h5 fw-bold text-primary">{{ $latestRapor->nilai_teknik }}</div>
                            <small class="text-muted">Teknik</small>
                        </div>
                        <div class="col-3">
                            <div class="h5 fw-bold text-success">{{ $latestRapor->nilai_fisik }}</div>
                            <small class="text-muted">Fisik</small>
                        </div>
                        <div class="col-3">
                            <div class="h5 fw-bold text-warning">{{ $latestRapor->nilai_kedisiplinan }}</div>
                            <small class="text-muted">Disiplin</small>
                        </div>
                        <div class="col-3">
                            <div class="h5 fw-bold text-info">{{ $latestRapor->nilai_semangat }}</div>
                            <small class="text-muted">Semangat</small>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Periode: {{ $latestRapor->periode }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <a href="{{ route('coach.siswa.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</x-layouts.coach>
