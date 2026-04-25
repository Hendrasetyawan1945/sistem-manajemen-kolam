<x-layouts.admin>
    <x-page-header 
        title="Detail Iuran Insidentil"
        subtitle="Informasi lengkap pembayaran iuran insidentil"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Iuran Insidentil', 'url' => route('admin.iuran-insidentil.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detail Iuran Insidentil</h6>
            <div>
                @if($iuranInsidentil->status_bayar == 'belum')
                    <a href="{{ route('admin.iuran-insidentil.edit', $iuranInsidentil->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                @endif
                <a href="{{ route('admin.iuran-insidentil.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Siswa</th>
                            <td>{{ $iuranInsidentil->siswa->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $iuranInsidentil->siswa->kelas->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Item</th>
                            <td>{{ $iuranInsidentil->nama_item }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $iuranInsidentil->itemKas->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>{{ formatRupiah($iuranInsidentil->jumlah) }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Tagihan</th>
                            <td>{{ $iuranInsidentil->tanggal ? formatTanggal($iuranInsidentil->tanggal) : '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Status</th>
                            <td>
                                @if($iuranInsidentil->status_bayar == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-danger">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Bayar</th>
                            <td>{{ $iuranInsidentil->tanggal_bayar ? formatTanggal($iuranInsidentil->tanggal_bayar) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>{{ $iuranInsidentil->metodePembayaran->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $iuranInsidentil->catatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $iuranInsidentil->dibuatOleh->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>{{ formatTanggal($iuranInsidentil->created_at) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
