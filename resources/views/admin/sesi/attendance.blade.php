<x-layouts.admin>
    <x-page-header 
        title="Input Kehadiran"
        subtitle="Sesi {{ $sesi->kelas->nama }} - {{ formatTanggal($sesi->tanggal) }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Sesi Latihan', 'url' => route('admin.sesi.index')],
            ['title' => 'Kehadiran', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-calendar me-2"></i>
                {{ $sesi->kelas->nama }} - {{ formatTanggal($sesi->tanggal) }} ({{ $sesi->waktu_mulai }} - {{ $sesi->waktu_selesai }})
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sesi.updateAttendance', $sesi->id) }}" method="POST">
                @csrf

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Siswa</th>
                                <th width="200">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sesi->kehadiran as $index => $kehadiran)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($kehadiran->siswa->foto)
                                            <img src="{{ asset('storage/' . $kehadiran->siswa->foto) }}" alt="{{ $kehadiran->siswa->nama }}" class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                        @endif
                                        <strong>{{ $kehadiran->siswa->nama }}</strong>
                                    </td>
                                    <td>
                                        <select name="kehadiran[{{ $kehadiran->id }}]" class="form-control" required>
                                            <option value="hadir" {{ $kehadiran->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="izin" {{ $kehadiran->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="sakit" {{ $kehadiran->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="alpha" {{ $kehadiran->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Kehadiran
                    </button>
                    <a href="{{ route('admin.sesi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
