<x-layouts.admin>
    <x-page-header 
        title="Manajemen Kelas"
        subtitle="Kelola kelas latihan renang"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Kelas', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kelas</h6>
            <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Kelas
            </a>
        </div>
        <div class="card-body">
            <!-- Alert Messages -->
            <x-alert />

            <!-- Data Table -->
            @if($kelas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Coach</th>
                                <th>Jadwal</th>
                                <th>Kapasitas</th>
                                <th>Biaya Bulanan</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelas as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->nama }}</strong>
                                        @if($item->deskripsi)
                                            <br><small class="text-muted">{{ $item->deskripsi }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-user-tie text-primary me-1"></i>
                                        {{ $item->coach->name }}
                                    </td>
                                    <td>{{ $item->jadwal }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->siswa_count >= $item->kapasitas ? 'danger' : ($item->siswa_count >= $item->kapasitas * 0.8 ? 'warning' : 'success') }}">
                                            {{ $item->siswa_count }}/{{ $item->kapasitas }}
                                        </span>
                                        @if($item->siswa_count >= $item->kapasitas)
                                            <br><small class="text-danger">Penuh</small>
                                        @endif
                                    </td>
                                    <td>{{ formatRupiah($item->biaya_bulanan) }}</td>
                                    <td>
                                        @if($item->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <x-action-buttons 
                                            :view-route="route('admin.kelas.show', $item->id)"
                                            :edit-route="route('admin.kelas.edit', $item->id)"
                                            :delete-route="route('admin.kelas.destroy', $item->id)"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $kelas->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data kelas</p>
                    <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Kelas Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
