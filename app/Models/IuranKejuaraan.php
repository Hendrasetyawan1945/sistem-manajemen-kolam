<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranKejuaraan extends Model
{
    use HasFactory;

    protected $table = 'iuran_kejuaraan';

    protected $fillable = [
        'siswa_id',
        'kejuaraan_id',
        'jumlah',
        'status_bayar',
        'tanggal_bayar',
        'metode_pembayaran_id',
        'dibuat_oleh',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kejuaraan()
    {
        return $this->belongsTo(Kejuaraan::class, 'kejuaraan_id');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Scopes
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByKejuaraan($query, $kejuaraanId)
    {
        return $query->where('kejuaraan_id', $kejuaraanId);
    }

    public function scopeLunas($query)
    {
        return $query->where('status_bayar', 'lunas');
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status_bayar', 'belum');
    }
}
