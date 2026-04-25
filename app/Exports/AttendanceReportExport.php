<?php

namespace App\Exports;

use App\Models\Sesi;
use App\Models\Kehadiran;
use App\Models\Kelas;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $kelasId;
    protected $minAttendance;

    public function __construct($startDate, $endDate, $kelasId = null, $minAttendance = 0)
    {
        $this->startDate = Carbon::parse($startDate);
        $this->endDate = Carbon::parse($endDate);
        $this->kelasId = $kelasId;
        $this->minAttendance = $minAttendance;
    }

    public function collection()
    {
        // Get sessions in date range
        $sesiQuery = Sesi::with(['kelas', 'kehadiran.siswa'])
                        ->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        
        if ($this->kelasId) {
            $sesiQuery->where('kelas_id', $this->kelasId);
        }
        
        $sesiList = $sesiQuery->orderBy('tanggal')->get();

        // Calculate attendance per student
        $siswaAttendance = [];

        foreach ($sesiList as $sesi) {
            foreach ($sesi->kehadiran as $kehadiran) {
                $siswaId = $kehadiran->siswa_id;
                
                if (!isset($siswaAttendance[$siswaId])) {
                    $siswaAttendance[$siswaId] = [
                        'siswa' => $kehadiran->siswa,
                        'total_sesi' => 0,
                        'hadir' => 0,
                        'alpha' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                    ];
                }
                
                $siswaAttendance[$siswaId]['total_sesi']++;
                $siswaAttendance[$siswaId][$kehadiran->status]++;
            }
        }

        // Calculate percentages and filter by minimum attendance
        $attendanceData = collect();
        foreach ($siswaAttendance as $siswaId => $data) {
            $percentage = $data['total_sesi'] > 0 ? ($data['hadir'] / $data['total_sesi']) * 100 : 0;
            
            if ($percentage >= $this->minAttendance) {
                $attendanceData->push(array_merge($data, [
                    'percentage' => round($percentage, 1)
                ]));
            }
        }

        // Sort by attendance percentage descending
        return $attendanceData->sortByDesc('percentage');
    }

    public function map($data): array
    {
        return [
            $data['siswa']->nama,
            $data['siswa']->kelas->nama ?? '-',
            $data['total_sesi'],
            $data['hadir'],
            $data['izin'],
            $data['sakit'],
            $data['alpha'],
            $data['percentage'] . '%',
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Total Sesi',
            'Hadir',
            'Izin',
            'Sakit',
            'Alpha',
            'Persentase Kehadiran'
        ];
    }

    public function title(): string
    {
        return 'Laporan Kehadiran';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
            'C' => 12,
            'D' => 10,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}