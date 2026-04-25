<x-layouts.admin>
    <x-page-header
        title="Laporan Keuangan"
        subtitle="Ringkasan pemasukan, pengeluaran, dan saldo bersih"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Laporan', 'url' => route('admin.laporan.index')],
            ['title' => 'Keuangan', 'url' => '#'],
        ]"
    />

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Periode
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.keuangan') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                        <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari"
                               value="{{ $tanggalDari }}">
                    </div>
                    <div class="col-md-4">
                        <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                        <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai"
                               value="{{ $tanggalSampai }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Tampilkan Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Export PDF Button -->
    <div class="mb-3 text-end">
        <a href="{{ route('admin.laporan.keuangan.pdf', ['tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai]) }}"
           class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Export PDF
        </a>
    </div>

    <!-- Periode Info -->
    <div class="alert alert-info mb-4">
        <i class="fas fa-calendar-alt me-2"></i>
        Menampilkan data periode:
        <strong>{{ formatTanggal($tanggalDari) }}</strong> s/d
        <strong>{{ formatTanggal($tanggalSampai) }}</strong>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pemasukan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ formatRupiah($totalIncome) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Pengeluaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ formatRupiah($totalExpenses) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-{{ $netBalance >= 0 ? 'primary' : 'warning' }} shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $netBalance >= 0 ? 'primary' : 'warning' }} text-uppercase mb-1">
                                Saldo Bersih
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-{{ $netBalance >= 0 ? 'success' : 'danger' }}">
                                {{ formatRupiah($netBalance) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x text-{{ $netBalance >= 0 ? 'primary' : 'warning' }} opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Income Breakdown -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-plus-circle me-2"></i>Rincian Pemasukan per Kategori
                    </h6>
                </div>
                <div class="card-body">
                    @if($totalIncome > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-end">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incomeBreakdown as $item)
                                        @if($item['jumlah'] > 0)
                                            @php
                                                $persen = round(($item['jumlah'] / $totalIncome) * 100, 1);
                                            @endphp
                                            <tr>
                                                <td>{{ $item['kategori'] }}</td>
                                                <td class="text-end">{{ formatRupiah($item['jumlah']) }}</td>
                                                <td class="text-end">
                                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                                        <div class="progress flex-grow-1" style="height: 8px; min-width: 60px;">
                                                            <div class="progress-bar bg-success" style="width: {{ $persen }}%"></div>
                                                        </div>
                                                        <span class="text-nowrap">{{ $persen }}%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="table-success">
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-end">{{ formatRupiah($totalIncome) }}</th>
                                        <th class="text-end">100%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Tidak ada pemasukan pada periode ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Expense Breakdown -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-minus-circle me-2"></i>Rincian Pengeluaran per Kategori
                    </h6>
                </div>
                <div class="card-body">
                    @if($expenseBreakdown->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-end">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expenseBreakdown as $item)
                                        @php
                                            $persen = $totalExpenses > 0
                                                ? round(($item->total / $totalExpenses) * 100, 1)
                                                : 0;
                                            $namaKategori = $item->itemKas->nama ?? 'Tidak Berkategori';
                                        @endphp
                                        <tr>
                                            <td>{{ $namaKategori }}</td>
                                            <td class="text-end">{{ formatRupiah($item->total) }}</td>
                                            <td class="text-end">
                                                <div class="d-flex align-items-center justify-content-end gap-2">
                                                    <div class="progress flex-grow-1" style="height: 8px; min-width: 60px;">
                                                        <div class="progress-bar bg-danger" style="width: {{ $persen }}%"></div>
                                                    </div>
                                                    <span class="text-nowrap">{{ $persen }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-danger">
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-end">{{ formatRupiah($totalExpenses) }}</th>
                                        <th class="text-end">100%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Tidak ada pengeluaran pada periode ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Distribution -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-credit-card me-2"></i>Distribusi Metode Pembayaran (Pemasukan)
            </h6>
        </div>
        <div class="card-body">
            @if(count($paymentMethodDistribution) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Metode Pembayaran</th>
                                <th class="text-end">Jumlah</th>
                                <th class="text-end">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentMethodDistribution as $item)
                                <tr>
                                    <td>
                                        <i class="fas fa-money-bill-wave me-2 text-primary"></i>
                                        {{ $item['metode'] }}
                                    </td>
                                    <td class="text-end">{{ formatRupiah($item['jumlah']) }}</td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <div class="progress flex-grow-1" style="height: 8px; min-width: 80px;">
                                                <div class="progress-bar bg-primary" style="width: {{ $item['persentase'] }}%"></div>
                                            </div>
                                            <span class="text-nowrap">{{ $item['persentase'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th>Total</th>
                                <th class="text-end">{{ formatRupiah($totalIncome) }}</th>
                                <th class="text-end">100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Tidak ada data pembayaran pada periode ini</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
