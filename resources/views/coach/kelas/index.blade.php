<x-layouts.coach>
    <x-page-header 
        title="Kelas Saya"
        subtitle="Daftar kelas yang Anda ampu"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Kelas', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kelas Saya</h6>
        </div>
        <div class="card-body">
            <x-alert />

            @if($kelas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Jadwal</th>
                                <th>Kapasitas</th>
                                <th>Biaya Bulanan</th>
                                <th>Status</th>
                                <th width="120">Aksi</th>
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
                                    <td>{{ $item->jadwal ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->siswa_count >= $item->kapasitas ? 'danger' : ($item->siswa_count >= $item->kapasitas * 0.8 ? 'warning' : 'success') }}">
                                            {{ $item->siswa_count }}/{{ $item->kapasitas }}
                                        </span>
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
                                        <a href="{{ route('coach.kelas.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('coach.sesi.create', ['kelas_id' => $item->id]) }}" class="btn btn-sm btn-primary" title="Buat Sesi">
                                            <i class="fas fa-calendar-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $kelas->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada kelas yang di-assign kepada Anda.</p>
                    <p class="text-muted small">Hubungi Admin untuk mendapatkan assignment kelas.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.coach>
