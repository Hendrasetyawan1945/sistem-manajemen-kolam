<x-layouts.admin>
    <x-page-header
        title="Laporan"
        subtitle="Pilih jenis laporan yang ingin Anda lihat"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Laporan', 'url' => '#'],
        ]"
    />

    <div class="row">
        <!-- Laporan Keuangan -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-chart-line fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Laporan Keuangan</h5>
                            <small class="text-muted">Komprehensif</small>
                        </div>
                    </div>
                    <p class="card-text text-muted flex-grow-1">
                        Laporan keuangan lengkap mencakup total pemasukan dari iuran rutin, iuran insidentil,
                        iuran kejuaraan, dan angsuran; total pengeluaran operasional; serta saldo bersih
                        untuk periode yang dipilih. Dilengkapi dengan rincian per kategori dan distribusi
                        metode pembayaran.
                    </p>
                    <a href="{{ route('admin.laporan.keuangan') }}" class="btn btn-primary mt-auto">
                        <i class="fas fa-arrow-right me-2"></i>Lihat Laporan Keuangan
                    </a>
                </div>
            </div>
        </div>

        <!-- Laporan Iuran Rutin -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-file-invoice-dollar fa-2x text-success"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Rekap Iuran Rutin</h5>
                            <small class="text-muted">Per Bulan / Kelas</small>
                        </div>
                    </div>
                    <p class="card-text text-muted flex-grow-1">
                        Rekap status pembayaran iuran rutin bulanan seluruh siswa aktif. Menampilkan
                        status lunas atau belum lunas per siswa, total yang sudah terkumpul, total
                        tunggakan, serta tanggal dan metode pembayaran untuk yang sudah lunas.
                        Dapat difilter berdasarkan kelas.
                    </p>
                    <a href="{{ route('admin.laporan.iuran-rutin') }}" class="btn btn-success mt-auto">
                        <i class="fas fa-arrow-right me-2"></i>Lihat Rekap Iuran Rutin
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
