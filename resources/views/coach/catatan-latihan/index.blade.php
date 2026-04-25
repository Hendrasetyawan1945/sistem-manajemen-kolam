<x-layouts.coach>
    <x-page-header 
        title="Catatan Latihan"
        subtitle="Catatan waktu latihan siswa di kelas Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Catatan Latihan', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Catatan Latihan</h6>
            <a href="{{ route('coach.catatan-latihan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Catatan
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('coach.catatan-latihan.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <select name="siswa_id" class="form-control">
                            <option value="">Semua Siswa</option>
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id }}" {{ request('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama }} ({{ $siswa->kelas->nama ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="gaya_renang" class="form-control">
                            <option value="">Semua Gaya</option>
                            @foreach($gayaRenangList as $gaya)
                                <option value="{{ $gaya }}" {{ request('gaya_renang') == $gaya ? 'selected' : '' }}>{{ $gaya }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="jarak" class="form-control">
                            <option value="">Semua Jarak</option>
                            @foreach($jarakList as $jarak)
                                <option value="{{ $jarak }}" {{ request('jarak') == $jarak ? 'selected' : '' }}>{{ $jarak }}m</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="tanggal_mulai" class="form-control" placeholder="Dari Tanggal" value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="tanggal_selesai" class="form-control" placeholder="Sampai Tanggal" value="{{ request('tanggal_selesai') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($catatanLatihan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Sesi</th>
                                <th>Nomor Latihan</th>
                                <th>Waktu</th>
                                <th>Catatan</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($catatanLatihan as $item)
                                <tr>
                                    <td><strong>{{ $item->siswa->nama }}</strong></td>
                                    <td>{{ $item->siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        @if($item->sesi)
                                            {{ formatTanggal($item->sesi->tanggal) }}
                                            <br><small class="text-muted">{{ $item->sesi->kelas->nama ?? '-' }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->gaya_renang }}</span>
                                        <span class="badge bg-secondary">{{ $item->jarak }}m</span>
                                    </td>
                                    <td><code>{{ $item->catatan_waktu }}</code></td>
                                    <td>{{ $item->catatan ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('coach.catatan-latihan.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('coach.catatan-latihan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada catatan latihan</p>
                    <a href="{{ route('coach.catatan-latihan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Catatan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.coach>
