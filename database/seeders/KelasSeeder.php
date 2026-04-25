<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\User;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coaches = User::where('role', 'coach')->get();
        
        $kelasData = [
            [
                'nama' => 'Kelas Pemula A',
                'deskripsi' => 'Kelas untuk pemula usia 6-10 tahun',
                'jadwal' => 'Senin, Rabu, Jumat - 16:00-17:30',
                'coach_id' => $coaches->first()->id ?? null,
                'kapasitas' => 15,
                'biaya_bulanan' => 350000,
                'is_active' => true,
            ],
            [
                'nama' => 'Kelas Pemula B',
                'deskripsi' => 'Kelas untuk pemula usia 11-15 tahun',
                'jadwal' => 'Selasa, Kamis, Sabtu - 16:00-17:30',
                'coach_id' => $coaches->skip(1)->first()->id ?? null,
                'kapasitas' => 15,
                'biaya_bulanan' => 350000,
                'is_active' => true,
            ],
            [
                'nama' => 'Kelas Menengah A',
                'deskripsi' => 'Kelas untuk level menengah',
                'jadwal' => 'Senin, Rabu, Jumat - 17:30-19:00',
                'coach_id' => $coaches->first()->id ?? null,
                'kapasitas' => 12,
                'biaya_bulanan' => 450000,
                'is_active' => true,
            ],
            [
                'nama' => 'Kelas Menengah B',
                'deskripsi' => 'Kelas untuk level menengah',
                'jadwal' => 'Selasa, Kamis, Sabtu - 17:30-19:00',
                'coach_id' => $coaches->skip(1)->first()->id ?? null,
                'kapasitas' => 12,
                'biaya_bulanan' => 450000,
                'is_active' => true,
            ],
            [
                'nama' => 'Kelas Prestasi',
                'deskripsi' => 'Kelas untuk atlet prestasi',
                'jadwal' => 'Setiap hari - 05:00-07:00',
                'coach_id' => $coaches->first()->id ?? null,
                'kapasitas' => 10,
                'biaya_bulanan' => 650000,
                'is_active' => true,
            ],
        ];

        foreach ($kelasData as $kelas) {
            Kelas::updateOrCreate(
                ['nama' => $kelas['nama']],
                $kelas
            );
        }
    }
}