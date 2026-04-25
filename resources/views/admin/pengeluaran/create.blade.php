<x-layouts.admin>
    <x-page-header 
        title="Tambah Pengeluaran"
        subtitle="Catat pengeluaran operasional klub"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pengeluaran', 'url' => route('admin.pengeluaran.index')],
            ['title' => 'Tambah', 'url' => '#']
        ]"
    />

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Pengeluaran</h6>
        </div>
        <div class="card-body">
            <x-alert />
            <form action="{{ route('admin.pengeluaran.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Nama Pengeluaran"
                            name="nama_pengeluaran"
                            type="text"
                            :value="old('nama_pengeluaran')"
                            placeholder="Contoh: Pembelian kaporit kolam"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="item_kas_id" class="form-label">Kategori</label>
                            <select name="item_kas_id" id="item_kas_id" class="form-control @error('item_kas_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori (Opsional) --</option>
                                @foreach($itemKasList as $item)
                                    <option value="{{ $item->id }}" {{ old('item_kas_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('item_kas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            label="Jumlah (Rp)"
                            name="jumlah"
                            type="number"
                            :value="old('jumlah')"
                            placeholder="100000"
                            required
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-input 
                            label="Tanggal"
                            name="tanggal"
                            type="date"
                            :value="old('tanggal', date('Y-m-d'))"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Keterangan pengeluaran (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
