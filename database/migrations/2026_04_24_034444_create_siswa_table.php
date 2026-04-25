<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->string('nama', 150);
            $table->string('nis', 50)->unique()->nullable();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'cuti', 'nonaktif'])->default('aktif');
            $table->string('nama_ortu', 150);
            $table->string('telepon_ortu', 20);
            $table->string('telepon_siswa', 20)->nullable();
            $table->date('tanggal_daftar');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('kelas_id');
            $table->index('status');
            $table->index('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
