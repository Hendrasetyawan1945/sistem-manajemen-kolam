<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\Rapor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentReportCardExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths, WithStyles
{
    protected $siswaId;
    protected $periode;

    public function __construct($siswaId, $periode = null)
    {
        $this->siswaId = $siswaId;
        $this->periode = $periode;
    }

    public function collection()
    {
        $siswa = Siswa::with(['kelas', 'user'])->findOrFail($this->siswaId);

        // Get rapor data
        $raporQuery = Rapor::where('siswa_id', $siswa->id)->with('coach');
        
        if ($this->periode) {
            $raporQuery->where('periode', $this->periode);
        }
        
        $raporList = $raporQuery->orderBy('periode', 'desc')->get();

        // Add student info to each rapor record
        return $raporList->map(function ($rapor) use ($siswa) {
            return [
                'siswa' => $siswa,
                'rapor' => $rapor,
            ];
        });
    }

    public function map($data): array
    {
        $rapor = $data['rapor'];
        $siswa = $data['siswa'];
        
        return [
            $rapor->periode,
            $rapor->nilai_teknik,
            $rapor->nilai_fisik,
            $rapor->nilai_kedisiplinan,
            $rapor->nilai_semangat,
            round(($rapor->nilai_teknik + $rapor->nilai_fisik + $rapor->nilai_kedisiplinan + $rapor->nilai_semangat) / 4, 1),
            $rapor->catatan ?? '-',
            $rapor->coach->nama ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Periode',
            'Nilai Teknik',
            'Nilai Fisik',
            'Nilai Kedisiplinan',
            'Nilai Semangat',
            'Rata-rata',
            'Catatan',
            'Coach'
        ];
    }

    public function title(): string
    {
        return 'Rapor Siswa';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 12,
            'C' => 12,
            'D' => 15,
            'E' => 15,
            'F' => 12,
            'G' => 30,
            'H' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $siswa = Siswa::with(['kelas', 'user'])->findOrFail($this->siswaId);
        
        // Add title and student info in first rows
        $sheet->setCellValue('A1', 'RAPOR SISWA');
        $sheet->mergeCells('A1:H1');
        
        $sheet->setCellValue('A2', 'Nama: ' . $siswa->nama);
        $sheet->mergeCells('A2:H2');
        
        $sheet->setCellValue('A3', 'Kelas: ' . ($siswa->kelas->nama ?? '-'));
        $sheet->mergeCells('A3:H3');

        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            3 => ['font' => ['bold' => true, 'size' => 12]],
            4 => ['font' => ['bold' => true]],
        ];
    }
}