<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemKas;

class ItemKasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemKas = [
            // Pendapatan
            [
                'nama' => 'Iuran Bulanan',
                'kategori' => 'pendapatan',
                'keterangan' => 'Iuran rutin bulanan siswa',
            ],
            [
                'nama' => 'Biaya Lomba',
                'kategori' => 'pendapatan',
                'keterangan' => 'Biaya pendaftaran lomba dari siswa',
            ],
            [
                'nama' => 'Iuran Insidentil',
                'kategori' => 'pendapatan',
                'keterangan' => 'Iuran khusus untuk kebutuhan tertentu',
            ],
            
            // Pengeluaran
            [
                'nama' => 'Peralatan Kolam',
                'kategori' => 'pengeluaran',
                'keterangan' => 'Pembelian dan perawatan peralatan kolam renang',
            ],
            [
                'nama' => 'Seragam',
                'kategori' => 'pengeluaran',
                'keterangan' => 'Pembelian seragam dan jersey klub',
            ],
            [
                'nama' => 'Transport Lomba',
                'kategori' => 'pengeluaran',
                'keterangan' => 'Biaya transportasi untuk mengikuti lomba',
            ],
            [
                'nama' => 'Konsumsi',
                'kategori' => 'pengeluaran',
                'keterangan' => 'Biaya konsumsi saat latihan atau lomba',
            ],
            [
                'nama' => 'Administrasi',
                'kategori' => 'pengeluaran',
                'keterangan' => 'Biaya administrasi dan operasional',
            ],
        ];

        foreach ($itemKas as $item) {
            ItemKas::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
