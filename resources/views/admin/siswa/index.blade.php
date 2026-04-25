<x-layouts.admin>
    <x-page-header 
        title="Manajemen Siswa"
        subtitle="Kelola data siswa klub renang"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Siswa', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
            <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Siswa
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.siswa.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama siswa atau orang tua..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
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
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
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
            @if($siswa->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="80">Foto</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Kelas</th>
                                <th>Orang Tua</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $item)
                                <tr>
                                    <td class="text-center">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->nama }}</strong><br>
                                        <small class="text-muted">{{ formatTanggal($item->tanggal_lahir) }}</small>
                                    </td>
                                    <td>{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $item->kelas->nama ?? '-' }}</td>
                                    <td>{{ $item->nama_ortu }}</td>
                                    <td>{{ $item->telepon_ortu }}</td>
                                    <td>
                                        <x-status-badge :status="$item->status" />
                                    </td>
                                    <td>
                                        <x-action-buttons 
                                            :view-route="route('admin.siswa.show', $item->id)"
                                            :edit-route="route('admin.siswa.edit', $item->id)"
                                            :delete-route="route('admin.siswa.destroy', $item->id)"
                                            delete-confirm="Apakah Anda yakin ingin menghapus siswa {{ $item->nama }}?"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $siswa->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data siswa</p>
                    <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Siswa Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
