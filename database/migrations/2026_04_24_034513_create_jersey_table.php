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
        Schema::create('jersey', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->unique()->constrained('siswa')->onDelete('cascade');
            $table->foreignId('ukuran_id')->nullable()->constrained('master_ukuran_jersey')->onDelete('set null');
            $table->string('jenis', 100)->comment('Contoh: Jersey Latihan');
            $table->integer('jumlah')->default(1);
            $table->enum('status', ['belum_pesan', 'sudah_pesan', 'sudah_terima'])->default('belum_pesan');
            $table->date('tanggal_pesan')->nullable();
            $table->date('tanggal_terima')->nullable();
            $table->string('catatan', 255)->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jersey');
    }
};
