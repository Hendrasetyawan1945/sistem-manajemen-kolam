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
        Schema::create('detail_angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('angsuran_id')->constrained('angsuran')->onDelete('cascade');
            $table->decimal('jumlah_bayar', 10, 2);
            $table->date('tanggal_bayar');
            $table->foreignId('metode_pembayaran_id')->nullable()->constrained('metode_pembayaran')->onDelete('set null');
            $table->string('catatan', 255)->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('angsuran_id');
            $table->index('tanggal_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_angsuran');
    }
};
