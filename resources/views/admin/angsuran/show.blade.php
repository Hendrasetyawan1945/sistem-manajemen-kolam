<x-layouts.admin>
    <x-page-header 
        title="Detail Angsuran"
        subtitle="Informasi lengkap angsuran dan riwayat pembayaran"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Angsuran', 'url' => route('admin.angsuran.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <div class="row">
        <!-- Informasi Angsuran -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Angsuran</h6>
                    <div>
                        <a href="{{ route('admin.angsuran.edit', $angsuran) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.angsuran.destroy', $angsuran) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus angsuran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Siswa</th>
                            <td>{{ $angsuran->siswa->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>{{ $angsuran->siswa->kelas->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $angsuran->keterangan }}</td>
                        </tr>
                        <tr>
                            <th>Total Tagihan</th>
                            <td><strong class="text-primary">{{ formatRupiah($angsuran->total_tagihan) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Total Dibayar</th>
                            <td><strong class="text-success">{{ formatRupiah($angsuran->total_dibayar) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Sisa</th>
                            <td>
                                @if($angsuran->sisa > 0)
                                    <strong class="text-danger">{{ formatRupiah($angsuran->sisa) }}</strong>
                                @else
                                    <strong class="text-success">{{ formatRupiah(0) }}</strong>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($angsuran->status == 'lunas')
                                    <span class="badge badge-success">Lunas</span>
                                @else
                                    <span class="badge badge-warning">Aktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $angsuran->dibuatOleh->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ formatTanggal($angsuran->created_at) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Progress Pembayaran -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Progress Pembayaran</h6>
                </div>
                <div class="card-body">
                    @php
                        $percentage = $angsuran->total_tagihan > 0 ? ($angsuran->total_dibayar / $angsuran->total_tagihan) * 100 : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Progress</span>
                            <span class="font-weight-bold">{{ number_format($percentage, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                {{ number_format($percentage, 1) }}%
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <div class="mb-2">
                            <small class="text-muted">Jumlah Cicilan</small>
                            <h4 class="mb-0">{{ $angsuran->detailAngsuran->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            @if($angsuran->status == 'aktif' && $angsuran->sisa > 0)
                <div class="card shadow mb-4 border-left-primary">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.angsuran.addPayment', $angsuran) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="jumlah_bayar" class="form-label">Jumlah Bayar (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control @error('jumlah_bayar') is-invalid @enderror" value="{{ old('jumlah_bayar') }}" required min="1" max="{{ $angsuran->sisa }}">
                                <small class="text-muted">Maksimal: {{ formatRupiah($angsuran->sisa) }}</small>
                                @error('jumlah_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_bayar" class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}">
                                @error('tanggal_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="metode_pembayaran_id" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="metode_pembayaran_id" id="metode_pembayaran_id" class="form-control @error('metode_pembayaran_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Metode --</option>
                                    @foreach($metodePembayaran as $metode)
                                        <option value="{{ $metode->id }}" {{ old('metode_pembayaran_id') == $metode->id ? 'selected' : '' }}>
                                            {{ $metode->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('metode_pembayaran_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" rows="2" class="form-control @error('catatan') is-invalid @enderror" placeholder="Catatan pembayaran (opsional)">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-1"></i> Tambah Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
        </div>
        <div class="card-body">
            <x-alert />

            @if($angsuran->detailAngsuran->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Tanggal Bayar</th>
                                <th>Jumlah Bayar</th>
                                <th>Metode Pembayaran</th>
                                <th>Catatan</th>
                                <th>Dibuat Oleh</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($angsuran->detailAngsuran as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ formatTanggal($detail->tanggal_bayar) }}</td>
                                    <td><strong>{{ formatRupiah($detail->jumlah_bayar) }}</strong></td>
                                    <td>{{ $detail->metodePembayaran->nama ?? '-' }}</td>
                                    <td>{{ $detail->catatan ?? '-' }}</td>
                                    <td>{{ $detail->dibuatOleh->name }}</td>
                                    <td>
                                        @if($angsuran->status == 'aktif')
                                            <form action="{{ route('admin.angsuran.deletePayment', [$angsuran, $detail]) }}" method="POST" class="d-flex" onsubmit="return confirm('Yakin ingin menghapus pembayaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <th colspan="2" class="text-end">Total:</th>
                                <th>{{ formatRupiah($angsuran->total_dibayar) }}</th>
                                <th colspan="4"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada pembayaran untuk angsuran ini.
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
