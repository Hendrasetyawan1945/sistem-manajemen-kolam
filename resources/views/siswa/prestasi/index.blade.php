<x-layouts.siswa>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-medal me-2 text-warning"></i>
            Prestasi Saya
        </h4>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('siswa.prestasi.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Gaya Renang</label>
                    <select name="gaya_renang" class="form-select">
                        <option value="">Semua Gaya</option>
                        @foreach($gayaList as $gaya)
                            <option value="{{ $gaya }}" {{ $filterGaya == $gaya ? 'selected' : '' }}>
                                {{ $gaya }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jarak (meter)</label>
                    <select name="jarak" class="form-select">
                        <option value="">Semua Jarak</option>
                        @foreach($jarakList as $jarak)
                            <option value="{{ $jarak }}" {{ $filterJarak == $jarak ? 'selected' : '' }}>
                                {{ $jarak }}m
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Personal Best Cards -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-trophy me-2"></i>
                Personal Best
            </h6>
        </div>
        <div class="card-body">
            @if($personalBests->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-medal fa-3x mb-3"></i>
                    <p>Belum ada catatan personal best.</p>
                </div>
            @else
                <div class="row">
                    @php $colors = ['success', 'info', 'warning', 'danger', 'primary']; @endphp
                    @foreach($personalBests as $i => $pb)
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="card border-left-{{ $colors[$i % count($colors)] }} h-100">
                                <div class="card-body text-center">
                                    <div class="mb-2">
                                        <span class="badge bg-{{ $colors[$i % count($colors)] }}">
                                            {{ $pb->jarak }}m
                                        </span>
                                    </div>
                                    <h6 class="card-title">{{ $pb->gaya_renang }}</h6>
                                    <h3 class="text-{{ $colors[$i % count($colors)] }} fw-bold">
                                        {{ $pb->catatan_waktu }}
                                    </h3>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ formatTanggal($pb->tanggal) }}
                                    </small>
                                    @if($pb->keterangan)
                                        <p class="text-muted small mt-1 mb-0">{{ $pb->keterangan }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Catatan Waktu Lomba -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-stopwatch me-2"></i>
                Riwayat Catatan Waktu Lomba
            </h6>
        </div>
        <div class="card-body">
            @if($catatanWaktu->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="fas fa-stopwatch fa-3x mb-3"></i>
                    <p>Belum ada catatan waktu lomba.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kejuaraan</th>
                                <th>Nomor Lomba</th>
                                <th>Gaya</th>
                                <th>Jarak</th>
                                <th>Waktu</th>
                                <th>Posisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($catatanWaktu as $i => $cw)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $cw->kejuaraan->nama ?? '-' }}</td>
                                    <td>{{ $cw->nomor_lomba ?? '-' }}</td>
                                    <td>{{ $cw->gaya_renang ?? '-' }}</td>
                                    <td>{{ $cw->jarak ? $cw->jarak . 'm' : '-' }}</td>
                                    <td>
                                        <strong>{{ $cw->catatan_waktu }}</strong>
                                    </td>
                                    <td>
                                        @if($cw->posisi)
                                            @if($cw->posisi == 1)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-trophy me-1"></i> Juara 1
                                                </span>
                                            @elseif($cw->posisi == 2)
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-medal me-1"></i> Juara 2
                                                </span>
                                            @elseif($cw->posisi == 3)
                                                <span class="badge" style="background-color: #cd7f32; color: white;">
                                                    <i class="fas fa-medal me-1"></i> Juara 3
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">Posisi {{ $cw->posisi }}</span>
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
</x-layouts.siswa>
