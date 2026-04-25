<x-layouts.admin>
    <x-page-header 
        title="Edit Sesi Latihan"
        subtitle="Ubah jadwal sesi latihan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Sesi Latihan', 'url' => route('admin.sesi.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Sesi Latihan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sesi.update', $sesi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ old('kelas_id', $sesi->kelas_id) == $kelas->id ? 'selected' : '' }}>
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
                            :value="old('tanggal', \Carbon\Carbon::parse($sesi->tanggal)->format('Y-m-d'))"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Waktu Mulai"
                            name="waktu_mulai"
                            type="time"
                            :value="old('waktu_mulai', $sesi->waktu_mulai)"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Waktu Selesai"
                            name="waktu_selesai"
                            type="time"
                            :value="old('waktu_selesai', $sesi->waktu_selesai)"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan/Materi</label>
                            <textarea name="catatan" id="catatan" rows="3" 
                                class="form-control @error('catatan') is-invalid @enderror" 
                                placeholder="Contoh: Latihan teknik gaya bebas">{{ old('catatan', $sesi->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.sesi.show', $sesi->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
