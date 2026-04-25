<x-layouts.admin>
    <x-page-header 
        title="Buat Sesi Latihan Baru"
        subtitle="Jadwalkan sesi latihan baru"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Sesi Latihan', 'url' => route('admin.sesi.index')],
            ['title' => 'Buat', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Sesi Latihan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sesi.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ old('kelas_id', $selectedKelasId) == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama }} - {{ $kelas->coach->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Tanggal"
                            name="tanggal"
                            type="date"
                            :value="old('tanggal', date('Y-m-d'))"
                            required
                        />
                        <small class="text-muted">Maksimal 7 hari ke depan</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Waktu Mulai"
                            name="waktu_mulai"
                            type="time"
                            :value="old('waktu_mulai', '16:00')"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Waktu Selesai"
                            name="waktu_selesai"
                            type="time"
                            :value="old('waktu_selesai', '18:00')"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan/Materi</label>
                            <textarea name="catatan" id="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror" placeholder="Contoh: Latihan teknik gaya bebas">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Kehadiran siswa akan otomatis di-generate dengan status "Alpha" setelah sesi dibuat.
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.sesi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
