<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IuranInsidentil;
use App\Models\Siswa;
use App\Models\MetodePembayaran;
use App\Models\ItemKas;
use Carbon\Carbon;

class IuranInsidentilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswaList = Siswa::all();
        $metodePembayaran = MetodePembayaran::all();
        $itemKas = ItemKas::all();
        $admin = \App\Models\User::where('role', 'admin')->first();
        
        // Buat beberapa iuran insidentil untuk siswa random
        $namaItemList = [
            'Biaya Seragam Latihan',
            'Biaya Perlengkapan Renang',
            'Biaya Tes Kesehatan',
            'Biaya Foto Dokumentasi',
            'Biaya Sertifikat',
        ];
        
        foreach ($siswaList->random(min(10, $siswaList->count())) as $siswa) {
            $jumlahIuran = rand(1, 2); // 1-2 iuran insidentil per siswa
            
            for ($i = 0; $i < $jumlahIuran; $i++) {
                $tanggal = Carbon::now()->subDays(rand(1, 60));
                $statusBayar = rand(0, 10) > 3 ? 'lunas' : 'belum'; // 70% lunas
                
                $data = [
                    'siswa_id' => $siswa->id,
                    'item_kas_id' => $itemKas->random()->id,
                    'nama_item' => $namaItemList[array_rand($namaItemList)],
                    'jumlah' => rand(5, 20) * 10000, // 50rb - 200rb
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'status_bayar' => $statusBayar,
                    'dibuat_oleh' => $admin->id,
                ];
                
                if ($statusBayar === 'lunas') {
                    $data['tanggal_bayar'] = $tanggal->addDays(rand(1, 14))->format('Y-m-d');
                    $data['metode_pembayaran_id'] = $metodePembayaran->random()->id;
                }
                
                IuranInsidentil::create($data);
            }
        }
    }
}
