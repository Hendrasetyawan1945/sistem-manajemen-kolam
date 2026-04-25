<x-layouts.admin>
    <x-page-header 
        title="Daftarkan Siswa ke Kejuaraan"
        subtitle="Tambah pendaftaran kejuaraan baru"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Iuran Kejuaraan', 'url' => route('admin.iuran-kejuaraan.index')],
            ['title' => 'Tambah', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Pendaftaran Kejuaraan</h6>
        </div>
        <div class="card-body">
            <x-alert />
            <form action="{{ route('admin.iuran-kejuaraan.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kejuaraan_id" class="form-label">Kejuaraan <span class="text-danger">*</span></label>
                            <select name="kejuaraan_id" id="kejuaraan_id" class="form-control @error('kejuaraan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kejuaraan --</option>
                                @foreach($kejuaraanList as $kejuaraan)
                                    <option value="{{ $kejuaraan->id }}" 
                                        data-biaya="{{ $kejuaraan->biaya_pendaftaran }}"
                                        {{ old('kejuaraan_id', request('kejuaraan_id')) == $kejuaraan->id ? 'selected' : '' }}>
                                        {{ $kejuaraan->nama }} - {{ formatTanggal($kejuaraan->tanggal_mulai) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kejuaraan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                        {{ $siswa->nama }} - {{ $siswa->kelas->nama ?? 'Tanpa Kelas' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Biaya Pendaftaran (Rp)"
                            name="jumlah"
                            type="number"
                            :value="old('jumlah')"
                            placeholder="500000"
                            required
                            id="jumlah"
                        />
                        <small class="text-muted">Biaya akan terisi otomatis saat memilih kejuaraan</small>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status_bayar" class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                            <select name="status_bayar" id="status_bayar" class="form-control @error('status_bayar') is-invalid @enderror" required>
                                <option value="belum" {{ old('status_bayar') == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="lunas" {{ old('status_bayar') == 'lunas' ? 'selected' : '' }}>Lunas</option>
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
                                :value="old('tanggal_bayar')"
                                id="tanggal_bayar"
                            />
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="metode_pembayaran_id" class="form-label">Metode Pembayaran</label>
                                <select name="metode_pembayaran_id" id="metode_pembayaran_id" class="form-control @error('metode_pembayaran_id') is-invalid @enderror">
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
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.iuran-kejuaraan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusBayar = document.getElementById('status_bayar');
            const paymentFields = document.getElementById('payment-fields');
            const tanggalBayar = document.getElementById('tanggal_bayar');
            const metodePembayaran = document.getElementById('metode_pembayaran_id');
            const kejuaraanSelect = document.getElementById('kejuaraan_id');
            const jumlahInput = document.getElementById('jumlah');

            // Toggle payment fields based on status
            function togglePaymentFields() {
                if (statusBayar.value === 'lunas') {
                    paymentFields.style.display = 'block';
                    tanggalBayar.required = true;
                    metodePembayaran.required = true;
                } else {
                    paymentFields.style.display = 'none';
                    tanggalBayar.required = false;
                    metodePembayaran.required = false;
                    tanggalBayar.value = '';
                    metodePembayaran.value = '';
                }
            }

            // Auto-fill biaya when kejuaraan is selected
            kejuaraanSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const biaya = selectedOption.getAttribute('data-biaya');
                if (biaya) {
                    jumlahInput.value = biaya;
                }
            });

            statusBayar.addEventListener('change', togglePaymentFields);
            
            // Initialize on page load
            togglePaymentFields();

            // Auto-fill biaya if kejuaraan is pre-selected
            if (kejuaraanSelect.value) {
                const selectedOption = kejuaraanSelect.options[kejuaraanSelect.selectedIndex];
                const biaya = selectedOption.getAttribute('data-biaya');
                if (biaya && !jumlahInput.value) {
                    jumlahInput.value = biaya;
                }
            }
        });
    </script>
    @endpush
</x-layouts.admin>
