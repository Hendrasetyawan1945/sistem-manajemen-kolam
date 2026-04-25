<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Iuran Rutin</title>
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
        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .summary-box td {
            border: 1px solid #999;
            padding: 5px 8px;
            text-align: center;
            width: 20%;
        }
        .summary-box .label {
            font-size: 8px;
            color: #555;
            text-transform: uppercase;
            display: block;
        }
        .summary-box .value {
            font-size: 12px;
            font-weight: bold;
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
        .data-table tfoot td {
            font-weight: bold;
            background: #f0f0f0;
            border: 1px solid #999;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .status-lunas {
            color: #006600;
            font-weight: bold;
        }
        .status-belum {
            color: #cc0000;
            font-weight: bold;
        }
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
        <h2>REKAP IURAN RUTIN</h2>
        <p>Tanggal Cetak: {{ formatTanggal($tanggalCetak) }}</p>
    </div>

    <div class="periode-info">
        <strong>Periode:</strong> {{ $namaBulan[$bulan] }} {{ $tahun }}
        @if($kelasId)
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Kelas:</strong> {{ $kelasList->firstWhere('id', $kelasId)->nama ?? '-' }}
        @else
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Kelas:</strong> Semua Kelas
        @endif
    </div>

    <!-- Summary -->
    <table class="summary-box">
        <tr>
            <td>
                <span class="label">Total Siswa</span>
                <span class="value">{{ $totalSiswa }}</span>
            </td>
            <td>
                <span class="label">Lunas</span>
                <span class="value" style="color:#006600;">{{ $totalLunas }}</span>
            </td>
            <td>
                <span class="label">Belum Lunas</span>
                <span class="value" style="color:#cc0000;">{{ $totalBelumLunas }}</span>
            </td>
            <td>
                <span class="label">Terkumpul</span>
                <span class="value" style="font-size:10px; color:#006600;">{{ formatRupiah($totalTerkumpul) }}</span>
            </td>
            <td>
                <span class="label">Tunggakan</span>
                <span class="value" style="font-size:10px; color:#cc6600;">{{ formatRupiah($totalOutstanding) }}</span>
            </td>
        </tr>
    </table>

    <!-- Detail Table -->
    <p class="section-title">DETAIL PER SISWA</p>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:4%;">No</th>
                <th style="width:25%;">Nama Siswa</th>
                <th style="width:18%;">Kelas</th>
                <th style="width:12%;">Status</th>
                <th style="width:15%;">Jumlah</th>
                <th style="width:16%;">Tanggal Bayar</th>
                <th style="width:10%;">Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row['siswa']->nama }}</td>
                    <td>{{ $row['kelas']->nama ?? '-' }}</td>
                    <td class="text-center">
                        @if($row['status'] === 'lunas')
                            <span class="status-lunas">&#10003; Lunas</span>
                        @else
                            <span class="status-belum">&#10007; Belum</span>
                        @endif
                    </td>
                    <td class="text-right">{{ formatRupiah($row['jumlah']) }}</td>
                    <td class="text-center">
                        @if($row['tanggal_bayar'])
                            {{ formatTanggal($row['tanggal_bayar']) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $row['metode'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">Terkumpul ({{ $totalLunas }} siswa):</td>
                <td class="text-right" style="color:#006600;">{{ formatRupiah($totalTerkumpul) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Tunggakan ({{ $totalBelumLunas }} siswa):</td>
                <td class="text-right" style="color:#cc0000;">{{ formatRupiah($totalOutstanding) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">Total:</td>
                <td class="text-right">{{ formatRupiah($totalTerkumpul + $totalOutstanding) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak oleh Sistem Manajemen Klub Renang &mdash; {{ formatTanggal($tanggalCetak) }}
    </div>
</body>
</html>
