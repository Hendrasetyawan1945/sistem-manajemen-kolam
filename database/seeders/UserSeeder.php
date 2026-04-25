<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@renang.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'telepon' => '081234567890',
            ]
        );

        // Coach Users
        User::updateOrCreate(
            ['email' => 'coach1@renang.com'],
            [
                'name' => 'Coach Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'coach',
                'is_active' => true,
                'telepon' => '081234567891',
            ]
        );

        User::updateOrCreate(
            ['email' => 'coach2@renang.com'],
            [
                'name' => 'Coach Sari Dewi',
                'password' => Hash::make('password'),
                'role' => 'coach',
                'is_active' => true,
                'telepon' => '081234567892',
            ]
        );

        // Siswa Users
        User::updateOrCreate(
            ['email' => 'siswa1@renang.com'],
            [
                'name' => 'Andi Pratama',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'is_active' => true,
                'telepon' => '081234567893',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siswa2@renang.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'is_active' => true,
                'telepon' => '081234567894',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siswa3@renang.com'],
            [
                'name' => 'Rudi Hermawan',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'is_active' => true,
                'telepon' => '081234567895',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siswa4@renang.com'],
            [
                'name' => 'Maya Sari',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'is_active' => true,
                'telepon' => '081234567896',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siswa5@renang.com'],
            [
                'name' => 'Doni Setiawan',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'is_active' => true,
                'telepon' => '081234567897',
            ]
        );
    }
}
