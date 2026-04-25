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
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->string('keterangan', 200);
            $table->decimal('total_tagihan', 10, 2);
            $table->decimal('total_dibayar', 10, 2)->default(0);
            $table->decimal('sisa', 10, 2)->comment('computed atau disimpan');
            $table->enum('status', ['aktif', 'lunas'])->default('aktif');
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('siswa_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
