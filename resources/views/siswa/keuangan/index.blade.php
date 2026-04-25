<x-layouts.siswa>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-money-bill-wave me-2 text-warning"></i>
            Keuangan Saya
        </h4>
    </div>

    <!-- Total Outstanding -->
    @if($totalOutstanding > 0)
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
            <div>
                <strong>Total Tagihan Belum Lunas:</strong>
                {{ formatRupiah($totalOutstanding) }}
                <br>
                <small>Segera hubungi admin untuk melakukan pembayaran.</small>
            </div>
        </div>
    @else
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle fa-2x me-3"></i>
            <div>
                <strong>Semua tagihan sudah lunas!</strong>
                Tidak ada tagihan yang perlu dibayar.
            </div>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-{{ $totalOutstandingIuranRutin > 0 ? 'danger' : 'success' }} text-center">
                <div class="card-body">
                    <h5 class="text-{{ $totalOutstandingIuranRutin > 0 ? 'danger' : 'success' }}">
                        {{ formatRupiah($totalOutstandingIuranRutin) }}
                    </h5>
                    <small class="text-muted">Tunggakan Iuran Rutin</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-{{ $totalOutstandingInsidentil > 0 ? 'danger' : 'success' }} text-center">
                <div class="card-body">
                    <h5 class="text-{{ $totalOutstandingInsidentil > 0 ? 'danger' : 'success' }}">
                        {{ formatRupiah($totalOutstandingInsidentil) }}
                    </h5>
                    <small class="text-muted">Tunggakan Iuran Insidentil</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-{{ $totalOutstandingAngsuran > 0 ? 'danger' : 'success' }} text-center">
                <div class="card-body">
                    <h5 class="text-{{ $totalOutstandingAngsuran > 0 ? 'danger' : 'success' }}">
                        {{ formatRupiah($totalOutstandingAngsuran) }}
                    </h5>
                    <small class="text-muted">Sisa Angsuran</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Iuran Rutin -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-calendar-check me-2"></i>
                Iuran Rutin Bulanan
            </h6>
        </div>
        <div class="card-body">
            @if($iuranRutin->isEmpty())
                <div class="text-center text-muted py-3">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada data iuran rutin.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Periode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($iuranRutin as $ir)
                                <tr class="{{ $ir->status_bayar !== 'lunas' ? 'table-danger' : '' }}">
                                    <td>{{ $ir->periode_text }}</td>
                                    <td>{{ $ir->formatted_jumlah }}</td>
                                    <td>
                                        @if($ir->status_bayar === 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td>{{ $ir->formatted_tanggal_bayar }}</td>
                                    <td>{{ $ir->metodePembayaran->nama ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Iuran Insidentil -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Iuran Insidentil
            </h6>
        </div>
        <div class="card-body">
            @if($iuranInsidentil->isEmpty())
                <div class="text-center text-muted py-3">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada data iuran insidentil.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Keterangan</th>
                                <th>Tanggal Tagihan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th>Metode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($iuranInsidentil as $ii)
                                <tr class="{{ $ii->status_bayar !== 'lunas' ? 'table-warning' : '' }}">
                                    <td>{{ $ii->nama_item ?? $ii->catatan ?? '-' }}</td>
                                    <td>{{ $ii->formatted_tanggal }}</td>
                                    <td>{{ $ii->formatted_jumlah }}</td>
                                    <td>
                                        @if($ii->status_bayar === 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td>{{ $ii->formatted_tanggal_bayar }}</td>
                                    <td>{{ $ii->metodePembayaran->nama ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Angsuran -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-hand-holding-usd me-2"></i>
                Angsuran / Cicilan
            </h6>
        </div>
        <div class="card-body">
            @if($angsuran->isEmpty())
                <div class="text-center text-muted py-3">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada data angsuran.</p>
                </div>
            @else
                @foreach($angsuran as $a)
                    <div class="card mb-3 {{ $a->status !== 'lunas' ? 'border-warning' : 'border-success' }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>{{ $a->keterangan }}</strong>
                            @if($a->status === 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <small class="text-muted">Total Tagihan</small>
                                    <p class="mb-0 fw-bold">{{ formatRupiah($a->total_tagihan) }}</p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Sudah Dibayar</small>
                                    <p class="mb-0 fw-bold text-success">{{ formatRupiah($a->total_dibayar) }}</p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">Sisa</small>
                                    <p class="mb-0 fw-bold {{ $a->sisa > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ formatRupiah($a->sisa) }}
                                    </p>
                                </div>
                            </div>

                            @if($a->detailAngsuran->isNotEmpty())
                                <h6 class="text-muted">Riwayat Pembayaran:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Jumlah</th>
                                                <th>Metode</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($a->detailAngsuran as $j => $detail)
                                                <tr>
                                                    <td>{{ $j + 1 }}</td>
                                                    <td>
                                                        {{ $detail->tanggal_bayar ? formatTanggal($detail->tanggal_bayar) : '-' }}
                                                    </td>
                                                    <td>{{ formatRupiah($detail->jumlah_bayar) }}</td>
                                                    <td>{{ $detail->metodePembayaran->nama ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-layouts.siswa>
