<x-layouts.admin>
    <x-page-header 
        title="Preview Import Data"
        subtitle="Periksa data sebelum melakukan import"
        :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Import Siswa', 'url' => route('admin.import.index')],
            ['title' => 'Preview', 'url' => '#']
        ]"
    />

    <div class="row">
        <!-- Summary -->
        <div class="col-12 mb-4">
            <div class="row">
                <div class="col-md-4">
                    <x-stat-card 
                        title="Data Valid"
                        :value="count($validRows)"
                        icon="fas fa-check-circle"
                        color="success"
                    />
                </div>
                <div class="col-md-4">
                    <x-stat-card 
                        title="Data Error"
                        :value="count($invalidRows)"
                        icon="fas fa-exclamation-triangle"
                        color="warning"
                    />
                </div>
                <div class="col-md-4">
                    <x-stat-card 
                        title="Total Baris"
                        :value="count($validRows) + count($invalidRows)"
                        icon="fas fa-file-csv"
                        color="info"
                    />
                </div>
            </div>
        </div>

        @if(count($validRows) > 0)
        <!-- Valid Data -->
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Data Valid ({{ count($validRows) }} baris)
                    </h6>
                    
                    @if(count($invalidRows) == 0)
                        <form action="{{ route('admin.import.process') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                            @csrf
                            <input type="file" name="csv_file" style="display: none;" id="hidden_csv_file">
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirmImport()">
                                <i class="fas fa-upload me-2"></i>
                                Import Sekarang
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Baris</th>
                                    <th>Nama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>JK</th>
                                    <th>Alamat</th>
                                    <th>Nama Orang Tua</th>
                                    <th>Telepon</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($validRows as $row)
                                <tr>
                                    <td>{{ $row['row'] }}</td>
                                    <td>{{ $row['data']['nama'] }}</td>
                                    <td>{{ $row['data']['tanggal_lahir'] }}</td>
                                    <td>{{ $row['data']['jenis_kelamin'] }}</td>
                                    <td>{{ Str::limit($row['data']['alamat'], 30) }}</td>
                                    <td>{{ $row['data']['nama_orang_tua'] }}</td>
                                    <td>{{ $row['data']['telepon_orang_tua'] }}</td>
                                    <td>{{ $kelasNames[$row['data']['kelas_id']] ?? 'Unknown' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $row['data']['status'] === 'aktif' ? 'success' : ($row['data']['status'] === 'cuti' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($row['data']['status']) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(count($invalidRows) > 0)
        <!-- Invalid Data -->
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Data Error ({{ count($invalidRows) }} baris)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        Data berikut mengandung error dan akan diabaikan saat import. Perbaiki data di file CSV dan upload ulang.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Baris</th>
                                    <th>Nama</th>
                                    <th>Data</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invalidRows as $row)
                                <tr>
                                    <td>{{ $row['row'] }}</td>
                                    <td>{{ $row['data']['nama'] ?? '-' }}</td>
                                    <td>
                                        <small>
                                            @foreach($row['data'] as $key => $value)
                                                <strong>{{ $key }}:</strong> {{ $value }}<br>
                                            @endforeach
                                        </small>
                                    </td>
                                    <td>
                                        @foreach($row['errors'] as $error)
                                            <span class="badge bg-danger mb-1">{{ $error }}</span><br>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.import.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                        
                        @if(count($validRows) > 0 && count($invalidRows) == 0)
                            <form action="{{ route('admin.import.process') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirmImport()">
                                    <i class="fas fa-upload me-2"></i>
                                    Import {{ count($validRows) }} Data
                                </button>
                            </form>
                        @elseif(count($validRows) > 0)
                            <div class="text-muted">
                                <i class="fas fa-info-circle me-2"></i>
                                Perbaiki data error terlebih dahulu sebelum import
                            </div>
                        @else
                            <div class="text-muted">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Tidak ada data valid untuk diimport
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmImport() {
            return confirm('Apakah Anda yakin ingin mengimport data ini? Proses ini tidak dapat dibatalkan.');
        }
    </script>
</x-layouts.admin>