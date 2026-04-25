<x-layouts.admin>
    <x-page-header 
        title="Edit Iuran Rutin"
        subtitle="Perbarui data pembayaran iuran rutin siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Iuran Rutin', 'url' => route('admin.iuran-rutin.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Iuran Rutin</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.iuran-rutin.update', $iuranRutin->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id', $iuranRutin->siswa_id) == $siswa->id ? 'selected' : '' }}>
                                        {{ $siswa->nama }} - {{ $siswa->kelas->nama ?? 'Belum ada kelas' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                            <select name="bulan" id="bulan" class="form-control @error('bulan') is-invalid @enderror" required>
                                <option value="">Pilih Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('bulan', $iuranRutin->bulan) == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('bulan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <x-form-input 
                            label="Tahun"
                            name="tahun"
                            type="number"
                            :value="old('tahun', $iuranRutin->tahun)"
                            placeholder="2024"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Jumlah (Rp)"
                            name="jumlah"
                            type="number"
                            :value="old('jumlah', $iuranRutin->jumlah)"
                            placeholder="300000"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status_bayar" class="form-label">Status Bayar <span class="text-danger">*</span></label>
                            <select name="status_bayar" id="status_bayar" class="form-control @error('status_bayar') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="lunas" {{ old('status_bayar', $iuranRutin->status_bayar) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="belum" {{ old('status_bayar', $iuranRutin->status_bayar) == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                            </select>
                            @error('status_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="payment-fields" style="{{ old('status_bayar', $iuranRutin->status_bayar) == 'lunas' ? '' : 'display: none;' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input 
                                label="Tanggal Bayar"
                                name="tanggal_bayar"
                                type="date"
                                :value="old('tanggal_bayar', $iuranRutin->tanggal_bayar ? $iuranRutin->tanggal_bayar->format('Y-m-d') : date('Y-m-d'))"
                            />
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="metode_pembayaran_id" class="form-label">Metode Pembayaran</label>
                                <select name="metode_pembayaran_id" id="metode_pembayaran_id" class="form-control @error('metode_pembayaran_id') is-invalid @enderror">
                                    <option value="">Pilih Metode</option>
                                    @foreach($metodePembayaran as $metode)
                                        <option value="{{ $metode->id }}" {{ old('metode_pembayaran_id', $iuranRutin->metode_pembayaran_id) == $metode->id ? 'selected' : '' }}>
                                            {{ $metode->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('metode_pembayaran_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                    <a href="{{ route('admin.iuran-rutin.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('status_bayar').addEventListener('change', function() {
            const paymentFields = document.getElementById('payment-fields');
            if (this.value === 'lunas') {
                paymentFields.style.display = 'block';
            } else {
                paymentFields.style.display = 'none';
            }
        });
    </script>
</x-layouts.admin>
