<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Kehadiran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 12px;
            font-weight: bold;
            margin-top: 4px;
        }
        .header p {
            font-size: 9px;
            color: #555;
            margin-top: 3px;
        }
        .periode-info {
            background: #f0f0f0;
            border: 1px solid #ccc;
            padding: 5px 8px;
            margin-bottom: 10px;
            font-size: 9px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            background: #555;
            color: #fff;
            padding: 4px 8px;
            margin-bottom: 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }
        .data-table th {
            background: #333;
            color: #fff;
            padding: 5px 6px;
            font-size: 9px;
            text-align: center;
            border: 1px solid #555;
        }
        .data-table td {
            border: 1px solid #ccc;
            padding: 4px 6px;
            font-size: 9px;
        }
        .data-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .persen-high { color: #006600; font-weight: bold; }
        .persen-mid  { color: #cc6600; font-weight: bold; }
        .persen-low  { color: #cc0000; font-weight: bold; }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>KLUB RENANG {{ strtoupper($namaKlub) }}</h1>
        <h2>REKAP KEHADIRAN SISWA</h2>
        <p>Tanggal Cetak: {{ formatTanggal($tanggalCetak) }}</p>
    </div>

    <div class="periode-info">
        <strong>Periode:</strong>
        {{ formatTanggal($tanggalDari->toDateString()) }} s/d {{ formatTanggal($tanggalSampai->toDateString()) }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Total Siswa:</strong> {{ $attendanceData->count() }}
    </div>

    <p class="section-title">DATA KEHADIRAN PER SISWA</p>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:4%;">No</th>
                <th style="width:28%;">Nama Siswa</th>
                <th style="width:18%;">Kelas</th>
                <th style="width:8%;">Total Sesi</th>
                <th style="width:8%;">Hadir</th>
                <th style="width:8%;">Izin</th>
                <th style="width:8%;">Sakit</th>
                <th style="width:8%;">Alpha</th>
                <th style="width:10%;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendanceData as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item['siswa']->nama }}</td>
                    <td>{{ $item['siswa']->kelas->nama ?? '-' }}</td>
                    <td class="text-center">{{ $item['total_sesi'] }}</td>
                    <td class="text-center">{{ $item['hadir'] }}</td>
                    <td class="text-center">{{ $item['izin'] }}</td>
                    <td class="text-center">{{ $item['sakit'] }}</td>
                    <td class="text-center">{{ $item['alpha'] }}</td>
                    <td class="text-center">
                        @php
                            $p = $item['persentase'];
                            $cls = $p >= 80 ? 'persen-high' : ($p >= 60 ? 'persen-mid' : 'persen-low');
                        @endphp
                        <span class="{{ $cls }}">{{ $p }}%</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding:10px;">
                        Tidak ada data kehadiran untuk periode ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh Sistem Manajemen Klub Renang &mdash; {{ formatTanggal($tanggalCetak) }}
    </div>
</body>
</html>
