<x-layouts.admin>
    <x-page-header 
        title="Tambah Personal Best"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Personal Best', 'url' => route('admin.personal-best.index')],
            ['title' => 'Tambah', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Personal Best</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.personal-best.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="siswa_id" id="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($siswaList as $id => $nama)
                                    <option value="{{ $id }}" {{ old('siswa_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                            @error('siswa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-form-input label="Nomor Lomba" name="nomor_lomba" type="text" :value="old('nomor_lomba')" placeholder="Contoh: 50m Freestyle" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input label="Gaya Renang" name="gaya_renang" type="text" :value="old('gaya_renang')" placeholder="Contoh: Freestyle" required />
                    </div>
                    <div class="col-md-6">
                        <x-form-input label="Jarak (meter)" name="jarak" type="number" :value="old('jarak')" placeholder="Contoh: 50" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input label="Catatan Waktu (MM:SS.MS)" name="catatan_waktu" type="text" :value="old('catatan_waktu')" placeholder="Contoh: 00:35.50" required />
                    </div>
                    <div class="col-md-6">
                        <x-form-input label="Tanggal" name="tanggal" type="date" :value="old('tanggal', date('Y-m-d'))" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                    <a href="{{ route('admin.personal-best.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
