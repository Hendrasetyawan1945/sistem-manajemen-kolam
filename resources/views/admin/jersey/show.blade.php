<x-layouts.admin>
    <x-page-header 
        title="Detail Pesanan Jersey"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pesanan Jersey', 'url' => route('admin.jersey.index')],
            ['title' => 'Detail', 'url' => '#']
        ]"
    />

    <x-alert />

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tshirt me-2"></i>Informasi Pesanan
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" width="35%">Siswa</td>
                            <td><strong>{{ $jersey->siswa->nama }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kelas</td>
                            <td>{{ $jersey->siswa->kelas->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ukuran Jersey</td>
                            <td>
                                <span class="badge bg-primary fs-6">
                                    {{ $jersey->masterUkuranJersey->ukuran ?? '-' }}
                                </span>
                                @if($jersey->masterUkuranJersey?->keterangan)
                                    <small class="text-muted ms-1">{{ $jersey->masterUkuranJersey->keterangan }}</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Pesan</td>
                            <td>{{ formatTanggal($jersey->tanggal_pesan) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                @if($jersey->status == 'dipesan')
                                    <span class="badge bg-warning text-dark fs-6">🕐 Dipesan</span>
                                @elseif($jersey->status == 'diterima')
                                    <span class="badge bg-success fs-6">✅ Diterima</span>
                                @else
                                    <span class="badge bg-danger fs-6">❌ Dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Catatan</td>
                            <td>{{ $jersey->catatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibuat Pada</td>
                            <td>{{ formatTanggal($jersey->created_at) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Panel Update Status -->
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exchange-alt me-2"></i>Update Status
                    </h6>
                </div>
                <div class="card-body">

                    {{-- Alur visual --}}
                    <div class="d-flex align-items-center justify-content-center mb-4 gap-2">
                        <span class="badge {{ $jersey->status == 'dipesan' ? 'bg-warning text-dark' : 'bg-light text-muted border' }} p-2">
                            Dipesan
                        </span>
                        <i class="fas fa-arrow-right text-muted"></i>
                        <span class="badge {{ $jersey->status == 'diterima' ? 'bg-success' : 'bg-light text-muted border' }} p-2">
                            Diterima
                        </span>
                        <span class="text-muted mx-1">atau</span>
                        <span class="badge {{ $jersey->status == 'dibatalkan' ? 'bg-danger' : 'bg-light text-muted border' }} p-2">
                            Dibatalkan
                        </span>
                    </div>

                    @if($jersey->status === 'dipesan')
                        <p class="text-muted small mb-3">Jersey sudah dipesan. Ubah status setelah jersey tiba atau jika dibatalkan.</p>

                        <form action="{{ route('admin.jersey.updateStatus', $jersey->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="diterima">
                            <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Konfirmasi jersey sudah diterima oleh siswa?')">
                                <i class="fas fa-check me-2"></i> Tandai Sudah Diterima
                            </button>
                        </form>

                        <form action="{{ route('admin.jersey.updateStatus', $jersey->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="dibatalkan">
                            <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return confirm('Yakin ingin membatalkan pesanan jersey ini?')">
                                <i class="fas fa-ban me-2"></i> Batalkan Pesanan
                            </button>
                        </form>

                    @elseif($jersey->status === 'diterima')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Jersey sudah diterima. Status tidak dapat diubah lagi.
                        </div>

                    @else
                        <div class="alert alert-secondary">
                            <i class="fas fa-ban me-2"></i>
                            Pesanan ini sudah dibatalkan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="d-flex gap-2">
        <a href="{{ route('admin.jersey.edit', $jersey->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Edit Ukuran/Catatan
        </a>
        @if($jersey->status !== 'diterima')
            <form action="{{ route('admin.jersey.destroy', $jersey->id) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </form>
        @endif
        <a href="{{ route('admin.jersey.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</x-layouts.admin>
