<x-layouts.admin>
    <x-page-header 
        title="Detail Catatan Latihan"
        subtitle="Informasi lengkap catatan latihan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Catatan Latihan', 'url' => route('admin.catatan-latihan.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Catatan Latihan</h6>
            <div>
                <a href="{{ route('admin.catatan-latihan.edit', $catatanLatihan) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.catatan-latihan.destroy', $catatanLatihan) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus catatan latihan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Sesi Latihan</th>
                            <td>
                                <strong>{{ $catatanLatihan->sesi->kelas->nama }}</strong>
                                <br><small class="text-muted">{{ formatTanggal($catatanLatihan->sesi->tanggal) }} {{ $catatanLatihan->sesi->waktu_mulai }} - {{ $catatanLatihan->sesi->waktu_selesai }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Siswa</th>
                            <td>{{ $catatanLatihan->siswa->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $catatanLatihan->siswa->kelas->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Latihan</th>
                            <td><strong>{{ $catatanLatihan->gaya_renang }} {{ $catatanLatihan->jarak }}m</strong></td>
                        </tr>
                        <tr>
                            <th>Catatan Waktu</th>
                            <td><h4 class="text-primary mb-0">{{ $catatanLatihan->catatan_waktu }}</h4></td>
                        </tr>
                        <tr>
                            <th>Coach</th>
                            <td>{{ $catatanLatihan->coach->name }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $catatanLatihan->catatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dicatat</th>
                            <td>{{ formatTanggal($catatanLatihan->created_at) }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-swimming-pool fa-3x text-primary mb-3"></i>
                            <h6 class="text-muted">Catatan Waktu</h6>
                            <h2 class="text-primary font-weight-bold">{{ $catatanLatihan->catatan_waktu }}</h2>
                            <p class="text-muted mb-0">{{ $catatanLatihan->gaya_renang }} {{ $catatanLatihan->jarak }}m</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('admin.catatan-latihan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        <a href="{{ route('admin.catatan-latihan.analytics', ['siswa_id' => $catatanLatihan->siswa_id, 'gaya_renang' => $catatanLatihan->gaya_renang, 'jarak' => $catatanLatihan->jarak]) }}" class="btn btn-info">
            <i class="fas fa-chart-line me-1"></i> Lihat Analitik
        </a>
    </div>
</x-layouts.admin>
