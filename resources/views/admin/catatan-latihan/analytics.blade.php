<x-layouts.admin>
    <x-page-header 
        title="Analitik Catatan Latihan"
        subtitle="Tren dan statistik waktu latihan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Catatan Latihan', 'url' => route('admin.catatan-latihan.index')],
            ['title' => 'Analitik', 'url' => '#']
        ]"
    />

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Analitik</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.catatan-latihan.analytics') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" id="siswa_id" class="form-control" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaList as $s)
                                <option value="{{ $s->id }}" {{ request('siswa_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="gaya_renang" class="form-label">Gaya Renang <span class="text-danger">*</span></label>
                        <select name="gaya_renang" id="gaya_renang" class="form-control" required>
                            <option value="">-- Pilih Gaya --</option>
                            @foreach($gayaRenangList as $g)
                                <option value="{{ $g }}" {{ request('gaya_renang') == $g ? 'selected' : '' }}>
                                    {{ $g }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="jarak" class="form-label">Jarak <span class="text-danger">*</span></label>
                        <select name="jarak" id="jarak" class="form-control" required>
                            <option value="">-- Pilih Jarak --</option>
                            @foreach($jarakList as $j)
                                <option value="{{ $j }}" {{ request('jarak') == $j ? 'selected' : '' }}>
                                    {{ $j }}m
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($siswa))
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Latihan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $catatanLatihan->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-list fa-2x text-gray-300"></i>
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
                                    Waktu Rata-rata
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $averageTime }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                    Waktu Terbaik
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $catatanLatihan->min('catatan_waktu') ?? '-' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-trophy fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Siswa</th>
                        <td><strong>{{ $siswa->nama }}</strong></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Latihan</th>
                        <td><strong>{{ $gayaRenang }} {{ $jarak }}m</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Riwayat Latihan -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Latihan & Tren Waktu</h6>
            </div>
            <div class="card-body">
                @if($catatanLatihan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Sesi</th>
                                    <th>Waktu</th>
                                    <th>Catatan</th>
                                    <th>Coach</th>
                                    <th>Tren</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $previousTime = null;
                                @endphp
                                @foreach($catatanLatihan as $index => $catatan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ formatTanggal($catatan->sesi->tanggal) }}</td>
                                        <td>{{ $catatan->sesi->kelas->nama }}</td>
                                        <td>
                                            <strong class="text-primary">{{ $catatan->catatan_waktu }}</strong>
                                        </td>
                                        <td>{{ $catatan->catatan ?? '-' }}</td>
                                        <td>{{ $catatan->coach->name }}</td>
                                        <td>
                                            @if($previousTime !== null)
                                                @php
                                                    $currentSeconds = $catatan->waktu_in_seconds;
                                                    $diff = $currentSeconds - $previousTime;
                                                @endphp
                                                @if($diff < 0)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-arrow-down"></i> Lebih Cepat
                                                    </span>
                                                @elseif($diff > 0)
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-arrow-up"></i> Lebih Lambat
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-minus"></i> Sama
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-light text-dark">-</span>
                                            @endif
                                            @php
                                                $previousTime = $catatan->waktu_in_seconds;
                                            @endphp
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Analisis:</strong> 
                        @if($catatanLatihan->count() >= 2)
                            @php
                                $firstTime = $catatanLatihan->last()->waktu_in_seconds;
                                $lastTime = $catatanLatihan->first()->waktu_in_seconds;
                                $improvement = $firstTime - $lastTime;
                            @endphp
                            @if($improvement > 0)
                                Siswa menunjukkan peningkatan dengan waktu lebih cepat {{ abs(round($improvement, 2)) }} detik dari latihan pertama ke terakhir.
                            @elseif($improvement < 0)
                                Waktu siswa {{ abs(round($improvement, 2)) }} detik lebih lambat dari latihan pertama. Perlu evaluasi teknik dan kondisi fisik.
                            @else
                                Waktu siswa konsisten antara latihan pertama dan terakhir.
                            @endif
                        @else
                            Belum cukup data untuk analisis tren. Minimal 2 catatan latihan diperlukan.
                        @endif
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Belum ada catatan latihan untuk nomor ini.
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="text-center">
        <a href="{{ route('admin.catatan-latihan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</x-layouts.admin>
