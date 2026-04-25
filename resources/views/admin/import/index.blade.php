<x-layouts.admin>
    <x-page-header 
        title="Import Data Siswa"
        subtitle="Upload file CSV untuk menambahkan siswa secara massal"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Import Siswa', 'url' => '#']
        ]"
    />

    <div class="row">
        <!-- Import Instructions -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-upload me-2"></i>
                        Import Data Siswa
                    </h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <x-alert type="success" :message="session('success')" />
                        
                        @if(session('import_errors'))
                            <div class="alert alert-warning mt-3">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Error pada beberapa baris:</h6>
                                <ul class="mb-0">
                                    @foreach(session('import_errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    <form action="{{ route('admin.import.preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="csv_file" class="form-label">
                                <i class="fas fa-file-csv me-2"></i>
                                Pilih File CSV
                            </label>
                            <input type="file" 
                                   class="form-control @error('csv_file') is-invalid @enderror" 
                                   id="csv_file" 
                                   name="csv_file" 
                                   accept=".csv,.txt"
                                   required>
                            @error('csv_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                File harus berformat CSV dengan maksimal ukuran 2MB.
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.import.template') }}" class="btn btn-outline-primary me-md-2">
                                <i class="fas fa-download me-2"></i>
                                Download Template
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>
                                Preview Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- CSV Export -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-download me-2"></i>
                        Export Data Siswa ke CSV
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Export data siswa yang sudah ada untuk keperluan backup atau editing massal.</p>
                    
                    <form action="{{ route('admin.import.export-csv') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <x-form-input 
                                    name="kelas_id" 
                                    label="Filter Kelas (Opsional)" 
                                    type="select"
                                    :options="$kelasList"
                                />
                            </div>
                            <div class="col-md-6">
                                <x-form-input 
                                    name="status" 
                                    label="Filter Status (Opsional)" 
                                    type="select"
                                    :options="['aktif' => 'Aktif', 'cuti' => 'Cuti', 'nonaktif' => 'Non-aktif']"
                                />
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-csv me-2"></i>
                                Export ke CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Petunjuk Import
                    </h6>
                </div>
                <div class="card-body">
                    <h6>Langkah-langkah:</h6>
                    <ol class="small">
                        <li>Download template CSV</li>
                        <li>Isi data siswa sesuai format</li>
                        <li>Upload file CSV</li>
                        <li>Preview dan validasi data</li>
                        <li>Konfirmasi import</li>
                    </ol>

                    <h6 class="mt-3">Format Data:</h6>
                    <ul class="small">
                        <li><strong>nama:</strong> Nama lengkap siswa</li>
                        <li><strong>tanggal_lahir:</strong> YYYY-MM-DD</li>
                        <li><strong>jenis_kelamin:</strong> L atau P</li>
                        <li><strong>alamat:</strong> Alamat lengkap</li>
                        <li><strong>nama_orang_tua:</strong> Nama orang tua</li>
                        <li><strong>telepon_orang_tua:</strong> Nomor telepon</li>
                        <li><strong>kelas_id:</strong> ID kelas (lihat daftar kelas)</li>
                        <li><strong>status:</strong> aktif, cuti, atau nonaktif</li>
                    </ul>

                    <h6 class="mt-3">Catatan:</h6>
                    <ul class="small text-muted">
                        <li>Email akan dibuat otomatis</li>
                        <li>Password default: password123</li>
                        <li>Data yang tidak valid akan diabaikan</li>
                        <li>Import dilakukan dalam transaksi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Classes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-list me-2"></i>
                        Daftar Kelas Tersedia
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($kelasList as $id => $nama)
                            @if($id !== '')
                                <div class="col-md-3 mb-2">
                                    <span class="badge bg-primary">ID: {{ $id }}</span>
                                    <span class="ms-2">{{ $nama }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>