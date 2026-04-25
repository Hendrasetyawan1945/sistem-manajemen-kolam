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
        Schema::create('iuran_kejuaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kejuaraan_id')->constrained('kejuaraan')->onDelete('cascade');
            $table->decimal('jumlah', 10, 2);
            $table->enum('status_bayar', ['lunas', 'belum'])->default('belum');
            $table->date('tanggal_bayar')->nullable();
            $table->foreignId('metode_pembayaran_id')->nullable()->constrained('metode_pembayaran')->onDelete('set null');
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['siswa_id', 'kejuaraan_id']);
            $table->index('siswa_id');
            $table->index('kejuaraan_id');
            $table->index('status_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_kejuaraan');
    }
};
