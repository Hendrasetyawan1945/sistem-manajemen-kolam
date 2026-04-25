<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IuranKejuaraan;
use App\Models\Siswa;
use App\Models\Kejuaraan;
use App\Models\MetodePembayaran;
use App\Models\User;
use Carbon\Carbon;

class IuranKejuaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = Siswa::all();
        $kejuaraan = Kejuaraan::all();
        $metodePembayaran = MetodePembayaran::all();
        $admin = User::where('role', 'admin')->first();
        
        if ($siswa->isEmpty() || $kejuaraan->isEmpty() || $metodePembayaran->isEmpty() || !$admin) {
            return;
        }

        // Daftarkan beberapa siswa ke kejuaraan
        foreach ($kejuaraan as $k) {
            // Ambil 60% siswa untuk setiap kejuaraan
            $selectedSiswa = $siswa->random(ceil($siswa->count() * 0.6));
            
            foreach ($selectedSiswa as $s) {
                $statusBayar = rand(1, 10) <= 7 ? 'lunas' : 'belum'; // 70% lunas
                $tanggalBayar = null;
                $metodeId = null;
                
                if ($statusBayar === 'lunas') {
                    $tanggalBayar = Carbon::now()->subDays(rand(1, 30));
                    $metodeId = $metodePembayaran->random()->id;
                }

                IuranKejuaraan::updateOrCreate(
                    [
                        'siswa_id' => $s->id,
                        'kejuaraan_id' => $k->id,
                    ],
                    [
                        'jumlah' => match($k->nama) {
                            'Kejuaraan Nasional Junior 2026' => 500000,
                            'Kejuaraan Daerah DKI Jakarta' => 300000,
                            'Kejuaraan Antar Klub Jakarta' => 200000,
                            default => 300000
                        },
                        'status_bayar' => $statusBayar,
                        'tanggal_bayar' => $tanggalBayar,
                        'metode_pembayaran_id' => $metodeId,
                        'dibuat_oleh' => $admin->id,
                    ]
                );
            }
        }
    }
}