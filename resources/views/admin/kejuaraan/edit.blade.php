<x-layouts.admin>
    <x-page-header 
        title="Edit Kejuaraan"
        subtitle="Perbarui data kejuaraan"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Kejuaraan', 'url' => route('admin.kejuaraan.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Kejuaraan</h6>
        </div>
        <div class="card-body">
            <x-alert />
            <form action="{{ route('admin.kejuaraan.update', $kejuaraan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-12">
                        <x-form-input 
                            label="Nama Kejuaraan"
                            name="nama"
                            type="text"
                            :value="old('nama', $kejuaraan->nama)"
                            placeholder="Contoh: Kejuaraan Renang Nasional 2024"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Tanggal Mulai"
                            name="tanggal_mulai"
                            type="date"
                            :value="old('tanggal_mulai', $kejuaraan->tanggal_mulai?->format('Y-m-d'))"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Tanggal Selesai"
                            name="tanggal_selesai"
                            type="date"
                            :value="old('tanggal_selesai', $kejuaraan->tanggal_selesai?->format('Y-m-d'))"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Lokasi"
                            name="lokasi"
                            type="text"
                            :value="old('lokasi', $kejuaraan->lokasi)"
                            placeholder="Contoh: GOR Aquatic Center Jakarta"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Biaya Pendaftaran (Rp)"
                            name="biaya_pendaftaran"
                            type="number"
                            :value="old('biaya_pendaftaran', $kejuaraan->biaya_pendaftaran)"
                            placeholder="500000"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi kejuaraan (opsional)">{{ old('deskripsi', $kejuaraan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                    <a href="{{ route('admin.kejuaraan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
