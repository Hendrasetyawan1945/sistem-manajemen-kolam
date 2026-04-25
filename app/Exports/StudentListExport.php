<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentListExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithColumnWidths, WithStyles
{
    protected $kelasId;
    protected $status;

    public function __construct($kelasId = null, $status = null)
    {
        $this->kelasId = $kelasId;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Siswa::with(['kelas', 'user']);
        
        if ($this->kelasId) {
            $query->where('kelas_id', $this->kelasId);
        }
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        return $query->orderBy('nama')->get();
    }

    public function map($siswa): array
    {
        return [
            $siswa->nama,
            $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d/m/Y') : '-',
            $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $siswa->alamat ?? '-',
            $siswa->nama_orang_tua ?? '-',
            $siswa->telepon_orang_tua ?? '-',
            $siswa->kelas->nama ?? '-',
            ucfirst($siswa->status),
            $siswa->created_at->format('d/m/Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Alamat',
            'Nama Orang Tua',
            'Telepon Orang Tua',
            'Kelas',
            'Status',
            'Tanggal Daftar'
        ];
    }

    public function title(): string
    {
        return 'Daftar Siswa';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 15,
            'C' => 15,
            'D' => 30,
            'E' => 25,
            'F' => 18,
            'G' => 20,
            'H' => 12,
            'I' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}