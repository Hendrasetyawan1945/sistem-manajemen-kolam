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
        
        .text-success {
            color: #28a745;
        }
        
        .text-danger {
            color: #dc3545;
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
        
        .row {
            display: flex;
            margin: 10px 0;
        }
        
        .col-6 {
            width: 50%;
            padding: 0 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $title }}</h1>
        <h2>Sistem Manajemen Klub Renang</h2>
        <p><strong>Periode:</strong> {{ $period }}</p>
        <p><strong>Digenerate:</strong> {{ $generated_at }}</p>
    </div>

    <!-- Summary -->
    <div class="info-box">
        <h3 style="margin-top: 0;">Ringkasan Keuangan</h3>
        <table class="summary-table">
            <tr>
                <td><strong>Total Pendapatan</strong></td>
                <td class="text-right">{{ formatRupiah($totalIncome) }}</td>
            </tr>
            <tr>
                <td><strong>Total Pengeluaran</strong></td>
                <td class="text-right">{{ formatRupiah($totalExpenses) }}</td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td><strong>Saldo Bersih</strong></td>
                <td class="text-right {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">
                    <strong>{{ formatRupiah($netBalance) }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- Income Breakdown -->
    <div class="section-title">Rincian Pendapatan</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incomeBreakdown as $category => $amount)
                <tr>
                    <td>{{ $category }}</td>
                    <td class="text-right">{{ formatRupiah($amount) }}</td>
                    <td class="text-right">
                        {{ $totalIncome > 0 ? number_format(($amount / $totalIncome) * 100, 1) : 0 }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Expense Breakdown -->
    <div class="section-title">Rincian Pengeluaran</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenseBreakdown as $category => $amount)
                <tr>
                    <td>{{ $category }}</td>
                    <td class="text-right">{{ formatRupiah($amount) }}</td>
                    <td class="text-right">
                        {{ $totalExpenses > 0 ? number_format(($amount / $totalExpenses) * 100, 1) : 0 }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Detailed Income -->
    @if($iuranRutin->count() > 0)
        <div class="section-title">Detail Iuran Rutin</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Periode</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($iuranRutin as $iuran)
                    <tr>
                        <td>{{ formatTanggal($iuran->tanggal_bayar) }}</td>
                        <td>{{ $iuran->siswa->nama }}</td>
                        <td>{{ $iuran->periode_text }}</td>
                        <td class="text-right">{{ formatRupiah($iuran->jumlah) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($iuranKejuaraan->count() > 0)
        <div class="section-title">Detail Iuran Kejuaraan</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Kejuaraan</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($iuranKejuaraan as $iuran)
                    <tr>
                        <td>{{ formatTanggal($iuran->tanggal_bayar) }}</td>
                        <td>{{ $iuran->siswa->nama }}</td>
                        <td>{{ $iuran->kejuaraan->nama }}</td>
                        <td class="text-right">{{ formatRupiah($iuran->jumlah) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Detailed Expenses -->
    @if($pengeluaran->count() > 0)
        <div class="section-title">Detail Pengeluaran</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluaran as $expense)
                    <tr>
                        <td>{{ formatTanggal($expense->tanggal) }}</td>
                        <td>{{ $expense->keterangan }}</td>
                        <td>{{ $expense->itemKas->nama }}</td>
                        <td class="text-right">{{ formatRupiah($expense->jumlah) }}</td>
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