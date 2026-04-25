<x-layouts.admin>
    <x-page-header 
        title="Manajemen Sesi Latihan"
        subtitle="Kelola jadwal sesi latihan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Sesi Latihan', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Sesi Latihan</h6>
            <a href="{{ route('admin.sesi.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Buat Sesi Baru
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.sesi.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="kelas_id" class="form-control">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="tanggal_dari" class="form-control" placeholder="Dari Tanggal" value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="tanggal_sampai" class="form-control" placeholder="Sampai Tanggal" value="{{ request('tanggal_sampai') }}">
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
            @if($sesi->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Coach</th>
                                <th>Waktu</th>
                                <th>Catatan</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sesi as $item)
                                <tr>
                                    <td>{{ formatTanggal($item->tanggal) }}</td>
                                    <td><strong>{{ $item->kelas->nama }}</strong></td>
                                    <td>{{ $item->coach->name }}</td>
                                    <td>{{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}</td>
                                    <td>{{ $item->catatan ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex flex-nowrap gap-1">
                                            <a href="{{ route('admin.sesi.attendance', $item->id) }}" class="btn btn-sm btn-success" title="Input Kehadiran">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <a href="{{ route('admin.sesi.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.sesi.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.sesi.destroy', $item->id) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus sesi ini?')">
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

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $sesi->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada sesi latihan</p>
                    <a href="{{ route('admin.sesi.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Buat Sesi Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
