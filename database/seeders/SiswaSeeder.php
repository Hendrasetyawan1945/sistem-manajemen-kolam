<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Carbon\Carbon;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswaUsers = User::where('role', 'siswa')->get();
        $kelas = Kelas::all();
        
        if ($siswaUsers->isEmpty() || $kelas->isEmpty()) {
            return;
        }

        $siswaData = [
            [
                'user_id' => $siswaUsers->get(0)->id,
                'kelas_id' => $kelas->get(0)->id,
                'nama' => 'Andi Pratama',
                'nis' => 'SW001',
                'tanggal_lahir' => Carbon::parse('2010-05-15'),
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'status' => 'aktif',
                'nama_ortu' => 'Budi Pratama',
                'telepon_ortu' => '081234567890',
                'telepon_siswa' => '081234567893',
                'tanggal_daftar' => Carbon::parse('2026-01-15'),
                'catatan' => 'Siswa berprestasi',
            ],
            [
                'user_id' => $siswaUsers->get(1)->id,
                'kelas_id' => $kelas->get(1)->id,
                'nama' => 'Siti Nurhaliza',
                'nis' => 'SW002',
                'tanggal_lahir' => Carbon::parse('2008-08-20'),
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Sudirman No. 456, Jakarta',
                'status' => 'aktif',
                'nama_ortu' => 'Ahmad Nurhaliza',
                'telepon_ortu' => '081234567891',
                'telepon_siswa' => '081234567894',
                'tanggal_daftar' => Carbon::parse('2026-01-20'),
                'catatan' => 'Teknik bagus',
            ],
            [
                'user_id' => $siswaUsers->get(2)->id,
                'kelas_id' => $kelas->get(2)->id,
                'nama' => 'Rudi Hermawan',
                'nis' => 'SW003',
                'tanggal_lahir' => Carbon::parse('2009-12-10'),
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Thamrin No. 789, Jakarta',
                'status' => 'aktif',
                'nama_ortu' => 'Sari Hermawan',
                'telepon_ortu' => '081234567892',
                'telepon_siswa' => '081234567895',
                'tanggal_daftar' => Carbon::parse('2026-02-01'),
                'catatan' => 'Perlu motivasi',
            ],
            [
                'user_id' => $siswaUsers->get(3)->id,
                'kelas_id' => $kelas->get(3)->id,
                'nama' => 'Maya Sari',
                'nis' => 'SW004',
                'tanggal_lahir' => Carbon::parse('2011-03-25'),
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Gatot Subroto No. 321, Jakarta',
                'status' => 'aktif',
                'nama_ortu' => 'Dedi Sari',
                'telepon_ortu' => '081234567893',
                'telepon_siswa' => '081234567896',
                'tanggal_daftar' => Carbon::parse('2026-02-10'),
                'catatan' => 'Rajin latihan',
            ],
            [
                'user_id' => $siswaUsers->get(4)->id,
                'kelas_id' => $kelas->get(4)->id,
                'nama' => 'Doni Setiawan',
                'nis' => 'SW005',
                'tanggal_lahir' => Carbon::parse('2007-07-18'),
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Kuningan No. 654, Jakarta',
                'status' => 'aktif',
                'nama_ortu' => 'Rina Setiawan',
                'telepon_ortu' => '081234567894',
                'telepon_siswa' => '081234567897',
                'tanggal_daftar' => Carbon::parse('2026-01-05'),
                'catatan' => 'Atlet potensial',
            ],
        ];

        foreach ($siswaData as $siswa) {
            Siswa::updateOrCreate(
                ['nis' => $siswa['nis']],
                $siswa
            );
        }
    }
}