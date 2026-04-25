<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterUkuranJersey;

class MasterUkuranJerseySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ukuranJersey = [
            [
                'ukuran' => 'XS',
                'keterangan' => 'Extra Small - Anak kecil',
            ],
            [
                'ukuran' => 'S',
                'keterangan' => 'Small - Anak remaja',
            ],
            [
                'ukuran' => 'M',
                'keterangan' => 'Medium - Dewasa kecil',
            ],
            [
                'ukuran' => 'L',
                'keterangan' => 'Large - Dewasa sedang',
            ],
            [
                'ukuran' => 'XL',
                'keterangan' => 'Extra Large - Dewasa besar',
            ],
            [
                'ukuran' => 'XXL',
                'keterangan' => 'Double Extra Large - Dewasa extra besar',
            ],
        ];

        foreach ($ukuranJersey as $ukuran) {
            MasterUkuranJersey::updateOrCreate(
                ['ukuran' => $ukuran['ukuran']],
                $ukuran
            );
        }
    }
}
