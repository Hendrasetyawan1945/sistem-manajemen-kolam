<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengeluaran;
use App\Models\ItemKas;
use Carbon\Carbon;

class PengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemKas = ItemKas::all();
        $admin = \App\Models\User::where('role', 'admin')->first();
        
        // Generate pengeluaran untuk 3 bulan terakhir
        for ($i = 0; $i < 30; $i++) {
            $tanggal = Carbon::now()->subDays(rand(1, 90));
            $item = $itemKas->random();
            
            Pengeluaran::create([
                'item_kas_id' => $item->id,
                'nama_pengeluaran' => $this->getNamaPengeluaran($item->nama),
                'jumlah' => $this->getJumlah($item->nama),
                'tanggal' => $tanggal->format('Y-m-d'),
                'keterangan' => $this->getKeterangan($item->nama),
                'dibuat_oleh' => $admin->id,
            ]);
        }
    }
    
    private function getNamaPengeluaran(string $itemNama): string
    {
        $namaMap = [
            'Sewa Kolam' => 'Pembayaran Sewa Kolam',
            'Gaji Pelatih' => 'Gaji Pelatih',
            'Peralatan Latihan' => 'Pembelian Peralatan',
            'Listrik & Air' => 'Tagihan Utilitas',
            'Transportasi' => 'Biaya Transportasi',
            'Konsumsi' => 'Konsumsi',
            'Administrasi' => 'Biaya Administrasi',
            'Pemeliharaan' => 'Pemeliharaan Fasilitas',
            'Lain-lain' => 'Pengeluaran Lain-lain',
        ];
        
        return $namaMap[$itemNama] ?? 'Pengeluaran ' . $itemNama;
    }
    
    private function getKeterangan(string $itemNama): string
    {
        $keteranganMap = [
            'Sewa Kolam' => 'Pembayaran sewa kolam bulan ' . Carbon::now()->subMonth(rand(0, 2))->format('F Y'),
            'Gaji Pelatih' => 'Gaji pelatih bulan ' . Carbon::now()->subMonth(rand(0, 2))->format('F Y'),
            'Peralatan Latihan' => 'Pembelian ' . ['pelampung', 'papan renang', 'pull buoy', 'fins'][rand(0, 3)],
            'Listrik & Air' => 'Tagihan listrik dan air bulan ' . Carbon::now()->subMonth(rand(0, 2))->format('F Y'),
            'Transportasi' => 'Biaya transportasi ke ' . ['kejuaraan', 'pelatihan', 'event'][rand(0, 2)],
            'Konsumsi' => 'Konsumsi untuk ' . ['latihan', 'event', 'rapat'][rand(0, 2)],
            'Administrasi' => 'Biaya ' . ['fotokopi', 'cetak sertifikat', 'ATK'][rand(0, 2)],
            'Pemeliharaan' => 'Pemeliharaan ' . ['peralatan', 'fasilitas', 'kolam'][rand(0, 2)],
            'Lain-lain' => 'Pengeluaran ' . ['tak terduga', 'insidentil', 'darurat'][rand(0, 2)],
        ];
        
        return $keteranganMap[$itemNama] ?? 'Pengeluaran ' . $itemNama;
    }
    
    private function getJumlah(string $itemNama): int
    {
        $jumlahMap = [
            'Sewa Kolam' => rand(200, 500) * 10000, // 2jt - 5jt
            'Gaji Pelatih' => rand(150, 300) * 10000, // 1.5jt - 3jt
            'Peralatan Latihan' => rand(10, 100) * 10000, // 100rb - 1jt
            'Listrik & Air' => rand(50, 150) * 10000, // 500rb - 1.5jt
            'Transportasi' => rand(20, 100) * 10000, // 200rb - 1jt
            'Konsumsi' => rand(10, 50) * 10000, // 100rb - 500rb
            'Administrasi' => rand(5, 30) * 10000, // 50rb - 300rb
            'Pemeliharaan' => rand(20, 100) * 10000, // 200rb - 1jt
            'Lain-lain' => rand(5, 50) * 10000, // 50rb - 500rb
        ];
        
        return $jumlahMap[$itemNama] ?? rand(10, 100) * 10000;
    }
}
