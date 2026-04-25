<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Angsuran;
use App\Models\DetailAngsuran;
use App\Models\Siswa;
use App\Models\MetodePembayaran;
use Carbon\Carbon;

class AngsuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswaList = Siswa::all();
        $metodePembayaran = MetodePembayaran::all();
        $admin = \App\Models\User::where('role', 'admin')->first();
        
        $keteranganList = [
            'Biaya Pendaftaran Awal',
            'Biaya Peralatan Lengkap',
            'Biaya Training Camp',
            'Biaya Seragam Tim',
        ];
        
        // Buat angsuran untuk beberapa siswa
        foreach ($siswaList->random(min(5, $siswaList->count())) as $siswa) {
            $totalTagihan = rand(50, 150) * 10000; // 500rb - 1.5jt
            $jumlahCicilan = rand(3, 6); // 3-6 cicilan
            
            $totalDibayar = 0;
            
            $angsuran = Angsuran::create([
                'siswa_id' => $siswa->id,
                'keterangan' => $keteranganList[array_rand($keteranganList)],
                'total_tagihan' => $totalTagihan,
                'total_dibayar' => 0, // akan diupdate
                'sisa' => $totalTagihan,
                'status' => 'aktif',
                'dibuat_oleh' => $admin->id,
            ]);
            
            // Generate detail angsuran (pembayaran yang sudah dilakukan)
            $jumlahPembayaran = rand(1, $jumlahCicilan); // Beberapa cicilan sudah dibayar
            
            for ($i = 1; $i <= $jumlahPembayaran; $i++) {
                $jumlahBayar = rand(10, 50) * 10000; // Jumlah bervariasi
                $totalDibayar += $jumlahBayar;
                
                DetailAngsuran::create([
                    'angsuran_id' => $angsuran->id,
                    'jumlah_bayar' => $jumlahBayar,
                    'tanggal_bayar' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d'),
                    'metode_pembayaran_id' => $metodePembayaran->random()->id,
                    'catatan' => 'Pembayaran cicilan ke-' . $i,
                    'dibuat_oleh' => $admin->id,
                ]);
            }
            
            // Update angsuran dengan total dibayar dan sisa
            $sisa = $totalTagihan - $totalDibayar;
            $status = $sisa <= 0 ? 'lunas' : 'aktif';
            
            $angsuran->update([
                'total_dibayar' => $totalDibayar,
                'sisa' => max(0, $sisa),
                'status' => $status,
            ]);
        }
    }
}
