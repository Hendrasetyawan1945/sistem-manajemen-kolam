<x-layouts.admin>
    <x-page-header 
        title="Manajemen Kejuaraan"
        subtitle="Kelola data kejuaraan renang"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Kejuaraan', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kejuaraan</h6>
            <a href="{{ route('admin.kejuaraan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Kejuaraan
            </a>
        </div>
        <div class="card-body">
            <x-alert />

            @if($kejuaraan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Kejuaraan</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Biaya</th>
                                <th>Peserta</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kejuaraan as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->nama }}</strong>
                                        @if($item->deskripsi)
                                            <br><small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ formatTanggal($item->tanggal_mulai) }}
                                        @if($item->tanggal_mulai != $item->tanggal_selesai)
                                            <br>s/d {{ formatTanggal($item->tanggal_selesai) }}
                                        @endif
                                    </td>
                                    <td>{{ $item->lokasi }}</td>
                                    <td>{{ formatRupiah($item->biaya_pendaftaran) }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $item->iuran_kejuaraan_count }} siswa</span>
                                    </td>
                                    <td>
                                        <x-action-buttons 
                                            :view-route="route('admin.kejuaraan.show', $item->id)"
                                            :edit-route="route('admin.kejuaraan.edit', $item->id)"
                                            :delete-route="route('admin.kejuaraan.destroy', $item->id)"
                                            delete-confirm="Apakah Anda yakin ingin menghapus kejuaraan {{ $item->nama }}?"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $kejuaraan->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data kejuaraan</p>
                    <a href="{{ route('admin.kejuaraan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Kejuaraan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
