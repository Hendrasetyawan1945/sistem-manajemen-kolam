<x-layouts.coach>
    <x-page-header 
        title="Catatan Waktu Lomba"
        subtitle="Catatan waktu lomba siswa di kelas Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Catatan Waktu', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Catatan Waktu Lomba</h6>
            <a href="{{ route('coach.catatan-waktu.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Catatan
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('coach.catatan-waktu.index') }}" class="mb-4">
                <div class="row">
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
                        <select name="kejuaraan_id" class="form-control">
                            <option value="">Semua Kejuaraan</option>
                            @foreach($kejuaraanList as $kejuaraan)
                                <option value="{{ $kejuaraan->id }}" {{ request('kejuaraan_id') == $kejuaraan->id ? 'selected' : '' }}>
                                    {{ $kejuaraan->nama }}
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
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($catatanWaktu->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Kejuaraan</th>
                                <th>Nomor Lomba</th>
                                <th>Waktu</th>
                                <th>Posisi</th>
                                <th>Keterangan</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($catatanWaktu as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->siswa->nama }}</strong>
                                        <br><small class="text-muted">{{ $item->siswa->kelas->nama ?? '-' }}</small>
                                    </td>
                                    <td>{{ $item->kejuaraan->nama ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->gaya_renang }}</span>
                                        <span class="badge bg-secondary">{{ $item->jarak }}m</span>
                                    </td>
                                    <td><code>{{ $item->catatan_waktu }}</code></td>
                                    <td>
                                        @if($item->posisi)
                                            @if($item->posisi <= 3)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-medal me-1"></i>Juara {{ $item->posisi }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Posisi {{ $item->posisi }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('coach.catatan-waktu.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('coach.catatan-waktu.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
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
                    {{ $catatanWaktu->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-stopwatch fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada catatan waktu lomba</p>
                    <a href="{{ route('coach.catatan-waktu.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Catatan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.coach>
