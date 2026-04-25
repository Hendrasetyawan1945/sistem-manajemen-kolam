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
        Schema::create('rapor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->string('periode', 50)->comment('Contoh: Semester 1 2024');
            $table->tinyInteger('teknik_renang')->comment('1-10');
            $table->tinyInteger('kondisi_fisik')->comment('1-10');
            $table->tinyInteger('kedisiplinan')->comment('1-10');
            $table->tinyInteger('semangat_berlatih')->comment('1-10');
            $table->text('catatan_coach')->nullable();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->timestamps();

            $table->index('siswa_id');
            $table->index('coach_id');
            $table->index('periode');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapor');
    }
};
