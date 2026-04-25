<x-layouts.admin>
    <x-page-header 
        title="Detail Iuran Rutin"
        subtitle="Informasi lengkap pembayaran iuran rutin"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Iuran Rutin', 'url' => route('admin.iuran-rutin.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detail Iuran Rutin</h6>
            <div>
                <a href="{{ route('admin.iuran-rutin.edit', $iuranRutin->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('admin.iuran-rutin.index') }}" class="btn btn-secondary btn-sm">
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
                            <td>{{ $iuranRutin->siswa->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $iuranRutin->siswa->kelas->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bulan</th>
                            <td>{{ date('F', mktime(0, 0, 0, $iuranRutin->bulan, 1)) }}</td>
                        </tr>
                        <tr>
                            <th>Tahun</th>
                            <td>{{ $iuranRutin->tahun }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>{{ formatRupiah($iuranRutin->jumlah) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Status</th>
                            <td>
                                @if($iuranRutin->status_bayar == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-danger">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Bayar</th>
                            <td>{{ $iuranRutin->tanggal_bayar ? formatTanggal($iuranRutin->tanggal_bayar) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>{{ $iuranRutin->metodePembayaran->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>{{ formatTanggal($iuranRutin->created_at) }}</td>
                        </tr>
                        <tr>
                            <th>Diperbarui</th>
                            <td>{{ formatTanggal($iuranRutin->updated_at) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
