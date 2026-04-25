<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN directly, so we recreate the table
        Schema::create('jersey_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('master_ukuran_jersey_id')->nullable()->constrained('master_ukuran_jersey')->onDelete('set null');
            $table->enum('status', ['dipesan', 'diterima', 'dibatalkan'])->default('dipesan');
            $table->date('tanggal_pesan');
            $table->string('catatan', 255)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('siswa_id');
        });

        // Drop old table and rename new one
        Schema::drop('jersey');
        Schema::rename('jersey_new', 'jersey');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('jersey_old', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->unique()->constrained('siswa')->onDelete('cascade');
            $table->foreignId('ukuran_id')->nullable()->constrained('master_ukuran_jersey')->onDelete('set null');
            $table->string('jenis', 100);
            $table->integer('jumlah')->default(1);
            $table->enum('status', ['belum_pesan', 'sudah_pesan', 'sudah_terima'])->default('belum_pesan');
            $table->date('tanggal_pesan')->nullable();
            $table->date('tanggal_terima')->nullable();
            $table->string('catatan', 255)->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::drop('jersey');
        Schema::rename('jersey_old', 'jersey');
    }
};
