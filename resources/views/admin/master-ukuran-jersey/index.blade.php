<x-layouts.admin>
    <x-page-header 
        title="Master Ukuran Jersey"
        subtitle="Kelola daftar ukuran jersey klub"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Master Ukuran Jersey', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Ukuran Jersey</h6>
            <a href="{{ route('admin.master-ukuran-jersey.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Ukuran
            </a>
        </div>
        <div class="card-body">
            <!-- Alert Messages -->
            <x-alert />

            @if($ukuranList->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="60">#</th>
                                <th>Ukuran</th>
                                <th>Keterangan</th>
                                <th>Total Jersey</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ukuranList as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($ukuranList->currentPage() - 1) * $ukuranList->perPage() }}</td>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $item->ukuran }}</span>
                                    </td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->total_jersey > 0 ? 'info' : 'secondary' }}">
                                            {{ $item->total_jersey }} jersey
                                        </span>
                                    </td>
                                    <td>
                                        <x-action-buttons 
                                            :edit-route="route('admin.master-ukuran-jersey.edit', $item->id)"
                                            :delete-route="route('admin.master-ukuran-jersey.destroy', $item->id)"
                                            delete-confirm="Apakah Anda yakin ingin menghapus ukuran {{ $item->ukuran }}?"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $ukuranList->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tshirt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data ukuran jersey</p>
                    <a href="{{ route('admin.master-ukuran-jersey.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Ukuran Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
