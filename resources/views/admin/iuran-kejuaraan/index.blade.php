<x-layouts.admin>
    <x-page-header 
        title="Iuran Kejuaraan"
        subtitle="Kelola pendaftaran dan pembayaran kejuaraan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Iuran Kejuaraan', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pendaftaran Kejuaraan</h6>
            <a href="{{ route('admin.iuran-kejuaraan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Daftarkan Siswa
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.iuran-kejuaraan.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="kejuaraan_id" class="form-control">
                            <option value="">Semua Kejuaraan</option>
                            @foreach($kejuaraanList as $kejuaraan)
                                <option value="{{ $kejuaraan->id }}" {{ request('kejuaraan_id') == $kejuaraan->id ? 'selected' : '' }}>
                                    {{ $kejuaraan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
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

            <x-alert />

            @if($iuranKejuaraan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Kejuaraan</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Biaya</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($iuranKejuaraan as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->kejuaraan->nama }}</strong>
                                        <br><small class="text-muted">{{ formatTanggal($item->kejuaraan->tanggal_mulai) }}</small>
                                    </td>
                                    <td>{{ $item->siswa->nama }}</td>
                                    <td>{{ $item->siswa->kelas->nama ?? '-' }}</td>
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
                                            <a href="{{ route('admin.iuran-kejuaraan.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.iuran-kejuaraan.destroy', $item->id) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    {{ $iuranKejuaraan->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada pendaftaran kejuaraan</p>
                    <a href="{{ route('admin.iuran-kejuaraan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Daftarkan Siswa Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
