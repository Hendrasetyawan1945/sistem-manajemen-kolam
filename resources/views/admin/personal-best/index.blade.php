<x-layouts.admin>
    <x-page-header 
        title="Personal Best"
        subtitle="Catatan waktu terbaik siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Personal Best', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-trophy me-2"></i>Daftar Personal Best
            </h6>
            <a href="{{ route('admin.catatan-waktu.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-stopwatch me-1"></i> Catatan Waktu
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.personal-best.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                            <a href="{{ route('admin.personal-best.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($personalBest->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Nomor Lomba</th>
                                <th>Waktu Terbaik</th>
                                <th>Tanggal Capai</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personalBest as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->siswa->nama }}</strong>
                                    </td>
                                    <td>{{ $item->siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <strong>{{ $item->gaya_renang }} {{ $item->jarak }}m</strong>
                                    </td>
                                    <td>
                                        <h5 class="mb-0 text-success">
                                            <i class="fas fa-trophy me-1"></i>{{ $item->catatan_waktu }}
                                        </h5>
                                    </td>
                                    <td>{{ formatTanggal($item->tanggal) }}</td>
                                    <td>
                                        <a href="{{ route('admin.personal-best.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $personalBest->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada personal best</p>
                    <a href="{{ route('admin.catatan-waktu.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Catatan Waktu
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
