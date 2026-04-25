<x-layouts.admin>
    <x-page-header 
        title="Tambah Angsuran"
        subtitle="Buat angsuran cicilan baru"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Angsuran', 'url' => route('admin.angsuran.index')],
            ['title' => 'Tambah', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Angsuran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.angsuran.store') }}" method="POST">
                @csrf

                <div class="row">
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

                    <div class="col-md-6">
                        <x-form-input 
                            label="Total Tagihan (Rp)"
                            name="total_tagihan"
                            type="number"
                            :value="old('total_tagihan')"
                            placeholder="1000000"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <x-form-input 
                            label="Keterangan"
                            name="keterangan"
                            type="text"
                            :value="old('keterangan')"
                            placeholder="Contoh: Biaya seragam dan perlengkapan renang"
                            required
                        />
                        <small class="text-muted">Maksimal 200 karakter</small>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Catatan:</strong> Angsuran akan dibuat dengan status "Aktif". Pembayaran cicilan dapat ditambahkan setelah angsuran dibuat.
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.angsuran.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
