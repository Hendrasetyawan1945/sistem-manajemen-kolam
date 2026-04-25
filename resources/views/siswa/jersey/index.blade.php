<x-layouts.siswa>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="fas fa-tshirt me-2 text-info"></i>
            Status Jersey
        </h4>
    </div>

    @if($jerseyOrders->isEmpty())
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-tshirt fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Pesanan Jersey</h5>
                <p class="text-muted">
                    Anda belum memiliki pesanan jersey. Hubungi admin untuk memesan jersey klub.
                </p>
                <div class="alert alert-info d-inline-block mt-2">
                    <i class="fas fa-info-circle me-2"></i>
                    Pemesanan jersey dilakukan melalui admin. Silakan hubungi admin untuk informasi lebih lanjut.
                </div>
            </div>
        </div>
    @else
        <!-- Info -->
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            Halaman ini hanya menampilkan status pesanan jersey Anda. Untuk perubahan atau pertanyaan, hubungi admin.
        </div>

        <!-- Jersey Orders -->
        <div class="row">
            @foreach($jerseyOrders as $jersey)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-tshirt me-2"></i>
                                Jersey #{{ $jersey->id }}
                            </h6>
                            @php
                                $statusColor = match($jersey->status) {
                                    'dipesan' => 'warning',
                                    'diterima' => 'success',
                                    'dibatalkan' => 'danger',
                                    default => 'secondary'
                                };
                                $statusLabel = match($jersey->status) {
                                    'dipesan' => 'Dipesan',
                                    'diterima' => 'Diterima',
                                    'dibatalkan' => 'Dibatalkan',
                                    default => ucfirst($jersey->status)
                                };
                                $statusIcon = match($jersey->status) {
                                    'dipesan' => 'fas fa-clock',
                                    'diterima' => 'fas fa-check-circle',
                                    'dibatalkan' => 'fas fa-times-circle',
                                    default => 'fas fa-question-circle'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">
                                <i class="{{ $statusIcon }} me-1"></i>
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="display-4 text-primary fw-bold">
                                    {{ $jersey->masterUkuranJersey->ukuran ?? '-' }}
                                </div>
                                <small class="text-muted">Ukuran Jersey</small>
                                @if($jersey->masterUkuranJersey && $jersey->masterUkuranJersey->keterangan)
                                    <p class="text-muted small mt-1">{{ $jersey->masterUkuranJersey->keterangan }}</p>
                                @endif
                            </div>

                            <hr>

                            <div class="row text-center">
                                <div class="col-12">
                                    <small class="text-muted">Tanggal Pesan</small>
                                    <p class="mb-0 fw-bold">
                                        {{ $jersey->tanggal_pesan ? formatTanggal($jersey->tanggal_pesan) : '-' }}
                                    </p>
                                </div>
                            </div>

                            @if($jersey->catatan)
                                <hr>
                                <div>
                                    <small class="text-muted">Catatan:</small>
                                    <p class="mb-0 small">{{ $jersey->catatan }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-transparent">
                            @if($jersey->status === 'dipesan')
                                <div class="alert alert-warning py-2 mb-0 small">
                                    <i class="fas fa-clock me-1"></i>
                                    Jersey sedang dalam proses pemesanan.
                                </div>
                            @elseif($jersey->status === 'diterima')
                                <div class="alert alert-success py-2 mb-0 small">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Jersey sudah diterima.
                                </div>
                            @elseif($jersey->status === 'dibatalkan')
                                <div class="alert alert-danger py-2 mb-0 small">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Pesanan dibatalkan. Hubungi admin untuk informasi lebih lanjut.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="card shadow mt-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>
                    Ringkasan Pesanan
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h4 class="text-primary">{{ $jerseyOrders->count() }}</h4>
                        <small class="text-muted">Total Pesanan</small>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-success">{{ $jerseyOrders->where('status', 'diterima')->count() }}</h4>
                        <small class="text-muted">Sudah Diterima</small>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-warning">{{ $jerseyOrders->where('status', 'dipesan')->count() }}</h4>
                        <small class="text-muted">Dalam Proses</small>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-layouts.siswa>
