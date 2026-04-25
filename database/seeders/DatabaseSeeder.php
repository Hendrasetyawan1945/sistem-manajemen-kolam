<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MetodePembayaranSeeder::class,
            ItemKasSeeder::class,
            MasterUkuranJerseySeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            SesiSeeder::class, // Sesi dengan Kehadiran
            KejuaraanSeeder::class,
            IuranRutinSeeder::class,
            IuranInsidentilSeeder::class,
            IuranKejuaraanSeeder::class,
            AngsuranSeeder::class,
            PengeluaranSeeder::class,
        ]);
    }
}
