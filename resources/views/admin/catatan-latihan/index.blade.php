<x-layouts.admin>
    <x-page-header 
        title="Catatan Latihan"
        subtitle="Kelola catatan waktu latihan siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Catatan Latihan', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Catatan Latihan</h6>
            <a href="{{ route('admin.catatan-latihan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Catatan
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.catatan-latihan.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="siswa_id" class="form-label">Siswa</label>
                        <select name="siswa_id" id="siswa_id" class="form-control">
                            <option value="">Semua Siswa</option>
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id }}" {{ request('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="gaya_renang" class="form-label">Gaya</label>
                        <select name="gaya_renang" id="gaya_renang" class="form-control">
                            <option value="">Semua Gaya</option>
                            @foreach($gayaRenangList as $gaya)
                                <option value="{{ $gaya }}" {{ request('gaya_renang') == $gaya ? 'selected' : '' }}>
                                    {{ $gaya }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="jarak" class="form-label">Jarak</label>
                        <select name="jarak" id="jarak" class="form-control">
                            <option value="">Semua</option>
                            @foreach($jarakList as $j)
                                <option value="{{ $j }}" {{ request('jarak') == $j ? 'selected' : '' }}>
                                    {{ $j }}m
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.catatan-latihan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Analytics Link -->
            <div class="mb-3">
                <a href="{{ route('admin.catatan-latihan.analytics', request()->all()) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-chart-line me-1"></i> Lihat Analitik & Tren
                </a>
            </div>

            <x-alert />

            @if($catatanLatihan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Nomor Latihan</th>
                                <th>Waktu</th>
                                <th>Coach</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($catatanLatihan as $item)
                                <tr>
                                    <td>{{ formatTanggal($item->sesi->tanggal) }}</td>
                                    <td>{{ $item->siswa->nama }}</td>
                                    <td>{{ $item->siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <strong>{{ $item->gaya_renang }} {{ $item->jarak }}m</strong>
                                    </td>
                                    <td><strong class="text-primary">{{ $item->catatan_waktu }}</strong></td>
                                    <td>{{ $item->coach->name }}</td>
                                    <td>
                                        <div class="d-flex flex-nowrap gap-1">
                                            <a href="{{ route('admin.catatan-latihan.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.catatan-latihan.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.catatan-latihan.destroy', $item->id) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    {{ $catatanLatihan->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-swimming-pool fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada catatan latihan</p>
                    <a href="{{ route('admin.catatan-latihan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Catatan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
