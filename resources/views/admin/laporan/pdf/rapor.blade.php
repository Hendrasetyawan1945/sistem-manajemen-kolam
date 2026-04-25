<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapor Siswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
        }
        .header p {
            font-size: 10px;
            color: #555;
            margin-top: 3px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .info-table td {
            padding: 4px 8px;
            font-size: 11px;
        }
        .info-table .label {
            width: 35%;
            font-weight: bold;
            color: #555;
        }
        .info-table .colon {
            width: 5%;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            background: #444;
            color: #fff;
            padding: 5px 10px;
            margin-bottom: 0;
        }
        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .nilai-table th {
            background: #333;
            color: #fff;
            padding: 6px 10px;
            font-size: 10px;
            border: 1px solid #555;
        }
        .nilai-table td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            font-size: 11px;
        }
        .nilai-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        .nilai-table tfoot td {
            font-weight: bold;
            background: #e8e8e8;
            border: 1px solid #999;
        }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .grade-box {
            border: 2px solid #333;
            text-align: center;
            padding: 15px;
            margin-bottom: 18px;
        }
        .grade-box .grade-label {
            font-size: 10px;
            color: #777;
            text-transform: uppercase;
        }
        .grade-box .grade-value {
            font-size: 48px;
            font-weight: bold;
            color: #333;
            line-height: 1;
        }
        .grade-box .rata-rata {
            font-size: 12px;
            color: #555;
            margin-top: 4px;
        }
        .catatan-box {
            border: 1px solid #ccc;
            padding: 10px;
            background: #fafafa;
            margin-bottom: 18px;
            font-size: 11px;
            min-height: 50px;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .ttd-table td {
            text-align: center;
            padding: 8px;
            width: 50%;
            vertical-align: top;
        }
        .ttd-line {
            border-bottom: 1px solid #333;
            margin: 50px auto 5px;
            width: 70%;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
        .two-col-layout {
            width: 100%;
        }
        .two-col-layout td {
            vertical-align: top;
        }
        .col-left  { width: 65%; padding-right: 12px; }
        .col-right { width: 35%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>KLUB RENANG {{ strtoupper($namaKlub) }}</h1>
        <h2>RAPOR PENILAIAN SISWA</h2>
        <p>Tanggal Cetak: {{ formatTanggal($tanggalCetak) }}</p>
    </div>

    <!-- Info Siswa -->
    <p class="section-title">INFORMASI SISWA</p>
    <table class="info-table" style="margin-top:0; border:1px solid #ccc;">
        <tr>
            <td class="label">Nama Siswa</td>
            <td class="colon">:</td>
            <td><strong>{{ $rapor->siswa->nama }}</strong></td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td class="colon">:</td>
            <td>{{ $rapor->siswa->kelas->nama ?? $rapor->siswa->kelas->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Periode</td>
            <td class="colon">:</td>
            <td>{{ $rapor->periode }}</td>
        </tr>
        <tr>
            <td class="label">Coach</td>
            <td class="colon">:</td>
            <td>{{ $rapor->coach->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status Rapor</td>
            <td class="colon">:</td>
            <td>{{ ucfirst($rapor->status) }}</td>
        </tr>
    </table>

    <!-- Nilai & Grade side by side -->
    <table class="two-col-layout">
        <tr>
            <td class="col-left">
                <p class="section-title">PENILAIAN</p>
                <table class="nilai-table" style="margin-top:0;">
                    <thead>
                        <tr>
                            <th style="width:70%;">Aspek Penilaian</th>
                            <th style="width:30%;">Nilai (1-10)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Teknik Renang</td>
                            <td class="text-center"><strong>{{ $rapor->teknik_renang }}</strong></td>
                        </tr>
                        <tr>
                            <td>Kondisi Fisik</td>
                            <td class="text-center"><strong>{{ $rapor->kondisi_fisik }}</strong></td>
                        </tr>
                        <tr>
                            <td>Kedisiplinan</td>
                            <td class="text-center"><strong>{{ $rapor->kedisiplinan }}</strong></td>
                        </tr>
                        <tr>
                            <td>Semangat Berlatih</td>
                            <td class="text-center"><strong>{{ $rapor->semangat_berlatih }}</strong></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Rata-rata</strong></td>
                            <td class="text-center"><strong>{{ number_format($rapor->rata_rata, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </td>
            <td class="col-right">
                <p class="section-title">GRADE</p>
                <div class="grade-box" style="margin-top:0;">
                    <div class="grade-label">Nilai Akhir</div>
                    <div class="grade-value">{{ $rapor->grade }}</div>
                    <div class="rata-rata">Rata-rata: {{ number_format($rapor->rata_rata, 2) }}</div>
                </div>

                <p class="section-title">SKALA NILAI</p>
                <table style="width:100%; border-collapse:collapse; margin-top:0; font-size:10px;">
                    <tr><td style="padding:3px 6px; border:1px solid #ccc;"><strong>A</strong></td><td style="padding:3px 6px; border:1px solid #ccc;">9.0 - 10.0</td></tr>
                    <tr><td style="padding:3px 6px; border:1px solid #ccc;"><strong>B+</strong></td><td style="padding:3px 6px; border:1px solid #ccc;">8.0 - 8.9</td></tr>
                    <tr><td style="padding:3px 6px; border:1px solid #ccc;"><strong>B</strong></td><td style="padding:3px 6px; border:1px solid #ccc;">7.0 - 7.9</td></tr>
                    <tr><td style="padding:3px 6px; border:1px solid #ccc;"><strong>C+</strong></td><td style="padding:3px 6px; border:1px solid #ccc;">6.0 - 6.9</td></tr>
                    <tr><td style="padding:3px 6px; border:1px solid #ccc;"><strong>C</strong></td><td style="padding:3px 6px; border:1px solid #ccc;">5.0 - 5.9</td></tr>
                    <tr><td style="padding:3px 6px; border:1px solid #ccc;"><strong>D</strong></td><td style="padding:3px 6px; border:1px solid #ccc;">&lt; 5.0</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Catatan Coach -->
    <p class="section-title" style="margin-top:12px;">CATATAN COACH</p>
    <div class="catatan-box">
        {{ $rapor->catatan_coach ?? 'Tidak ada catatan.' }}
    </div>

    <!-- Tanda Tangan -->
    <table class="ttd-table">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div>Kepala Klub Renang</div>
                <div class="ttd-line"></div>
                <div>( _________________________ )</div>
            </td>
            <td>
                <div>{{ $rapor->periode }},</div>
                <div>Coach</div>
                <div class="ttd-line"></div>
                <div>( {{ $rapor->coach->name ?? '________________________' }} )</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak oleh Sistem Manajemen Klub Renang &mdash; {{ formatTanggal($tanggalCetak) }}
    </div>
</body>
</html>
