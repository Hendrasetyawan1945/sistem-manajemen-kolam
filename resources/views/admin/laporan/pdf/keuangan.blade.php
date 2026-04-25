<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Keuangan</title>
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
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
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
        .periode-info {
            background: #f0f0f0;
            border: 1px solid #ccc;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 10px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary-table th,
        .summary-table td {
            border: 1px solid #999;
            padding: 6px 8px;
        }
        .summary-table thead th {
            background: #333;
            color: #fff;
            font-size: 10px;
            text-align: center;
        }
        .summary-table tbody td {
            font-size: 11px;
        }
        .summary-table tfoot td {
            font-weight: bold;
            background: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            background: #555;
            color: #fff;
            padding: 5px 8px;
            margin-bottom: 0;
        }
        .two-col {
            width: 100%;
        }
        .two-col td {
            vertical-align: top;
            width: 50%;
            padding-right: 8px;
        }
        .two-col td:last-child {
            padding-right: 0;
            padding-left: 8px;
        }
        .income-row {
            background: #f0fff0;
        }
        .expense-row {
            background: #fff0f0;
        }
        .balance-positive {
            color: #006600;
            font-weight: bold;
        }
        .balance-negative {
            color: #cc0000;
            font-weight: bold;
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
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>KLUB RENANG {{ strtoupper($namaKlub) }}</h1>
        <h2>LAPORAN KEUANGAN</h2>
        <p>Tanggal Cetak: {{ formatTanggal($tanggalCetak) }}</p>
    </div>

    <div class="periode-info">
        <strong>Periode:</strong> {{ formatTanggal($tanggalDari) }} s/d {{ formatTanggal($tanggalSampai) }}
    </div>

    <!-- Summary -->
    <p class="section-title">RINGKASAN KEUANGAN</p>
    <table class="summary-table" style="margin-top:0;">
        <thead>
            <tr>
                <th style="width:50%;">Keterangan</th>
                <th style="width:50%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr class="income-row">
                <td>Total Pemasukan</td>
                <td class="text-right">{{ formatRupiah($totalIncome) }}</td>
            </tr>
            <tr class="expense-row">
                <td>Total Pengeluaran</td>
                <td class="text-right">{{ formatRupiah($totalExpenses) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Saldo Bersih</strong></td>
                <td class="text-right">
                    <span class="{{ $netBalance >= 0 ? 'balance-positive' : 'balance-negative' }}">
                        {{ formatRupiah($netBalance) }}
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Income & Expense Breakdown side by side -->
    <table class="two-col" style="margin-bottom:15px;">
        <tr>
            <td>
                <p class="section-title">RINCIAN PEMASUKAN</p>
                <table class="summary-table" style="margin-top:0;">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incomeBreakdown as $item)
                            @if($item['jumlah'] > 0)
                                @php $persen = $totalIncome > 0 ? round(($item['jumlah'] / $totalIncome) * 100, 1) : 0; @endphp
                                <tr>
                                    <td>{{ $item['kategori'] }}</td>
                                    <td class="text-right">{{ formatRupiah($item['jumlah']) }}</td>
                                    <td class="text-right">{{ $persen }}%</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td class="text-right"><strong>{{ formatRupiah($totalIncome) }}</strong></td>
                            <td class="text-right"><strong>100%</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </td>
            <td>
                <p class="section-title">RINCIAN PENGELUARAN</p>
                <table class="summary-table" style="margin-top:0;">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenseBreakdown as $item)
                            @php
                                $persen = $totalExpenses > 0 ? round(($item->total / $totalExpenses) * 100, 1) : 0;
                                $namaKategori = $item->itemKas->nama ?? 'Tidak Berkategori';
                            @endphp
                            <tr>
                                <td>{{ $namaKategori }}</td>
                                <td class="text-right">{{ formatRupiah($item->total) }}</td>
                                <td class="text-right">{{ $persen }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada pengeluaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td class="text-right"><strong>{{ formatRupiah($totalExpenses) }}</strong></td>
                            <td class="text-right"><strong>100%</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak oleh Sistem Manajemen Klub Renang &mdash; {{ formatTanggal($tanggalCetak) }}
    </div>
</body>
</html>
