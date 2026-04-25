<x-layouts.coach>
    <x-page-header 
        title="Edit Rapor Siswa"
        subtitle="Perbarui penilaian rapor siswa"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('coach.dashboard')],
            ['title' => 'Rapor', 'url' => route('coach.rapor.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Rapor Siswa</h6>
        </div>
        <div class="card-body">
            <x-alert />

            <form action="{{ route('coach.rapor.update', $rapor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($siswaList as $siswa)
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id', $rapor->siswa_id) == $siswa->id ? 'selected' : '' }}>
                                        {{ $siswa->nama }} ({{ $siswa->kelas->nama ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <x-form-input 
                            label="Periode"
                            name="periode"
                            type="text"
                            :value="old('periode', $rapor->periode)"
                            placeholder="YYYY-MM (contoh: 2024-01)"
                            required
                        />
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $rapor->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="final" {{ old('status', $rapor->status) == 'final' ? 'selected' : '' }}>Final</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>
                <h6 class="font-weight-bold text-primary mb-3">Penilaian (Skala 1-10)</h6>

                <div class="row">
                    <div class="col-md-3">
                        <x-form-input 
                            label="Teknik Renang"
                            name="teknik_renang"
                            type="number"
                            :value="old('teknik_renang', $rapor->teknik_renang)"
                            placeholder="1-10"
                            required
                        />
                    </div>

                    <div class="col-md-3">
                        <x-form-input 
                            label="Kondisi Fisik"
                            name="kondisi_fisik"
                            type="number"
                            :value="old('kondisi_fisik', $rapor->kondisi_fisik)"
                            placeholder="1-10"
                            required
                        />
                    </div>

                    <div class="col-md-3">
                        <x-form-input 
                            label="Kedisiplinan"
                            name="kedisiplinan"
                            type="number"
                            :value="old('kedisiplinan', $rapor->kedisiplinan)"
                            placeholder="1-10"
                            required
                        />
                    </div>

                    <div class="col-md-3">
                        <x-form-input 
                            label="Semangat Berlatih"
                            name="semangat_berlatih"
                            type="number"
                            :value="old('semangat_berlatih', $rapor->semangat_berlatih)"
                            placeholder="1-10"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="catatan_coach" class="form-label">Catatan Coach (Opsional)</label>
                            <textarea name="catatan_coach" id="catatan_coach" rows="4" class="form-control @error('catatan_coach') is-invalid @enderror" placeholder="Catatan perkembangan siswa...">{{ old('catatan_coach', $rapor->catatan_coach) }}</textarea>
                            @error('catatan_coach')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                    <a href="{{ route('coach.rapor.show', $rapor->id) }}" class="btn btn-info">
                        <i class="fas fa-eye me-1"></i> Lihat Detail
                    </a>
                    <a href="{{ route('coach.rapor.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.coach>
