<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IuranRutin;
use App\Models\Siswa;
use App\Models\MetodePembayaran;
use App\Models\User;
use Carbon\Carbon;

class IuranRutinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = Siswa::all();
        $metodePembayaran = MetodePembayaran::all();
        $admin = User::where('role', 'admin')->first();
        
        if ($siswa->isEmpty() || $metodePembayaran->isEmpty() || !$admin) {
            return;
        }

        // Generate iuran untuk 3 bulan terakhir
        $bulanList = [
            ['bulan' => 2, 'tahun' => 2026], // Februari 2026
            ['bulan' => 3, 'tahun' => 2026], // Maret 2026
            ['bulan' => 4, 'tahun' => 2026], // April 2026
        ];

        foreach ($siswa as $s) {
            foreach ($bulanList as $periode) {
                $statusBayar = rand(1, 10) <= 8 ? 'lunas' : 'belum'; // 80% lunas
                $tanggalBayar = null;
                $metodeId = null;
                
                if ($statusBayar === 'lunas') {
                    $tanggalBayar = Carbon::create($periode['tahun'], $periode['bulan'], rand(1, 28));
                    $metodeId = $metodePembayaran->random()->id;
                }

                IuranRutin::updateOrCreate(
                    [
                        'siswa_id' => $s->id,
                        'bulan' => $periode['bulan'],
                        'tahun' => $periode['tahun'],
                    ],
                    [
                        'jumlah' => $s->kelas->biaya_bulanan ?? 350000,
                        'status_bayar' => $statusBayar,
                        'tanggal_bayar' => $tanggalBayar,
                        'metode_pembayaran_id' => $metodeId,
                        'catatan' => $statusBayar === 'lunas' ? 'Pembayaran tepat waktu' : null,
                        'dibuat_oleh' => $admin->id,
                    ]
                );
            }
        }
    }
}