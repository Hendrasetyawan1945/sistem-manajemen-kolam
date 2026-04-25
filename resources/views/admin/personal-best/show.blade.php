<x-layouts.admin>
    <x-page-header 
        title="Detail Personal Best"
        subtitle="Informasi lengkap personal best dan riwayat"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Personal Best', 'url' => route('admin.personal-best.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-trophy me-2"></i>Informasi Personal Best
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Siswa</th>
                            <td><strong>{{ $personalBest->siswa->nama }}</strong></td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $personalBest->siswa->kelas->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Lomba</th>
                            <td><strong>{{ $personalBest->gaya_renang }} {{ $personalBest->jarak }}m</strong></td>
                        </tr>
                        <tr>
                            <th>Waktu Terbaik</th>
                            <td><h3 class="text-success mb-0">{{ $personalBest->catatan_waktu }}</h3></td>
                        </tr>
                        <tr>
                            <th>Tanggal Capai</th>
                            <td>{{ formatTanggal($personalBest->tanggal) }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $personalBest->keterangan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow">
                <div class="card-body text-center">
                    <i class="fas fa-trophy fa-4x text-success mb-3"></i>
                    <h6 class="text-muted">Personal Best</h6>
                    <h2 class="text-success font-weight-bold">{{ $personalBest->catatan_waktu }}</h2>
                    <p class="text-muted mb-0">{{ $personalBest->gaya_renang }} {{ $personalBest->jarak }}m</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Catatan Waktu -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Catatan Waktu</h6>
        </div>
        <div class="card-body">
            @if($catatanWaktuHistory->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Kejuaraan</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Waktu</th>
                                <th>Posisi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($catatanWaktuHistory as $catatan)
                                <tr class="{{ $catatan->catatan_waktu == $personalBest->catatan_waktu ? 'table-success' : '' }}">
                                    <td>
                                        <strong>{{ $catatan->kejuaraan->nama }}</strong>
                                    </td>
                                    <td>{{ formatTanggal($catatan->kejuaraan->tanggal_mulai) }}</td>
                                    <td>{{ $catatan->kejuaraan->lokasi }}</td>
                                    <td>
                                        <strong class="{{ $catatan->catatan_waktu == $personalBest->catatan_waktu ? 'text-success' : '' }}">
                                            {{ $catatan->catatan_waktu }}
                                        </strong>
                                    </td>
                                    <td>
                                        @if($catatan->posisi)
                                            @if($catatan->posisi == 1)
                                                <span class="badge bg-warning text-dark"><i class="fas fa-trophy"></i> Juara 1</span>
                                            @elseif($catatan->posisi == 2)
                                                <span class="badge bg-secondary"><i class="fas fa-medal"></i> Juara 2</span>
                                            @elseif($catatan->posisi == 3)
                                                <span class="badge bg-info"><i class="fas fa-medal"></i> Juara 3</span>
                                            @else
                                                <span class="badge bg-light text-dark">Posisi {{ $catatan->posisi }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($catatan->catatan_waktu == $personalBest->catatan_waktu)
                                            <span class="badge bg-success"><i class="fas fa-star"></i> Personal Best</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada riwayat catatan waktu untuk nomor lomba ini.
                </div>
            @endif
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('admin.personal-best.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</x-layouts.admin>
