<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #666;
        }
        
        .info-box {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .summary-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }
        
        .badge-success {
            background-color: #28a745;
        }
        
        .badge-danger {
            background-color: #dc3545;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .stats-grid {
            display: flex;
            margin: 20px 0;
        }
        
        .stat-item {
            flex: 1;
            text-align: center;
            padding: 15px;
            margin: 0 5px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $title }}</h1>
        <h2>Sistem Manajemen Klub Renang</h2>
        <p><strong>Periode:</strong> {{ $period }}</p>
        @if($kelas)
            <p><strong>Kelas:</strong> {{ $kelas->nama }}</p>
        @endif
        <p><strong>Digenerate:</strong> {{ $generated_at }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="info-box">
        <h3 style="margin-top: 0;">Ringkasan Iuran</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $totalSiswa }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalLunas }}</div>
                <div class="stat-label">Sudah Lunas</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalBelum }}</div>
                <div class="stat-label">Belum Lunas</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalSiswa > 0 ? number_format(($totalLunas / $totalSiswa) * 100, 1) : 0 }}%</div>
                <div class="stat-label">Persentase Lunas</div>
            </div>
        </div>
        
        <table class="summary-table">
            <tr>
                <td><strong>Total Terkumpul</strong></td>
                <td class="text-right">{{ formatRupiah($totalCollected) }}</td>
            </tr>
            <tr>
                <td><strong>Total Tunggakan</strong></td>
                <td class="text-right">{{ formatRupiah($totalOutstanding) }}</td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td><strong>Total Keseluruhan</strong></td>
                <td class="text-right">
                    <strong>{{ formatRupiah($totalCollected + $totalOutstanding) }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- Detailed Student List -->
    <div class="section-title">Rincian Per Siswa</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th class="text-right">Jumlah</th>
                <th class="text-center">Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tuitionData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data['siswa']->nama }}</td>
                    <td>{{ $data['siswa']->kelas->nama ?? '-' }}</td>
                    <td class="text-right">{{ formatRupiah($data['jumlah']) }}</td>
                    <td class="text-center">
                        @if($data['status'] === 'lunas')
                            <span class="badge badge-success">LUNAS</span>
                        @else
                            <span class="badge badge-danger">BELUM</span>
                        @endif
                    </td>
                    <td>
                        {{ $data['tanggal_bayar'] ? formatTanggal($data['tanggal_bayar']) : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Students who haven't paid -->
    @php
        $belumLunas = collect($tuitionData)->where('status', 'belum');
    @endphp
    
    @if($belumLunas->count() > 0)
        <div class="section-title">Daftar Siswa Belum Lunas</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th class="text-right">Jumlah Tunggakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($belumLunas as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data['siswa']->nama }}</td>
                        <td>{{ $data['siswa']->kelas->nama ?? '-' }}</td>
                        <td class="text-right">{{ formatRupiah($data['jumlah']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Payment Summary by Class -->
    @if(!$kelas)
        @php
            $byClass = collect($tuitionData)->groupBy(function($item) {
                return $item['siswa']->kelas->nama ?? 'Tanpa Kelas';
            });
        @endphp
        
        <div class="section-title">Ringkasan Per Kelas</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th class="text-center">Total Siswa</th>
                    <th class="text-center">Lunas</th>
                    <th class="text-center">Belum</th>
                    <th class="text-right">Total Terkumpul</th>
                    <th class="text-right">Total Tunggakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byClass as $className => $classData)
                    @php
                        $classLunas = $classData->where('status', 'lunas')->count();
                        $classBelum = $classData->where('status', 'belum')->count();
                        $classCollected = $classData->where('status', 'lunas')->sum('jumlah');
                        $classOutstanding = $classData->where('status', 'belum')->sum('jumlah');
                    @endphp
                    <tr>
                        <td>{{ $className }}</td>
                        <td class="text-center">{{ $classData->count() }}</td>
                        <td class="text-center">{{ $classLunas }}</td>
                        <td class="text-center">{{ $classBelum }}</td>
                        <td class="text-right">{{ formatRupiah($classCollected) }}</td>
                        <td class="text-right">{{ formatRupiah($classOutstanding) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh Sistem Manajemen Klub Renang</p>
        <p>Dicetak pada: {{ formatTanggal(now()) }}</p>
    </div>
</body>
</html>