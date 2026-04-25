<x-layouts.coach>
    <x-page-header 
        title="Personal Best"
        subtitle="Catatan waktu terbaik siswa di kelas Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Personal Best', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Personal Best</h6>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('coach.personal-best.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
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
                        <select name="gaya_renang" class="form-control">
                            <option value="">Semua Gaya</option>
                            @foreach($gayaRenangList as $gaya)
                                <option value="{{ $gaya }}" {{ request('gaya_renang') == $gaya ? 'selected' : '' }}>{{ $gaya }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
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
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personalBest as $item)
                                <tr>
                                    <td><strong>{{ $item->siswa->nama }}</strong></td>
                                    <td>{{ $item->siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->gaya_renang }}</span>
                                        <span class="badge bg-secondary">{{ $item->jarak }}m</span>
                                    </td>
                                    <td>
                                        <code class="text-success fs-6">{{ $item->catatan_waktu }}</code>
                                    </td>
                                    <td>{{ $item->tanggal ? formatTanggal($item->tanggal) : '-' }}</td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
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
                    <p class="text-muted">Belum ada data Personal Best untuk siswa di kelas Anda.</p>
                    <p class="text-muted small">Personal Best akan otomatis tercatat saat Anda menambahkan catatan waktu lomba.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.coach>
