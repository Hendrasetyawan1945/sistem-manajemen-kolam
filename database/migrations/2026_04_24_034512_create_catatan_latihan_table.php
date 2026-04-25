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
        Schema::create('catatan_latihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('sesi_id')->constrained('sesi')->onDelete('cascade');
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_latihan', 100);
            $table->string('gaya_renang', 100);
            $table->string('jarak', 50);
            $table->string('catatan_waktu', 20)->comment('Format: 00:00.00');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('siswa_id');
            $table->index('sesi_id');
            $table->index('coach_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_latihan');
    }
};
