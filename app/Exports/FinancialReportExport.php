<?php

namespace App\Exports;

use App\Models\IuranRutin;
use App\Models\IuranInsidentil;
use App\Models\IuranKejuaraan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class FinancialReportExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate);
        $this->endDate = Carbon::parse($endDate);
    }

    public function sheets(): array
    {
        return [
            'Ringkasan' => new FinancialSummarySheet($this->startDate, $this->endDate),
            'Iuran Rutin' => new IuranRutinSheet($this->startDate, $this->endDate),
            'Iuran Kejuaraan' => new IuranKejuaraanSheet($this->startDate, $this->endDate),
            'Pengeluaran' => new PengeluaranSheet($this->startDate, $this->endDate),
        ];
    }
}

class FinancialSummarySheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Calculate totals
        $iuranRutin = IuranRutin::where('status_bayar', 'lunas')
                               ->whereBetween('tanggal_bayar', [$this->startDate, $this->endDate])
                               ->sum('jumlah');

        $iuranInsidentil = IuranInsidentil::where('status_bayar', 'lunas')
                                        ->whereBetween('tanggal_bayar', [$this->startDate, $this->endDate])
                                        ->sum('jumlah');

        $iuranKejuaraan = IuranKejuaraan::where('status_bayar', 'lunas')
                                       ->whereBetween('tanggal_bayar', [$this->startDate, $this->endDate])
                                       ->sum('jumlah');

        $totalPendapatan = $iuranRutin + $iuranInsidentil + $iuranKejuaraan;

        $totalPengeluaran = Pengeluaran::whereBetween('tanggal', [$this->startDate, $this->endDate])
                                     ->sum('jumlah');

        $netBalance = $totalPendapatan - $totalPengeluaran;

        return collect([
            ['kategori' => 'PENDAPATAN', 'jumlah' => null],
            ['kategori' => 'Iuran Rutin', 'jumlah' => $iuranRutin],
            ['kategori' => 'Iuran Insidentil', 'jumlah' => $iuranInsidentil],
            ['kategori' => 'Iuran Kejuaraan', 'jumlah' => $iuranKejuaraan],
            ['kategori' => 'Total Pendapatan', 'jumlah' => $totalPendapatan],
            ['kategori' => '', 'jumlah' => null],
            ['kategori' => 'PENGELUARAN', 'jumlah' => null],
            ['kategori' => 'Total Pengeluaran', 'jumlah' => $totalPengeluaran],
            ['kategori' => '', 'jumlah' => null],
            ['kategori' => 'SALDO BERSIH', 'jumlah' => $netBalance],
        ]);
    }

    public function map($row): array
    {
        return [
            $row['kategori'],
            $row['jumlah']
        ];
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Jumlah (Rp)'
        ];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B:B')->getNumberFormat()->setFormatCode('#,##0');
        
        return [
            1 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            8 => ['font' => ['bold' => true]],
            10 => ['font' => ['bold' => true]],
        ];
    }
}

class IuranRutinSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return IuranRutin::where('status_bayar', 'lunas')
                        ->whereBetween('tanggal_bayar', [$this->startDate, $this->endDate])
                        ->with('siswa')
                        ->orderBy('tanggal_bayar')
                        ->get();
    }

    public function map($iuran): array
    {
        return [
            $iuran->tanggal_bayar->format('d/m/Y'),
            $iuran->siswa->nama,
            $iuran->periode_text,
            $iuran->jumlah,
            $iuran->metodePembayaran->nama ?? '-'
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Bayar',
            'Nama Siswa',
            'Periode',
            'Jumlah (Rp)',
            'Metode Pembayaran'
        ];
    }

    public function title(): string
    {
        return 'Iuran Rutin';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 15,
            'D' => 15,
            'E' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('D:D')->getNumberFormat()->setFormatCode('#,##0');
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class IuranKejuaraanSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return IuranKejuaraan::where('status_bayar', 'lunas')
                           ->whereBetween('tanggal_bayar', [$this->startDate, $this->endDate])
                           ->with(['siswa', 'kejuaraan'])
                           ->orderBy('tanggal_bayar')
                           ->get();
    }

    public function map($iuran): array
    {
        return [
            $iuran->tanggal_bayar->format('d/m/Y'),
            $iuran->siswa->nama,
            $iuran->kejuaraan->nama,
            $iuran->jumlah,
            $iuran->metodePembayaran->nama ?? '-'
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Bayar',
            'Nama Siswa',
            'Kejuaraan',
            'Jumlah (Rp)',
            'Metode Pembayaran'
        ];
    }

    public function title(): string
    {
        return 'Iuran Kejuaraan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 25,
            'C' => 25,
            'D' => 15,
            'E' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('D:D')->getNumberFormat()->setFormatCode('#,##0');
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class PengeluaranSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Pengeluaran::whereBetween('tanggal', [$this->startDate, $this->endDate])
                         ->with(['itemKas', 'metodePembayaran'])
                         ->orderBy('tanggal')
                         ->get();
    }

    public function map($pengeluaran): array
    {
        return [
            $pengeluaran->tanggal->format('d/m/Y'),
            $pengeluaran->keterangan,
            $pengeluaran->itemKas->nama,
            $pengeluaran->jumlah,
            $pengeluaran->metodePembayaran->nama ?? '-'
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Keterangan',
            'Kategori',
            'Jumlah (Rp)',
            'Metode Pembayaran'
        ];
    }

    public function title(): string
    {
        return 'Pengeluaran';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
            'C' => 20,
            'D' => 15,
            'E' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('D:D')->getNumberFormat()->setFormatCode('#,##0');
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}