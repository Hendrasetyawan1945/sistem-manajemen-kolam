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
        
        .student-info {
            background-color: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
            border-radius: 5px;
        }
        
        .info-table {
            width: 100%;
            margin: 15px 0;
        }
        
        .info-table td {
            padding: 5px 10px;
            vertical-align: top;
        }
        
        .info-table .label {
            font-weight: bold;
            width: 150px;
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
        
        .score-circle {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            color: white;
            margin: 0 5px;
        }
        
        .score-excellent {
            background-color: #28a745;
        }
        
        .score-good {
            background-color: #17a2b8;
        }
        
        .score-fair {
            background-color: #ffc107;
            color: #333;
        }
        
        .score-poor {
            background-color: #dc3545;
        }
        
        .grade-row {
            background-color: #f8f9fa;
        }
        
        .pb-record {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 5px 0;
        }
        
        .competition-record {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 10px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $title }}</h1>
        <h2>Sistem Manajemen Klub Renang</h2>
        @if($periode)
            <p><strong>Periode:</strong> {{ $periode }}</p>
        @endif
        <p><strong>Digenerate:</strong> {{ $generated_at }}</p>
    </div>

    <!-- Student Information -->
    <div class="student-info">
        <h3 style="margin-top: 0;">Informasi Siswa</h3>
        <table class="info-table">
            <tr>
                <td class="label">Nama Lengkap:</td>
                <td>{{ $siswa->nama }}</td>
                <td class="label">NIS:</td>
                <td>{{ $siswa->nis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Lahir:</td>
                <td>{{ $siswa->formatted_tanggal_lahir }}</td>
                <td class="label">Umur:</td>
                <td>{{ $siswa->umur ?? '-' }} tahun</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin:</td>
                <td>{{ $siswa->jenis_kelamin_text }}</td>
                <td class="label">Status:</td>
                <td>{{ ucfirst($siswa->status) }}</td>
            </tr>
            <tr>
                <td class="label">Kelas:</td>
                <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                <td class="label">Tanggal Daftar:</td>
                <td>{{ $siswa->formatted_tanggal_daftar }}</td>
            </tr>
            <tr>
                <td class="label">Nama Orang Tua:</td>
                <td>{{ $siswa->nama_ortu }}</td>
                <td class="label">Telepon Orang Tua:</td>
                <td>{{ $siswa->telepon_ortu }}</td>
            </tr>
        </table>
    </div>

    <!-- Report Cards -->
    @if($raporList->count() > 0)
        <div class="section-title">Penilaian Rapor</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th class="text-center">Teknik</th>
                    <th class="text-center">Fisik</th>
                    <th class="text-center">Kedisiplinan</th>
                    <th class="text-center">Semangat</th>
                    <th class="text-center">Rata-rata</th>
                    <th>Pelatih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($raporList as $rapor)
                    @php
                        $average = ($rapor->nilai_teknik + $rapor->nilai_fisik + $rapor->nilai_kedisiplinan + $rapor->nilai_semangat) / 4;
                        $gradeClass = '';
                        if ($average >= 9) $gradeClass = 'score-excellent';
                        elseif ($average >= 8) $gradeClass = 'score-good';
                        elseif ($average >= 7) $gradeClass = 'score-fair';
                        else $gradeClass = 'score-poor';
                    @endphp
                    <tr>
                        <td>{{ $rapor->periode }}</td>
                        <td class="text-center">
                            <span class="score-circle {{ $rapor->nilai_teknik >= 8 ? 'score-good' : 'score-fair' }}">
                                {{ $rapor->nilai_teknik }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="score-circle {{ $rapor->nilai_fisik >= 8 ? 'score-good' : 'score-fair' }}">
                                {{ $rapor->nilai_fisik }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="score-circle {{ $rapor->nilai_kedisiplinan >= 8 ? 'score-good' : 'score-fair' }}">
                                {{ $rapor->nilai_kedisiplinan }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="score-circle {{ $rapor->nilai_semangat >= 8 ? 'score-good' : 'score-fair' }}">
                                {{ $rapor->nilai_semangat }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="score-circle {{ $gradeClass }}">
                                {{ number_format($average, 1) }}
                            </span>
                        </td>
                        <td>{{ $rapor->coach->name ?? '-' }}</td>
                    </tr>
                    @if($rapor->catatan)
                        <tr class="grade-row">
                            <td colspan="7">
                                <strong>Catatan:</strong> {{ $rapor->catatan }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Personal Best Records -->
    @if($personalBest->count() > 0)
        <div class="section-title">Catatan Waktu Terbaik (Personal Best)</div>
        @foreach($personalBest as $pb)
            <div class="pb-record">
                <strong>{{ $pb->nomor_lomba }}</strong> - {{ $pb->gaya_renang }} {{ $pb->jarak }}m
                <div style="float: right;">
                    <strong>{{ $pb->catatan_waktu }}</strong> 
                    <small>({{ formatTanggal($pb->tanggal) }})</small>
                </div>
                <div style="clear: both;">
                    <small>{{ $pb->keterangan }}</small>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Recent Competition Results -->
    @if($catatanWaktu->count() > 0)
        <div class="section-title">Hasil Kejuaraan Terbaru</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Kejuaraan</th>
                    <th>Nomor Lomba</th>
                    <th class="text-center">Waktu</th>
                    <th class="text-center">Posisi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($catatanWaktu as $waktu)
                    <tr>
                        <td>{{ $waktu->kejuaraan->nama ?? '-' }}</td>
                        <td>{{ $waktu->nomor_lomba }}</td>
                        <td class="text-center"><strong>{{ $waktu->catatan_waktu }}</strong></td>
                        <td class="text-center">
                            @if($waktu->posisi)
                                @if($waktu->posisi <= 3)
                                    <span class="score-circle score-excellent">{{ $waktu->posisi }}</span>
                                @else
                                    {{ $waktu->posisi }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $waktu->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Performance Summary -->
    @if($raporList->count() > 0)
        <div class="section-title">Ringkasan Prestasi</div>
        @php
            $avgTeknik = $raporList->avg('nilai_teknik');
            $avgFisik = $raporList->avg('nilai_fisik');
            $avgKedisiplinan = $raporList->avg('nilai_kedisiplinan');
            $avgSemangat = $raporList->avg('nilai_semangat');
            $overallAvg = ($avgTeknik + $avgFisik + $avgKedisiplinan + $avgSemangat) / 4;
        @endphp
        
        <table class="summary-table">
            <tr>
                <td><strong>Rata-rata Nilai Teknik</strong></td>
                <td class="text-center">{{ number_format($avgTeknik, 1) }}</td>
            </tr>
            <tr>
                <td><strong>Rata-rata Nilai Fisik</strong></td>
                <td class="text-center">{{ number_format($avgFisik, 1) }}</td>
            </tr>
            <tr>
                <td><strong>Rata-rata Kedisiplinan</strong></td>
                <td class="text-center">{{ number_format($avgKedisiplinan, 1) }}</td>
            </tr>
            <tr>
                <td><strong>Rata-rata Semangat</strong></td>
                <td class="text-center">{{ number_format($avgSemangat, 1) }}</td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td><strong>Rata-rata Keseluruhan</strong></td>
                <td class="text-center">
                    <strong>{{ number_format($overallAvg, 1) }}</strong>
                </td>
            </tr>
        </table>

        <div style="margin: 20px 0;">
            <strong>Jumlah Personal Best:</strong> {{ $personalBest->count() }} nomor<br>
            <strong>Jumlah Kejuaraan Diikuti:</strong> {{ $catatanWaktu->groupBy('kejuaraan_id')->count() }} kejuaraan<br>
            <strong>Total Catatan Waktu:</strong> {{ $catatanWaktu->count() }} catatan
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Rapor ini digenerate secara otomatis oleh Sistem Manajemen Klub Renang</p>
        <p>Dicetak pada: {{ formatTanggal(now()) }}</p>
        <br>
        <p>Tanda Tangan Pelatih: ________________________</p>
        <p>Tanda Tangan Orang Tua: ________________________</p>
    </div>
</body>
</html>