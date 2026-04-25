<x-layouts.admin>
    <x-page-header 
        title="Tambah Pesanan Jersey"
        subtitle="Buat pesanan jersey baru untuk siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pesanan Jersey', 'url' => route('admin.jersey.index')],
            ['title' => 'Tambah', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Pesanan Jersey</h6>
        </div>
        <div class="card-body">

            {{-- Info alur --}}
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Alur Jersey:</strong>
                Pesanan baru otomatis berstatus <strong>Dipesan</strong>.
                Setelah jersey tiba, ubah status ke <strong>Diterima</strong> dari halaman detail atau daftar pesanan.
            </div>

            <form action="{{ route('admin.jersey.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id', request('siswa_id')) == $siswa->id ? 'selected' : '' }}>
                                        {{ $siswa->nama }} — {{ $siswa->kelas->nama ?? 'Belum ada kelas' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($siswaList->isEmpty())
                                <small class="text-success"><i class="fas fa-check-circle"></i> Semua siswa aktif sudah memiliki pesanan jersey.</small>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="master_ukuran_jersey_id" class="form-label">Ukuran Jersey <span class="text-danger">*</span></label>
                            <select name="master_ukuran_jersey_id" id="master_ukuran_jersey_id" class="form-control @error('master_ukuran_jersey_id') is-invalid @enderror" required>
                                <option value="">Pilih Ukuran</option>
                                @foreach($ukuranList as $ukuran)
                                    <option value="{{ $ukuran->id }}" {{ old('master_ukuran_jersey_id') == $ukuran->id ? 'selected' : '' }}>
                                        {{ $ukuran->ukuran }}{{ $ukuran->keterangan ? ' — ' . $ukuran->keterangan : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('master_ukuran_jersey_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($ukuranList->isEmpty())
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Belum ada ukuran jersey.
                                    <a href="{{ route('admin.master-ukuran-jersey.create') }}">Tambah ukuran terlebih dahulu</a>.
                                </small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input
                            label="Tanggal Pesan"
                            name="tanggal_pesan"
                            type="date"
                            :value="old('tanggal_pesan', date('Y-m-d'))"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control bg-light" value="Dipesan (otomatis)" readonly>
                            <small class="text-muted">Status awal selalu Dipesan</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="2"
                        class="form-control @error('catatan') is-invalid @enderror"
                        placeholder="Catatan tambahan (opsional)" maxlength="255">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Pesanan
                    </button>
                    <a href="{{ route('admin.jersey.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
