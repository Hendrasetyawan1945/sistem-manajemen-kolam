<x-layouts.admin>
    <x-page-header 
        title="Edit Rapor"
        subtitle="Perbarui rapor penilaian siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Rapor', 'url' => route('admin.rapor.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Rapor</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.rapor.update', $rapor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id', $rapor->siswa_id) == $siswa->id ? 'selected' : '' }}>
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
                            label="Periode"
                            name="periode"
                            type="month"
                            :value="old('periode', $rapor->periode)"
                            required
                        />
                        <small class="text-muted">Format: YYYY-MM (contoh: 2024-01)</small>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Skala Penilaian:</strong> 1-10 (1 = Sangat Kurang, 10 = Sangat Baik)
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Nilai Teknik Renang"
                            name="teknik_renang"
                            type="number"
                            :value="old('teknik_renang', $rapor->teknik_renang)"
                            placeholder="8"
                            required
                        />
                        <small class="text-muted">Penilaian teknik gaya renang (1-10)</small>
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Nilai Kondisi Fisik"
                            name="kondisi_fisik"
                            type="number"
                            :value="old('kondisi_fisik', $rapor->kondisi_fisik)"
                            placeholder="8"
                            required
                        />
                        <small class="text-muted">Penilaian stamina dan kekuatan (1-10)</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Nilai Kedisiplinan"
                            name="kedisiplinan"
                            type="number"
                            :value="old('kedisiplinan', $rapor->kedisiplinan)"
                            placeholder="9"
                            required
                        />
                        <small class="text-muted">Penilaian kehadiran dan ketepatan waktu (1-10)</small>
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Nilai Semangat Berlatih"
                            name="semangat_berlatih"
                            type="number"
                            :value="old('semangat_berlatih', $rapor->semangat_berlatih)"
                            placeholder="9"
                            required
                        />
                        <small class="text-muted">Penilaian motivasi dan antusiasme (1-10)</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="catatan_coach" class="form-label">Catatan Coach</label>
                            <textarea name="catatan_coach" id="catatan_coach" rows="4" class="form-control @error('catatan_coach') is-invalid @enderror" placeholder="Catatan dan saran untuk siswa (opsional)">{{ old('catatan_coach', $rapor->catatan_coach) }}</textarea>
                            @error('catatan_coach')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $rapor->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="final" {{ old('status', $rapor->status) == 'final' ? 'selected' : '' }}>Final</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Draft dapat diedit, Final tidak dapat diubah</small>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                    <a href="{{ route('admin.rapor.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
