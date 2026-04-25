<x-layouts.admin>
    <x-page-header 
        title="Edit Catatan Waktu"
        subtitle="Perbarui catatan waktu lomba"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Catatan Waktu', 'url' => route('admin.catatan-waktu.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Catatan Waktu</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.catatan-waktu.update', $catatanWaktu) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kejuaraan_id" class="form-label">Kejuaraan <span class="text-danger">*</span></label>
                            <select name="kejuaraan_id" id="kejuaraan_id" class="form-control @error('kejuaraan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kejuaraan --</option>
                                @foreach($kejuaraanList as $kejuaraan)
                                    <option value="{{ $kejuaraan->id }}" {{ old('kejuaraan_id', $catatanWaktu->kejuaraan_id) == $kejuaraan->id ? 'selected' : '' }}>
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
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id', $catatanWaktu->siswa_id) == $siswa->id ? 'selected' : '' }}>
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
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="gaya_renang" class="form-label">Gaya Renang <span class="text-danger">*</span></label>
                            <select name="gaya_renang" id="gaya_renang" class="form-control @error('gaya_renang') is-invalid @enderror" required>
                                <option value="">-- Pilih Gaya --</option>
                                @foreach($gayaRenangList as $gaya)
                                    <option value="{{ $gaya }}" {{ old('gaya_renang', $catatanWaktu->gaya_renang) == $gaya ? 'selected' : '' }}>
                                        {{ $gaya }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gaya_renang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="jarak" class="form-label">Jarak (meter) <span class="text-danger">*</span></label>
                            <select name="jarak" id="jarak" class="form-control @error('jarak') is-invalid @enderror" required>
                                <option value="">-- Pilih Jarak --</option>
                                @foreach($jarakList as $j)
                                    <option value="{{ $j }}" {{ old('jarak', $catatanWaktu->jarak) == $j ? 'selected' : '' }}>
                                        {{ $j }}m
                                    </option>
                                @endforeach
                            </select>
                            @error('jarak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <x-form-input 
                            label="Posisi"
                            name="posisi"
                            type="number"
                            :value="old('posisi', $catatanWaktu->posisi)"
                            placeholder="1"
                        />
                        <small class="text-muted">Kosongkan jika tidak juara</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Catatan Waktu"
                            name="catatan_waktu"
                            type="text"
                            :value="old('catatan_waktu', $catatanWaktu->catatan_waktu)"
                            placeholder="01:23.45"
                            required
                        />
                        <small class="text-muted">Format: MM:SS.MS (contoh: 01:23.45)</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $catatanWaktu->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Catatan:</strong> Personal Best akan otomatis diperbarui jika waktu ini lebih baik dari catatan sebelumnya.
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                    <a href="{{ route('admin.catatan-waktu.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
