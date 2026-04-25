<x-layouts.admin>
    <x-page-header 
        title="Iuran Insidentil"
        subtitle="Kelola pembayaran iuran insidentil siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Iuran Insidentil', 'url' => '#']
        ]"
    />

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Lunas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ formatRupiah($totalLunas) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Belum Bayar
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ formatRupiah($totalBelum) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Iuran Insidentil</h6>
            <a href="{{ route('admin.iuran-insidentil.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Iuran
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.iuran-insidentil.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <select name="siswa_id" class="form-control">
                            <option value="">Semua Siswa</option>
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id }}" {{ request('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status_bayar" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="lunas" {{ request('status_bayar') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="belum" {{ request('status_bayar') == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Alert Messages -->
            <x-alert />

            <!-- Data Table -->
            @if($iuranInsidentil->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Item</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($iuranInsidentil as $item)
                                <tr>
                                    <td>{{ formatTanggal($item->tanggal) }}</td>
                                    <td>{{ $item->siswa->nama }}</td>
                                    <td>{{ $item->siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <strong>{{ $item->nama_item }}</strong>
                                        @if($item->catatan)
                                            <br><small class="text-muted">{{ $item->catatan }}</small>
                                        @endif
                                    </td>
                                    <td><strong>{{ formatRupiah($item->jumlah) }}</strong></td>
                                    <td>
                                        @if($item->status_bayar == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->tanggal_bayar ? formatTanggal($item->tanggal_bayar) : '-' }}</td>
                                    <td>
                                        <div class="d-flex flex-nowrap gap-1">
                                            <a href="{{ route('admin.iuran-insidentil.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($item->status_bayar == 'belum')
                                                <form action="{{ route('admin.iuran-insidentil.destroy', $item->id) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $iuranInsidentil->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data iuran insidentil</p>
                    <a href="{{ route('admin.iuran-insidentil.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Iuran Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
