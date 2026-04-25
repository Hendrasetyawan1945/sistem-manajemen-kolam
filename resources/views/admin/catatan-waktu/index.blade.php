<x-layouts.admin>
    <x-page-header 
        title="Catatan Waktu Lomba"
        subtitle="Kelola catatan waktu kompetisi siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Catatan Waktu', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Catatan Waktu</h6>
            <div>
                <a href="{{ route('admin.personal-best.index') }}" class="btn btn-success btn-sm me-2">
                    <i class="fas fa-trophy me-1"></i> Personal Best
                </a>
                <a href="{{ route('admin.catatan-waktu.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Catatan
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.catatan-waktu.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="kejuaraan_id" class="form-label">Kejuaraan</label>
                        <select name="kejuaraan_id" id="kejuaraan_id" class="form-control">
                            <option value="">Semua Kejuaraan</option>
                            @foreach($kejuaraanList as $kejuaraan)
                                <option value="{{ $kejuaraan->id }}" {{ request('kejuaraan_id') == $kejuaraan->id ? 'selected' : '' }}>
                                    {{ $kejuaraan->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
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
                    <div class="col-md-2">
                        <label for="jarak" class="form-label">Jarak</label>
                        <select name="jarak" id="jarak" class="form-control">
                            <option value="">Semua Jarak</option>
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
                            <a href="{{ route('admin.catatan-waktu.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($catatanWaktu->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Kejuaraan</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Nomor Lomba</th>
                                <th>Waktu</th>
                                <th>Posisi</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($catatanWaktu as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->kejuaraan->nama }}</strong>
                                        <br><small class="text-muted">{{ formatTanggal($item->kejuaraan->tanggal_mulai) }}</small>
                                    </td>
                                    <td>{{ $item->siswa->nama }}</td>
                                    <td>{{ $item->siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <strong>{{ $item->gaya_renang }} {{ $item->jarak }}m</strong>
                                    </td>
                                    <td><strong class="text-primary">{{ $item->catatan_waktu }}</strong></td>
                                    <td>
                                        @if($item->posisi)
                                            @if($item->posisi == 1)
                                                <span class="badge bg-warning text-dark"><i class="fas fa-trophy"></i> Juara 1</span>
                                            @elseif($item->posisi == 2)
                                                <span class="badge bg-secondary"><i class="fas fa-medal"></i> Juara 2</span>
                                            @elseif($item->posisi == 3)
                                                <span class="badge bg-info"><i class="fas fa-medal"></i> Juara 3</span>
                                            @else
                                                <span class="badge bg-light text-dark">Posisi {{ $item->posisi }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-nowrap gap-1">
                                            <a href="{{ route('admin.catatan-waktu.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.catatan-waktu.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.catatan-waktu.destroy', $item->id) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    {{ $catatanWaktu->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-stopwatch fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada catatan waktu</p>
                    <a href="{{ route('admin.catatan-waktu.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Catatan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
