<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kejuaraan;
use Carbon\Carbon;

class KejuaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kejuaraanData = [
            [
                'nama' => 'Kejuaraan Nasional Junior 2026',
                'tanggal_mulai' => Carbon::parse('2026-05-15'),
                'tanggal_selesai' => Carbon::parse('2026-05-17'),
                'lokasi' => 'Jakarta Aquatic Center',
                'keterangan' => 'Kejuaraan tingkat nasional untuk kategori junior. Biaya pendaftaran Rp 500.000',
            ],
            [
                'nama' => 'Kejuaraan Daerah DKI Jakarta',
                'tanggal_mulai' => Carbon::parse('2026-05-28'),
                'tanggal_selesai' => Carbon::parse('2026-05-30'),
                'lokasi' => 'Kolam Renang Senayan',
                'keterangan' => 'Kejuaraan tingkat daerah DKI Jakarta. Biaya pendaftaran Rp 300.000',
            ],
            [
                'nama' => 'Kejuaraan Antar Klub Jakarta',
                'tanggal_mulai' => Carbon::parse('2026-06-10'),
                'tanggal_selesai' => Carbon::parse('2026-06-12'),
                'lokasi' => 'Kolam Renang Kota',
                'keterangan' => 'Kejuaraan antar klub se-Jakarta. Biaya pendaftaran Rp 200.000',
            ],
        ];

        foreach ($kejuaraanData as $kejuaraan) {
            Kejuaraan::updateOrCreate(
                ['nama' => $kejuaraan['nama']],
                $kejuaraan
            );
        }
    }
}