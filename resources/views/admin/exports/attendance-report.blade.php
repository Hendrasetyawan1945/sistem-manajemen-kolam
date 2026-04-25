<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 15px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        
        .info-box {
            background-color: #f8f9fa;
            padding: 12px;
            margin: 15px 0;
            border-left: 4px solid #007bff;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        
        .summary-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .stats-grid {
            display: flex;
            margin: 15px 0;
        }
        
        .stat-item {
            flex: 1;
            text-align: center;
            padding: 10px;
            margin: 0 3px;
            background-color: #f8f9fa;
            border-radius: 3px;
        }
        
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }
        
        .percentage-bar {
            width: 100%;
            height: 15px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin: 3px 0;
        }
        
        .percentage-fill {
            height: 100%;
            transition: width 0.3s ease;
        }
        
        .percentage-excellent {
            background-color: #28a745;
        }
        
        .percentage-good {
            background-color: #17a2b8;
        }
        
        .percentage-fair {
            background-color: #ffc107;
        }
        
        .percentage-poor {
            background-color: #dc3545;
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
        @if($min_attendance > 0)
            <p><strong>Minimum Kehadiran:</strong> {{ $min_attendance }}%</p>
        @endif
        <p><strong>Digenerate:</strong> {{ $generated_at }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="info-box">
        <h3 style="margin-top: 0;">Ringkasan Kehadiran</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $totalSesi }}</div>
                <div class="stat-label">Total Sesi</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $totalSiswa }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
            <div class="stat-item">
                @php
                    $avgAttendance = collect($attendanceData)->avg('percentage');
                @endphp
                <div class="stat-number">{{ number_format($avgAttendance, 1) }}%</div>
                <div class="stat-label">Rata-rata Kehadiran</div>
            </div>
            <div class="stat-item">
                @php
                    $excellentCount = collect($attendanceData)->where('percentage', '>=', 90)->count();
                @endphp
                <div class="stat-number">{{ $excellentCount }}</div>
                <div class="stat-label">Kehadiran ≥90%</div>
            </div>
        </div>
    </div>

    <!-- Detailed Attendance List -->
    <div class="section-title">Rincian Kehadiran Per Siswa</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th class="text-center">Total Sesi</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Alpha</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Sakit</th>
                <th class="text-center">Persentase</th>
                <th>Grafik</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceData as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data['siswa']->nama }}</td>
                    <td>{{ $data['siswa']->kelas->nama ?? '-' }}</td>
                    <td class="text-center">{{ $data['total_sesi'] }}</td>
                    <td class="text-center">{{ $data['hadir'] }}</td>
                    <td class="text-center">{{ $data['alpha'] }}</td>
                    <td class="text-center">{{ $data['izin'] }}</td>
                    <td class="text-center">{{ $data['sakit'] }}</td>
                    <td class="text-center"><strong>{{ $data['percentage'] }}%</strong></td>
                    <td>
                        <div class="percentage-bar">
                            <div class="percentage-fill 
                                @if($data['percentage'] >= 90) percentage-excellent
                                @elseif($data['percentage'] >= 80) percentage-good
                                @elseif($data['percentage'] >= 70) percentage-fair
                                @else percentage-poor
                                @endif"
                                style="width: {{ $data['percentage'] }}%">
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Attendance Categories -->
    <div class="section-title">Kategori Kehadiran</div>
    @php
        $excellent = collect($attendanceData)->where('percentage', '>=', 90);
        $good = collect($attendanceData)->whereBetween('percentage', [80, 89.9]);
        $fair = collect($attendanceData)->whereBetween('percentage', [70, 79.9]);
        $poor = collect($attendanceData)->where('percentage', '<', 70);
    @endphp
    
    <table class="summary-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-center">Rentang</th>
                <th class="text-center">Jumlah Siswa</th>
                <th class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Sangat Baik</strong></td>
                <td class="text-center">≥ 90%</td>
                <td class="text-center">{{ $excellent->count() }}</td>
                <td class="text-center">{{ $totalSiswa > 0 ? number_format(($excellent->count() / $totalSiswa) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td><strong>Baik</strong></td>
                <td class="text-center">80% - 89%</td>
                <td class="text-center">{{ $good->count() }}</td>
                <td class="text-center">{{ $totalSiswa > 0 ? number_format(($good->count() / $totalSiswa) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td><strong>Cukup</strong></td>
                <td class="text-center">70% - 79%</td>
                <td class="text-center">{{ $fair->count() }}</td>
                <td class="text-center">{{ $totalSiswa > 0 ? number_format(($fair->count() / $totalSiswa) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td><strong>Kurang</strong></td>
                <td class="text-center">< 70%</td>
                <td class="text-center">{{ $poor->count() }}</td>
                <td class="text-center">{{ $totalSiswa > 0 ? number_format(($poor->count() / $totalSiswa) * 100, 1) : 0 }}%</td>
            </tr>
        </tbody>
    </table>

    <!-- Students with Poor Attendance -->
    @if($poor->count() > 0)
        <div class="section-title">Siswa dengan Kehadiran Kurang (< 70%)</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th class="text-center">Persentase Kehadiran</th>
                    <th class="text-center">Sesi Hadir / Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($poor as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data['siswa']->nama }}</td>
                        <td>{{ $data['siswa']->kelas->nama ?? '-' }}</td>
                        <td class="text-center"><strong>{{ $data['percentage'] }}%</strong></td>
                        <td class="text-center">{{ $data['hadir'] }} / {{ $data['total_sesi'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Summary by Class -->
    @if(!$kelas && $totalSiswa > 0)
        @php
            $byClass = collect($attendanceData)->groupBy(function($item) {
                return $item['siswa']->kelas->nama ?? 'Tanpa Kelas';
            });
        @endphp
        
        <div class="section-title">Ringkasan Per Kelas</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th class="text-center">Jumlah Siswa</th>
                    <th class="text-center">Rata-rata Kehadiran</th>
                    <th class="text-center">Kehadiran Tertinggi</th>
                    <th class="text-center">Kehadiran Terendah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byClass as $className => $classData)
                    @php
                        $classAvg = $classData->avg('percentage');
                        $classMax = $classData->max('percentage');
                        $classMin = $classData->min('percentage');
                    @endphp
                    <tr>
                        <td>{{ $className }}</td>
                        <td class="text-center">{{ $classData->count() }}</td>
                        <td class="text-center">{{ number_format($classAvg, 1) }}%</td>
                        <td class="text-center">{{ number_format($classMax, 1) }}%</td>
                        <td class="text-center">{{ number_format($classMin, 1) }}%</td>
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