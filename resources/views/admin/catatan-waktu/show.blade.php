<x-layouts.admin>
    <x-page-header 
        title="Detail Catatan Waktu"
        subtitle="Informasi lengkap catatan waktu lomba"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Catatan Waktu', 'url' => route('admin.catatan-waktu.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Catatan Waktu</h6>
            <div>
                <a href="{{ route('admin.catatan-waktu.edit', $catatanWaktu) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.catatan-waktu.destroy', $catatanWaktu) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus catatan waktu ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Kejuaraan</th>
                            <td>
                                <strong>{{ $catatanWaktu->kejuaraan->nama }}</strong>
                                <br><small class="text-muted">{{ formatTanggal($catatanWaktu->kejuaraan->tanggal_mulai) }} - {{ formatTanggal($catatanWaktu->kejuaraan->tanggal_selesai) }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $catatanWaktu->kejuaraan->lokasi }}</td>
                        </tr>
                        <tr>
                            <th>Siswa</th>
                            <td>{{ $catatanWaktu->siswa->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $catatanWaktu->siswa->kelas->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Lomba</th>
                            <td><strong>{{ $catatanWaktu->gaya_renang }} {{ $catatanWaktu->jarak }}m</strong></td>
                        </tr>
                        <tr>
                            <th>Catatan Waktu</th>
                            <td><h4 class="text-primary mb-0">{{ $catatanWaktu->catatan_waktu }}</h4></td>
                        </tr>
                        <tr>
                            <th>Posisi</th>
                            <td>
                                @if($catatanWaktu->posisi)
                                    @if($catatanWaktu->posisi == 1)
                                        <span class="badge bg-warning text-dark" style="font-size: 1.1em;"><i class="fas fa-trophy"></i> Juara 1</span>
                                    @elseif($catatanWaktu->posisi == 2)
                                        <span class="badge bg-secondary" style="font-size: 1.1em;"><i class="fas fa-medal"></i> Juara 2</span>
                                    @elseif($catatanWaktu->posisi == 3)
                                        <span class="badge bg-info" style="font-size: 1.1em;"><i class="fas fa-medal"></i> Juara 3</span>
                                    @else
                                        <span class="badge bg-light text-dark" style="font-size: 1.1em;">Posisi {{ $catatanWaktu->posisi }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $catatanWaktu->keterangan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dicatat</th>
                            <td>{{ formatTanggal($catatanWaktu->created_at) }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-stopwatch fa-3x text-primary mb-3"></i>
                            <h6 class="text-muted">Catatan Waktu</h6>
                            <h2 class="text-primary font-weight-bold">{{ $catatanWaktu->catatan_waktu }}</h2>
                            <p class="text-muted mb-0">{{ $catatanWaktu->gaya_renang }} {{ $catatanWaktu->jarak }}m</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('admin.catatan-waktu.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</x-layouts.admin>
