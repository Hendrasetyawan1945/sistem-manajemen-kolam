<x-layouts.admin>
    <x-page-header 
        title="Edit Personal Best"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Personal Best', 'url' => route('admin.personal-best.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Personal Best</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.personal-best.update', $personalBest->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Siswa</label>
                            <input type="text" class="form-control" value="{{ $personalBest->siswa->nama }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-form-input label="Nomor Lomba" name="nomor_lomba" type="text" :value="old('nomor_lomba', $personalBest->nomor_lomba)" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input label="Gaya Renang" name="gaya_renang" type="text" :value="old('gaya_renang', $personalBest->gaya_renang)" required />
                    </div>
                    <div class="col-md-6">
                        <x-form-input label="Jarak (meter)" name="jarak" type="number" :value="old('jarak', $personalBest->jarak)" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input label="Catatan Waktu (MM:SS.MS)" name="catatan_waktu" type="text" :value="old('catatan_waktu', $personalBest->catatan_waktu)" required />
                    </div>
                    <div class="col-md-6">
                        <x-form-input label="Tanggal" name="tanggal" type="date" :value="old('tanggal', \Carbon\Carbon::parse($personalBest->tanggal)->format('Y-m-d'))" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="2" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $personalBest->keterangan) }}</textarea>
                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                    <a href="{{ route('admin.personal-best.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
