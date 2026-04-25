<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentCsvExport implements FromCollection, WithHeadings, WithMapping
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
            $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '',
            $siswa->jenis_kelamin,
            $siswa->alamat ?? '',
            $siswa->nama_orang_tua ?? '',
            $siswa->telepon_orang_tua ?? '',
            $siswa->kelas_id,
            $siswa->status,
        ];
    }

    public function headings(): array
    {
        return [
            'nama',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat',
            'nama_orang_tua',
            'telepon_orang_tua',
            'kelas_id',
            'status'
        ];
    }
}