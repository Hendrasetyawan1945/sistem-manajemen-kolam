<x-layouts.admin>
    <x-page-header 
        title="Detail Kejuaraan"
        subtitle="Informasi lengkap kejuaraan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Kejuaraan', 'url' => route('admin.kejuaraan.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="row">
        <!-- Informasi Kejuaraan -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Kejuaraan</h6>
                    <div>
                        <a href="{{ route('admin.kejuaraan.edit', $kejuaraan) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.kejuaraan.destroy', $kejuaraan) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus kejuaraan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Nama Kejuaraan</th>
                            <td>{{ $kejuaraan->nama }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td>{{ formatTanggal($kejuaraan->tanggal_mulai) }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>{{ formatTanggal($kejuaraan->tanggal_selesai) }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $kejuaraan->lokasi }}</td>
                        </tr>
                        <tr>
                            <th>Biaya Pendaftaran</th>
                            <td>{{ formatRupiah($kejuaraan->biaya_pendaftaran) }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $kejuaraan->deskripsi ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistik Peserta -->
        <div class="col-md-4">
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Total Peserta
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPeserta }}</div>
                </div>
            </div>

            <div class="card shadow mb-4 border-left-success">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Sudah Lunas
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLunas }}</div>
                </div>
            </div>

            <div class="card shadow mb-4 border-left-warning">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Belum Lunas
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBelum }}</div>
                </div>
            </div>

            <div class="card shadow mb-4 border-left-info">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Total Terkumpul
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ formatRupiah($totalTerkumpul) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Peserta -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Peserta</h6>
            <a href="{{ route('admin.iuran-kejuaraan.create', ['kejuaraan_id' => $kejuaraan->id]) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Peserta
            </a>
        </div>
        <div class="card-body">
            @if($iuranKejuaraan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Status Bayar</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode Pembayaran</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($iuranKejuaraan as $index => $iuran)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $iuran->siswa->nama }}</td>
                                    <td>{{ $iuran->siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        @if($iuran->status_bayar === 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td>{{ $iuran->tanggal_bayar ? formatTanggal($iuran->tanggal_bayar) : '-' }}</td>
                                    <td>{{ $iuran->metodePembayaran->nama ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.iuran-kejuaraan.edit', $iuran) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada peserta terdaftar untuk kejuaraan ini.
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
