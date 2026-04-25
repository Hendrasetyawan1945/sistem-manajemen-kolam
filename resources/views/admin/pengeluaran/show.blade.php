<x-layouts.admin>
    <x-page-header 
        title="Detail Pengeluaran"
        subtitle="Informasi lengkap pengeluaran"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pengeluaran', 'url' => route('admin.pengeluaran.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Pengeluaran</h6>
            <div>
                <a href="{{ route('admin.pengeluaran.edit', $pengeluaran) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.pengeluaran.destroy', $pengeluaran) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')">
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
                            <th width="200">Nama Pengeluaran</th>
                            <td>{{ $pengeluaran->nama_pengeluaran }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                @if($pengeluaran->itemKas)
                                    <span class="badge bg-info">{{ $pengeluaran->itemKas->nama }}</span>
                                @else
                                    <span class="badge bg-secondary">Tanpa Kategori</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td><strong class="text-danger h5">{{ formatRupiah($pengeluaran->jumlah) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ formatTanggal($pengeluaran->tanggal) }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $pengeluaran->keterangan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $pengeluaran->dibuatOleh->nama }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ formatTanggal($pengeluaran->created_at) }}</td>
                        </tr>
                        @if($pengeluaran->updated_at != $pengeluaran->created_at)
                            <tr>
                                <th>Terakhir Diperbarui</th>
                                <td>{{ formatTanggal($pengeluaran->updated_at) }}</td>
                            </tr>
                        @endif
                    </table>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave fa-3x text-danger mb-3"></i>
                            <h6 class="text-muted">Total Pengeluaran</h6>
                            <h3 class="text-danger font-weight-bold">{{ formatRupiah($pengeluaran->jumlah) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</x-layouts.admin>
