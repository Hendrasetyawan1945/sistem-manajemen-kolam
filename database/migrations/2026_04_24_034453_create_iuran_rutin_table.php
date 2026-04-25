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
        Schema::create('iuran_rutin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->tinyInteger('bulan')->comment('1-12');
            $table->year('tahun');
            $table->decimal('jumlah', 10, 2);
            $table->enum('status_bayar', ['lunas', 'belum', 'cicilan'])->default('belum');
            $table->date('tanggal_bayar')->nullable();
            $table->foreignId('metode_pembayaran_id')->nullable()->constrained('metode_pembayaran')->onDelete('set null');
            $table->string('catatan', 255)->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['siswa_id', 'bulan', 'tahun']);
            $table->index('siswa_id');
            $table->index(['bulan', 'tahun']);
            $table->index('status_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_rutin');
    }
};
