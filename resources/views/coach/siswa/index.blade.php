<x-layouts.coach>
    <x-page-header
        title="Daftar Siswa"
        subtitle="Siswa di kelas yang Anda ampu"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Siswa', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa Kelas Saya</h6>
        </div>
        <div class="card-body">
            {{-- Filter --}}
            <form method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama siswa atau orang tua..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="kelas_id" class="form-select">
                            <option value="">Semua Kelas Saya</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="cuti"     {{ request('status') == 'cuti'     ? 'selected' : '' }}>Cuti</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('coach.siswa.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($siswa->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Orang Tua</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $item)
                                <tr>
                                    <td width="60">
                                        @if($item->foto)
                                            <img src="{{ Storage::url($item->foto) }}" class="rounded-circle" width="40" height="40" style="object-fit:cover">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width:40px;height:40px;font-size:16px">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->nama }}</strong>
                                        <br><small class="text-muted">{{ formatTanggal($item->tanggal_lahir) }}</small>
                                    </td>
                                    <td>{{ $item->kelas->nama ?? '-' }}</td>
                                    <td>{{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $item->nama_ortu }}</td>
                                    <td>{{ $item->telepon_ortu }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status_badge }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('coach.siswa.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $siswa->appends(request()->query())->links() }}</div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada siswa ditemukan</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.coach>
