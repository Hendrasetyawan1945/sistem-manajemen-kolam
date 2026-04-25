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
        Schema::create('catatan_waktu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kejuaraan_id')->constrained('kejuaraan')->onDelete('cascade');
            $table->string('nomor_lomba', 100);
            $table->string('gaya_renang', 100);
            $table->string('jarak', 50)->comment('Contoh: 50m, 100m');
            $table->string('catatan_waktu', 20)->comment('Format: 00:00.00');
            $table->integer('posisi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('siswa_id');
            $table->index('kejuaraan_id');
            $table->index(['gaya_renang', 'jarak']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_waktu');
    }
};
