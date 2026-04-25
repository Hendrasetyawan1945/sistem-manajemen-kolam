<x-layouts.admin>
    <x-page-header 
        title="Edit Pesanan Jersey"
        subtitle="Ubah ukuran atau catatan pesanan jersey"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pesanan Jersey', 'url' => route('admin.jersey.index')],
            ['title' => 'Edit', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Pesanan Jersey</h6>
        </div>
        <div class="card-body">

            {{-- Info: untuk ubah status gunakan tombol di halaman detail --}}
            <div class="alert alert-warning mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Untuk mengubah <strong>status</strong> pesanan, gunakan tombol di halaman
                <a href="{{ route('admin.jersey.show', $jersey->id) }}">Detail Pesanan</a>.
                Halaman ini hanya untuk mengubah ukuran dan catatan.
            </div>

            <form action="{{ route('admin.jersey.update', $jersey->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Siswa</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ $jersey->siswa->nama }} — {{ $jersey->siswa->kelas->nama ?? '-' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status Saat Ini</label>
                            <div>
                                @if($jersey->status == 'dipesan')
                                    <span class="badge bg-warning text-dark fs-6">Dipesan</span>
                                @elseif($jersey->status == 'diterima')
                                    <span class="badge bg-success fs-6">Diterima</span>
                                @else
                                    <span class="badge bg-danger fs-6">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="master_ukuran_jersey_id" class="form-label">Ukuran Jersey <span class="text-danger">*</span></label>
                            <select name="master_ukuran_jersey_id" id="master_ukuran_jersey_id"
                                class="form-control @error('master_ukuran_jersey_id') is-invalid @enderror" required>
                                <option value="">Pilih Ukuran</option>
                                @foreach($ukuranList as $ukuran)
                                    <option value="{{ $ukuran->id }}"
                                        {{ old('master_ukuran_jersey_id', $jersey->master_ukuran_jersey_id) == $ukuran->id ? 'selected' : '' }}>
                                        {{ $ukuran->ukuran }}{{ $ukuran->keterangan ? ' — ' . $ukuran->keterangan : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('master_ukuran_jersey_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <x-form-input
                            label="Tanggal Pesan"
                            name="tanggal_pesan"
                            type="date"
                            :value="old('tanggal_pesan', \Carbon\Carbon::parse($jersey->tanggal_pesan)->format('Y-m-d'))"
                            required
                        />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan</label>
                    <textarea name="catatan" id="catatan" rows="2"
                        class="form-control @error('catatan') is-invalid @enderror"
                        maxlength="255">{{ old('catatan', $jersey->catatan) }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.jersey.show', $jersey->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
