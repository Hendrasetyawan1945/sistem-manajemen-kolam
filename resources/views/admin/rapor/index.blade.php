<x-layouts.admin>
    <x-page-header 
        title="Rapor Siswa"
        subtitle="Kelola penilaian dan rapor siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Rapor', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Rapor</h6>
            <a href="{{ route('admin.rapor.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Rapor
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.rapor.index') }}" class="mb-4">
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
                        <label for="periode" class="form-label">Periode</label>
                        <input type="month" name="periode" id="periode" class="form-control" value="{{ request('periode') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="final" {{ request('status') == 'final' ? 'selected' : '' }}>Final</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.rapor.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($rapor->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Periode</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Rata-rata</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th>Coach</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rapor as $item)
                                <tr>
                                    <td>{{ $item->periode }}</td>
                                    <td><strong>{{ $item->siswa->nama }}</strong></td>
                                    <td>{{ $item->siswa->kelas->nama ?? '-' }}</td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($item->rata_rata, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($item->grade == 'A') bg-success
                                            @elseif($item->grade == 'B+' || $item->grade == 'B') bg-info
                                            @elseif($item->grade == 'C+' || $item->grade == 'C') bg-warning
                                            @else bg-danger
                                            @endif
                                        ">{{ $item->grade }}</span>
                                    </td>
                                    <td>
                                        @if($item->status == 'final')
                                            <span class="badge bg-success">Final</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->coach->name }}</td>
                                    <td>
                                        <div class="d-flex flex-nowrap gap-1">
                                            <a href="{{ route('admin.rapor.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.rapor.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.rapor.destroy', $item->id) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                    {{ $rapor->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada rapor</p>
                    <a href="{{ route('admin.rapor.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Rapor Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
