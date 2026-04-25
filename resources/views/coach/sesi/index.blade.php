<x-layouts.coach>
    <x-page-header 
        title="Sesi Latihan"
        subtitle="Daftar sesi latihan kelas Anda"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Sesi Latihan', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Sesi Latihan</h6>
            <a href="{{ route('coach.sesi.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Buat Sesi Baru
            </a>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('coach.sesi.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="kelas_id" class="form-control">
                            <option value="">Semua Kelas Saya</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="tanggal_dari" class="form-control" placeholder="Dari Tanggal" value="{{ request('tanggal_dari') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="tanggal_sampai" class="form-control" placeholder="Sampai Tanggal" value="{{ request('tanggal_sampai') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <x-alert />

            @if($sesi->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kelas</th>
                                <th>Waktu</th>
                                <th>Catatan</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sesi as $item)
                                <tr>
                                    <td>
                                        {{ formatTanggal($item->tanggal) }}
                                        @if(\Carbon\Carbon::parse($item->tanggal)->isToday())
                                            <span class="badge bg-success ms-1">Hari Ini</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $item->kelas->nama }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}</td>
                                    <td>{{ $item->catatan ?? '-' }}</td>
                                    <td>
                                        {{-- Input Kehadiran --}}
                                        <a href="{{ route('coach.sesi.show', $item->id) }}"
                                           class="btn btn-sm btn-success" title="Input Kehadiran">
                                            <i class="fas fa-check-circle me-1"></i> Kehadiran
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $sesi->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada sesi latihan</p>
                    <a href="{{ route('coach.sesi.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Buat Sesi Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.coach>
