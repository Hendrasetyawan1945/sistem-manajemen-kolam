<x-layouts.admin>
    <x-page-header 
        title="Pengeluaran Klub"
        subtitle="Kelola pengeluaran operasional klub"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pengeluaran', 'url' => '#']
        ]"
    />

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Pengeluaran (Semua)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ formatRupiah($totalPengeluaran) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                Total Pengeluaran (Filter)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ formatRupiah($totalFiltered) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-filter fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengeluaran by Category -->
    @if($totalByCategory->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pengeluaran per Kategori</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($totalByCategory as $category)
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        {{ $category->itemKas->nama ?? 'Tanpa Kategori' }}
                                    </div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">{{ formatRupiah($category->total) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengeluaran</h6>
            <a href="{{ route('admin.pengeluaran.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Pengeluaran
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.pengeluaran.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="item_kas_id" class="form-label">Kategori</label>
                        <select name="item_kas_id" id="item_kas_id" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach($itemKasList as $item)
                                <option value="{{ $item->id }}" {{ request('item_kas_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($pengeluaran->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Pengeluaran</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>Dibuat Oleh</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengeluaran as $item)
                                <tr>
                                    <td>{{ formatTanggal($item->tanggal) }}</td>
                                    <td><strong>{{ $item->nama_pengeluaran }}</strong></td>
                                    <td>
                                        @if($item->itemKas)
                                            <span class="badge bg-info">{{ $item->itemKas->nama }}</span>
                                        @else
                                            <span class="badge bg-secondary">Tanpa Kategori</span>
                                        @endif
                                    </td>
                                    <td><strong class="text-danger">{{ formatRupiah($item->jumlah) }}</strong></td>
                                    <td>{{ $item->keterangan ? Str::limit($item->keterangan, 50) : '-' }}</td>
                                    <td>{{ $item->dibuatOleh->name }}</td>
                                    <td>
                                        <div class="d-flex flex-nowrap gap-1">
                                            <a href="{{ route('admin.pengeluaran.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.pengeluaran.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.pengeluaran.destroy', $item->id) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $pengeluaran->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data pengeluaran</p>
                    <a href="{{ route('admin.pengeluaran.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Pengeluaran Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
