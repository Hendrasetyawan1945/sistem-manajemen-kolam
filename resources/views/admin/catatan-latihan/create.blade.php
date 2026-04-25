<x-layouts.admin>
    <x-page-header 
        title="Tambah Catatan Latihan"
        subtitle="Catat waktu latihan siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Catatan Latihan', 'url' => route('admin.catatan-latihan.index')],
            ['title' => 'Tambah', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Catatan Latihan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.catatan-latihan.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sesi_id" class="form-label">Sesi Latihan <span class="text-danger">*</span></label>
                            <select name="sesi_id" id="sesi_id" class="form-control @error('sesi_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Sesi --</option>
                                @foreach($sesiList as $sesi)
                                    <option value="{{ $sesi->id }}" {{ old('sesi_id') == $sesi->id ? 'selected' : '' }}>
                                        {{ $sesi->kelas->nama }} - {{ formatTanggal($sesi->tanggal) }} {{ $sesi->waktu_mulai }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sesi_id')
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
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="gaya_renang" class="form-label">Gaya Renang <span class="text-danger">*</span></label>
                            <select name="gaya_renang" id="gaya_renang" class="form-control @error('gaya_renang') is-invalid @enderror" required>
                                <option value="">-- Pilih Gaya --</option>
                                @foreach($gayaRenangList as $gaya)
                                    <option value="{{ $gaya }}" {{ old('gaya_renang') == $gaya ? 'selected' : '' }}>
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
                                    <option value="{{ $j }}" {{ old('jarak') == $j ? 'selected' : '' }}>
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
                            label="Catatan Waktu"
                            name="catatan_waktu"
                            type="text"
                            :value="old('catatan_waktu')"
                            placeholder="01:23.45"
                            required
                        />
                        <small class="text-muted">Format: MM:SS.MS</small>
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
                    <a href="{{ route('admin.catatan-latihan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
