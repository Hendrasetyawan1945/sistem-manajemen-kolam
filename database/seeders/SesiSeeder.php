<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sesi;
use App\Models\Kehadiran;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;

class SesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = Kelas::all();
        
        // Generate 20 sesi latihan untuk 2 bulan terakhir
        foreach ($kelas as $kelasItem) {
            // 4 sesi per kelas (sekitar 1 sesi per minggu selama 1 bulan)
            for ($i = 0; $i < 4; $i++) {
                $tanggal = Carbon::now()->subDays(rand(1, 30));
                
                $sesi = Sesi::create([
                    'kelas_id' => $kelasItem->id,
                    'coach_id' => $kelasItem->coach_id,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'waktu_mulai' => '16:00:00',
                    'waktu_selesai' => '18:00:00',
                    'catatan' => $this->getRandomMateri(),
                ]);
                
                // Auto-generate kehadiran untuk semua siswa di kelas ini
                $siswaList = Siswa::where('kelas_id', $kelasItem->id)->get();
                
                foreach ($siswaList as $siswa) {
                    Kehadiran::create([
                        'sesi_id' => $sesi->id,
                        'siswa_id' => $siswa->id,
                        'status' => $this->getRandomStatus(),
                    ]);
                }
            }
        }
    }
    
    private function getRandomMateri(): string
    {
        $materiList = [
            'Teknik Gaya Bebas',
            'Teknik Gaya Punggung',
            'Teknik Gaya Dada',
            'Teknik Gaya Kupu-kupu',
            'Latihan Kecepatan',
            'Latihan Daya Tahan',
            'Latihan Start dan Turn',
            'Latihan Pernapasan',
        ];
        
        return $materiList[array_rand($materiList)];
    }
    
    private function getRandomStatus(): string
    {
        $statuses = ['hadir', 'hadir', 'hadir', 'hadir', 'izin', 'sakit', 'alpha'];
        return $statuses[array_rand($statuses)];
    }
}
