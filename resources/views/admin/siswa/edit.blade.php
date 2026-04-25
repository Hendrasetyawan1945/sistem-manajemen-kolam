<x-layouts.admin>
    <x-page-header 
        title="Edit Data Siswa"
        subtitle="Perbarui data siswa {{ $siswa->nama }}"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Siswa', 'url' => route('admin.siswa.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Data Siswa</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Nama Lengkap"
                            name="nama"
                            type="text"
                            :value="old('nama', $siswa->nama)"
                            placeholder="Masukkan nama lengkap siswa"
                            required
                        />
                    </div>

                    <div class="col-md-3">
                        <x-form-input 
                            label="Tanggal Lahir"
                            name="tanggal_lahir"
                            type="date"
                            :value="old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d'))"
                            required
                        />
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" id="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $siswa->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Nama Orang Tua"
                            name="nama_orang_tua"
                            type="text"
                            :value="old('nama_orang_tua', $siswa->nama_ortu)"
                            placeholder="Masukkan nama orang tua/wali"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Telepon Orang Tua"
                            name="telepon_orang_tua"
                            type="text"
                            :value="old('telepon_orang_tua', $siswa->telepon_ortu)"
                            placeholder="Contoh: 081234567890"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror">
                                <option value="">Belum Ada Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status', $siswa->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="cuti" {{ old('status', $siswa->status) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                <option value="nonaktif" {{ old('status', $siswa->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Siswa</label>
                            
                            @if($siswa->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="{{ $siswa->nama }}" class="img-thumbnail" width="150">
                                    <p class="text-muted small mt-1">Foto saat ini</p>
                                </div>
                            @endif
                            
                            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg,image/jpg,image/png">
                            <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
