<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetodePembayaran;

class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodePembayaran = [
            [
                'nama' => 'Transfer Bank',
                'keterangan' => 'Transfer melalui rekening bank klub',
                'is_active' => true,
            ],
            [
                'nama' => 'Tunai',
                'keterangan' => 'Pembayaran secara tunai langsung',
                'is_active' => true,
            ],
            [
                'nama' => 'QRIS',
                'keterangan' => 'Pembayaran melalui QRIS',
                'is_active' => true,
            ],
            [
                'nama' => 'E-Wallet',
                'keterangan' => 'Pembayaran melalui dompet digital',
                'is_active' => true,
            ],
        ];

        foreach ($metodePembayaran as $metode) {
            MetodePembayaran::updateOrCreate(
                ['nama' => $metode['nama']],
                $metode
            );
        }
    }
}
