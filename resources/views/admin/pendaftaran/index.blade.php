<x-layouts.admin>
    <x-page-header 
        title="Pendaftaran Siswa Baru"
        subtitle="Review dan proses pendaftaran siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pendaftaran', 'url' => '#']
        ]"
    />

    <x-alert />

    {{-- Stats --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-warning shadow">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-clock text-warning fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted text-uppercase">Menunggu Review</div>
                        <div class="h4 mb-0 fw-bold">{{ $totalMenunggu }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-check-circle text-success fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted text-uppercase">Disetujui</div>
                        <div class="h4 mb-0 fw-bold">{{ $totalDisetujui }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-danger shadow">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="fas fa-times-circle text-danger fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-xs text-muted text-uppercase">Ditolak</div>
                        <div class="h4 mb-0 fw-bold">{{ $totalDitolak }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pendaftaran</h6>
            <a href="{{ route('pendaftaran.create') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-external-link-alt me-1"></i> Lihat Form Publik
            </a>
        </div>
        <div class="card-body">
            {{-- Filter --}}
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu"  {{ request('status') == 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak"   {{ request('status') == 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            @if($pendaftaran->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kelas Pilihan</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendaftaran as $item)
                                <tr class="{{ $item->status === 'menunggu' ? 'table-warning' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $item->nama }}</strong>
                                        @if($item->status === 'menunggu')
                                            <span class="badge bg-warning text-dark ms-1">Baru</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->kelas->nama ?? '<span class="text-muted">Belum dipilih</span>' }}</td>
                                    <td>{{ formatTanggal($item->created_at) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status_badge }}">
                                            {{ $item->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.pendaftaran.show', $item->id) }}"
                                           class="btn btn-sm btn-{{ $item->status === 'menunggu' ? 'primary' : 'info' }}">
                                            <i class="fas fa-{{ $item->status === 'menunggu' ? 'clipboard-check' : 'eye' }}"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $pendaftaran->appends(request()->query())->links() }}</div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada pendaftaran masuk</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
