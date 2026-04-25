<x-layouts.admin>
    <x-page-header 
        title="Tambah Iuran Insidentil"
        subtitle="Input pembayaran iuran insidentil siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Iuran Insidentil', 'url' => route('admin.iuran-insidentil.index')],
            ['title' => 'Tambah', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Iuran Insidentil</h6>
        </div>
        <div class="card-body">
            <x-alert />
            <form action="{{ route('admin.iuran-insidentil.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                        {{ $siswa->nama }} - {{ $siswa->kelas->nama ?? 'Belum ada kelas' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="item_kas_id" class="form-label">Kategori Item</label>
                            <select name="item_kas_id" id="item_kas_id" class="form-control @error('item_kas_id') is-invalid @enderror">
                                <option value="">Pilih Kategori (Opsional)</option>
                                @foreach($itemKasList as $itemKas)
                                    <option value="{{ $itemKas->id }}" {{ old('item_kas_id') == $itemKas->id ? 'selected' : '' }}>
                                        {{ $itemKas->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('item_kas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Nama Item"
                            name="nama_item"
                            type="text"
                            :value="old('nama_item')"
                            placeholder="Contoh: Biaya Seragam Latihan"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Jumlah (Rp)"
                            name="jumlah"
                            type="number"
                            :value="old('jumlah')"
                            placeholder="100000"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Tanggal Tagihan"
                            name="tanggal"
                            type="date"
                            :value="old('tanggal', date('Y-m-d'))"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status_bayar" class="form-label">Status Bayar <span class="text-danger">*</span></label>
                            <select name="status_bayar" id="status_bayar" class="form-control @error('status_bayar') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="lunas" {{ old('status_bayar') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="belum" {{ old('status_bayar') == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                            </select>
                            @error('status_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="payment-fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <x-form-input 
                                label="Tanggal Bayar"
                                name="tanggal_bayar"
                                type="date"
                                :value="old('tanggal_bayar', date('Y-m-d'))"
                            />
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="metode_pembayaran_id" class="form-label">Metode Pembayaran</label>
                                <select name="metode_pembayaran_id" id="metode_pembayaran_id" class="form-control @error('metode_pembayaran_id') is-invalid @enderror">
                                    <option value="">Pilih Metode</option>
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
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror" placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.iuran-insidentil.index') }}" class="btn btn-secondary">
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
        
        // Trigger on page load if old value exists
        if (document.getElementById('status_bayar').value === 'lunas') {
            document.getElementById('payment-fields').style.display = 'block';
        }
    </script>
</x-layouts.admin>
