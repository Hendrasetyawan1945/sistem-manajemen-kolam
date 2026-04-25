<x-layouts.coach>
    <x-page-header 
        title="Rapor Siswa"
        subtitle="Daftar rapor siswa di kelas Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Rapor', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Rapor</h6>
            <a href="{{ route('coach.rapor.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Buat Rapor
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('coach.rapor.index') }}" class="mb-4">
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
                        <input type="text" name="periode" class="form-control" placeholder="Periode (YYYY-MM)" value="{{ request('periode') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="final" {{ request('status') == 'final' ? 'selected' : '' }}>Final</option>
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

            @if($rapor->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Periode</th>
                                <th class="text-center">Teknik</th>
                                <th class="text-center">Fisik</th>
                                <th class="text-center">Disiplin</th>
                                <th class="text-center">Semangat</th>
                                <th class="text-center">Rata-rata</th>
                                <th>Status</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rapor as $item)
                                <tr>
                                    <td><strong>{{ $item->siswa->nama }}</strong></td>
                                    <td>{{ $item->siswa->kelas->nama ?? '-' }}</td>
                                    <td>{{ $item->periode }}</td>
                                    <td class="text-center">{{ $item->teknik_renang }}</td>
                                    <td class="text-center">{{ $item->kondisi_fisik }}</td>
                                    <td class="text-center">{{ $item->kedisiplinan }}</td>
                                    <td class="text-center">{{ $item->semangat_berlatih }}</td>
                                    <td class="text-center">
                                        <strong class="text-{{ $item->rata_rata >= 7 ? 'success' : ($item->rata_rata >= 5 ? 'warning' : 'danger') }}">
                                            {{ number_format($item->rata_rata, 2) }}
                                        </strong>
                                        <br><small class="badge bg-{{ $item->rata_rata >= 9 ? 'success' : ($item->rata_rata >= 7 ? 'info' : ($item->rata_rata >= 5 ? 'warning' : 'danger')) }}">{{ $item->grade }}</small>
                                    </td>
                                    <td>
                                        @if($item->status == 'final')
                                            <span class="badge bg-success">Final</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('coach.rapor.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('coach.rapor.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
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
                    <a href="{{ route('coach.rapor.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Buat Rapor Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.coach>
