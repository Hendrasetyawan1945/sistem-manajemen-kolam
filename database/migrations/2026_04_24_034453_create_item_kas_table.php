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
        Schema::create('item_kas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->enum('kategori', ['pendapatan', 'pengeluaran']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_kas');
    }
};
