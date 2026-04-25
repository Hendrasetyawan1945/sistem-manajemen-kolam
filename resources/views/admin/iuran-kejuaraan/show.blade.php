<x-layouts.admin>
    <x-page-header 
        title="Detail Iuran Kejuaraan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Iuran Kejuaraan', 'url' => route('admin.iuran-kejuaraan.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <x-alert />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detail Iuran Kejuaraan</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.iuran-kejuaraan.edit', $iuranKejuaraan->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('admin.iuran-kejuaraan.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <td class="text-muted" width="30%">Siswa</td>
                    <td><strong>{{ $iuranKejuaraan->siswa->nama }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Kelas</td>
                    <td>{{ $iuranKejuaraan->siswa->kelas->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Kejuaraan</td>
                    <td>{{ $iuranKejuaraan->kejuaraan->nama }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Tanggal Kejuaraan</td>
                    <td>
                        {{ formatTanggal($iuranKejuaraan->kejuaraan->tanggal_mulai) }}
                        @if($iuranKejuaraan->kejuaraan->tanggal_selesai != $iuranKejuaraan->kejuaraan->tanggal_mulai)
                            - {{ formatTanggal($iuranKejuaraan->kejuaraan->tanggal_selesai) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-muted">Lokasi</td>
                    <td>{{ $iuranKejuaraan->kejuaraan->lokasi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Jumlah</td>
                    <td><strong>{{ formatRupiah($iuranKejuaraan->jumlah) }}</strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Status Bayar</td>
                    <td>
                        @if($iuranKejuaraan->status_bayar === 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @else
                            <span class="badge bg-danger">Belum Lunas</span>
                        @endif
                    </td>
                </tr>
                @if($iuranKejuaraan->tanggal_bayar)
                <tr>
                    <td class="text-muted">Tanggal Bayar</td>
                    <td>{{ formatTanggal($iuranKejuaraan->tanggal_bayar) }}</td>
                </tr>
                @endif
                @if($iuranKejuaraan->metodePembayaran)
                <tr>
                    <td class="text-muted">Metode Pembayaran</td>
                    <td>{{ $iuranKejuaraan->metodePembayaran->nama }}</td>
                </tr>
                @endif
                @if($iuranKejuaraan->dibuatOleh)
                <tr>
                    <td class="text-muted">Dibuat Oleh</td>
                    <td>{{ $iuranKejuaraan->dibuatOleh->name }}</td>
                </tr>
                @endif
                <tr>
                    <td class="text-muted">Dibuat Pada</td>
                    <td>{{ formatTanggal($iuranKejuaraan->created_at) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.iuran-kejuaraan.edit', $iuranKejuaraan->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <form action="{{ route('admin.iuran-kejuaraan.destroy', $iuranKejuaraan->id) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
        </form>
        <a href="{{ route('admin.iuran-kejuaraan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</x-layouts.admin>
