<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\IuranRutin;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TuitionSummaryExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths, WithStyles
{
    protected $bulan;
    protected $tahun;
    protected $kelasId;

    public function __construct($bulan, $tahun, $kelasId = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kelasId = $kelasId;
    }

    public function collection()
    {
        // Get students
        $siswaQuery = Siswa::with(['kelas', 'user']);
        if ($this->kelasId) {
            $siswaQuery->where('kelas_id', $this->kelasId);
        }
        $siswaList = $siswaQuery->where('status', 'aktif')->orderBy('nama')->get();

        // Get tuition data
        $tuitionData = collect();

        foreach ($siswaList as $siswa) {
            $iuran = IuranRutin::where('siswa_id', $siswa->id)
                              ->where('bulan', $this->bulan)
                              ->where('tahun', $this->tahun)
                              ->first();

            $status = $iuran ? $iuran->status_bayar : 'belum';
            $jumlah = $iuran ? $iuran->jumlah : ($siswa->kelas->biaya_bulanan ?? 0);
            $tanggalBayar = $iuran && $iuran->status_bayar === 'lunas' ? $iuran->tanggal_bayar : null;

            $tuitionData->push([
                'siswa' => $siswa,
                'status' => $status,
                'jumlah' => $jumlah,
                'tanggal_bayar' => $tanggalBayar,
            ]);
        }

        return $tuitionData;
    }

    public function map($data): array
    {
        return [
            $data['siswa']->nama,
            $data['siswa']->kelas->nama ?? '-',
            $data['jumlah'],
            $data['status'] === 'lunas' ? 'LUNAS' : 'BELUM LUNAS',
            $data['tanggal_bayar'] ? $data['tanggal_bayar']->format('d/m/Y') : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Jumlah (Rp)',
            'Status',
            'Tanggal Bayar'
        ];
    }

    public function title(): string
    {
        return 'Laporan Iuran';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
            'C' => 15,
            'D' => 15,
            'E' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Add title in first row
        $sheet->setCellValue('A1', 'Laporan Iuran ' . $bulanNama[$this->bulan] . ' ' . $this->tahun);
        $sheet->mergeCells('A1:E1');

        $sheet->getStyle('C:C')->getNumberFormat()->setFormatCode('#,##0');
        
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}