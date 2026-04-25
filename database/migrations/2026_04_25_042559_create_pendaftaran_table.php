<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();

            // Data calon siswa
            $table->string('nama', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->string('nama_ortu', 100);
            $table->string('telepon_ortu', 20);
            $table->string('email_ortu', 100)->nullable();

            // Preferensi kelas
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();

            // Akun login yang akan dibuat
            $table->string('email', 100)->unique();
            $table->string('password'); // hashed

            // Status pendaftaran
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable(); // alasan tolak / catatan

            // Relasi ke siswa setelah disetujui
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->nullOnDelete();
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
