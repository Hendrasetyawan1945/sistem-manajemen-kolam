<x-layouts.admin>
    <x-page-header 
        title="Edit Ukuran Jersey"
        subtitle="Perbarui data ukuran jersey {{ $masterUkuranJersey->ukuran }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Master Ukuran Jersey', 'url' => route('admin.master-ukuran-jersey.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Ukuran Jersey</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.master-ukuran-jersey.update', $masterUkuranJersey->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ukuran" class="form-label">Ukuran <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                name="ukuran"
                                id="ukuran"
                                class="form-control @error('ukuran') is-invalid @enderror"
                                value="{{ old('ukuran', $masterUkuranJersey->ukuran) }}"
                                placeholder="Contoh: S, M, L, XL, XXL"
                                maxlength="10"
                                required
                            >
                            @error('ukuran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 10 karakter. Contoh: XS, S, M, L, XL, XXL</small>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input
                                type="text"
                                name="keterangan"
                                id="keterangan"
                                class="form-control @error('keterangan') is-invalid @enderror"
                                value="{{ old('keterangan', $masterUkuranJersey->keterangan) }}"
                                placeholder="Contoh: Lingkar dada 80-90 cm (opsional)"
                                maxlength="100"
                            >
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.master-ukuran-jersey.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
