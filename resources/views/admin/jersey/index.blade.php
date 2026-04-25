<x-layouts.admin>
    <x-page-header 
        title="Pesanan Jersey"
        subtitle="Kelola pesanan jersey siswa klub renang"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pesanan Jersey', 'url' => '#']
        ]"
    />

    <!-- Stats by Size -->
    <div class="row mb-4">
        @foreach($statsBySize as $ukuran)
            <div class="col-md-2 col-sm-4 mb-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body text-center">
                        <div class="h4 font-weight-bold text-primary">{{ $ukuran->ukuran }}</div>
                        <div class="text-xs text-uppercase text-muted mb-1">Total Pesanan</div>
                        <div class="h5 font-weight-bold">{{ $ukuran->total_pesanan }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pesanan Jersey</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.jersey.report') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-chart-bar me-1"></i> Laporan
                </a>
                <a href="{{ route('admin.jersey.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Pesanan
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter -->
            <form method="GET" action="{{ route('admin.jersey.index') }}" class="mb-4">
                <div class="row g-2">
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
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="dipesan"    {{ request('status') == 'dipesan'    ? 'selected' : '' }}>Dipesan</option>
                            <option value="diterima"   {{ request('status') == 'diterima'   ? 'selected' : '' }}>Diterima</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.jersey.index') }}" class="btn btn-secondary w-100"><i class="fas fa-times"></i> Reset</a>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($jerseys->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Ukuran</th>
                                <th>Tanggal Pesan</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th width="160">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jerseys as $jersey)
                                <tr>
                                    <td>{{ $loop->iteration + ($jerseys->currentPage() - 1) * $jerseys->perPage() }}</td>
                                    <td><strong>{{ $jersey->siswa->nama }}</strong></td>
                                    <td>{{ $jersey->siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary fs-6">
                                            {{ $jersey->masterUkuranJersey->ukuran ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ formatTanggal($jersey->tanggal_pesan) }}</td>
                                    <td>
                                        @if($jersey->status == 'dipesan')
                                            <span class="badge bg-warning text-dark">Dipesan</span>
                                        @elseif($jersey->status == 'diterima')
                                            <span class="badge bg-success">Diterima</span>
                                        @else
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td>{{ $jersey->catatan ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.jersey.show', $jersey->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($jersey->status === 'dipesan')
                                            {{-- Tandai Diterima --}}
                                            <form action="{{ route('admin.jersey.updateStatus', $jersey->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="diterima">
                                                <button type="submit" class="btn btn-sm btn-success" title="Tandai Diterima"
                                                    onclick="return confirm('Tandai jersey ini sudah diterima?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            {{-- Batalkan --}}
                                            <form action="{{ route('admin.jersey.updateStatus', $jersey->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="dibatalkan">
                                                <button type="submit" class="btn btn-sm btn-secondary" title="Batalkan"
                                                    onclick="return confirm('Batalkan pesanan jersey ini?')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.jersey.edit', $jersey->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($jersey->status !== 'diterima')
                                            <form action="{{ route('admin.jersey.destroy', $jersey->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $jerseys->appends(request()->query())->links() }}</div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tshirt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data pesanan jersey</p>
                    <a href="{{ route('admin.jersey.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Pesanan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Siswa Belum Pesan Jersey -->
    @if($siswaWithoutJersey->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Siswa Belum Pesan Jersey ({{ $siswaWithoutJersey->count() }} siswa)
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswaWithoutJersey as $siswa)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.jersey.create') }}?siswa_id={{ $siswa->id }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i> Buat Pesanan
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</x-layouts.admin>
