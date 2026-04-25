<x-layouts.admin>
    <x-page-header 
        title="Edit Kelas"
        subtitle="Perbarui data kelas {{ $kela->nama }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Kelas', 'url' => route('admin.kelas.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Data Kelas</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.kelas.update', $kela->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Nama Kelas"
                            name="nama"
                            type="text"
                            :value="old('nama', $kela->nama)"
                            placeholder="Contoh: Kelas Pemula A"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="coach_id" class="form-label">Coach <span class="text-danger">*</span></label>
                            <select name="coach_id" id="coach_id" class="form-control @error('coach_id') is-invalid @enderror" required>
                                <option value="">Pilih Coach</option>
                                @foreach($coaches as $coach)
                                    <option value="{{ $coach->id }}" {{ old('coach_id', $kela->coach_id) == $coach->id ? 'selected' : '' }}>
                                        {{ $coach->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('coach_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <x-form-input 
                            label="Jadwal"
                            name="jadwal"
                            type="text"
                            :value="old('jadwal', $kela->jadwal)"
                            placeholder="Contoh: Senin, Rabu, Jumat 16:00-18:00"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Kapasitas Siswa"
                            name="kapasitas"
                            type="number"
                            :value="old('kapasitas', $kela->kapasitas)"
                            placeholder="Contoh: 20"
                            required
                        />
                        <small class="text-muted">Saat ini: {{ $kela->siswa->count() }} siswa terdaftar</small>
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Biaya Bulanan (Rp)"
                            name="biaya_bulanan"
                            type="number"
                            :value="old('biaya_bulanan', $kela->biaya_bulanan)"
                            placeholder="Contoh: 300000"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi kelas (opsional)">{{ old('deskripsi', $kela->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $kela->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Kelas Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
