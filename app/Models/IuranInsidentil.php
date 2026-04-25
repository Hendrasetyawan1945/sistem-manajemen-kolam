<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranInsidentil extends Model
{
    use HasFactory;

    protected $table = 'iuran_insidentil';

    protected $fillable = [
        'siswa_id',
        'item_kas_id',
        'nama_item',
        'jumlah',
        'status_bayar',
        'tanggal',
        'tanggal_bayar',
        'metode_pembayaran_id',
        'catatan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date',
        'tanggal_bayar' => 'date',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function itemKas()
    {
        return $this->belongsTo(ItemKas::class, 'item_kas_id');
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

    public function scopeByItemKas($query, $itemKasId)
    {
        return $query->where('item_kas_id', $itemKasId);
    }

    public function scopeLunas($query)
    {
        return $query->where('status_bayar', 'lunas');
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status_bayar', 'belum');
    }

    public function scopeByPeriode($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    // Accessors
    public function getFormattedJumlahAttribute()
    {
        return formatRupiah($this->jumlah);
    }

    public function getFormattedTanggalAttribute()
    {
        return formatTanggal($this->tanggal);
    }

    public function getFormattedTanggalBayarAttribute()
    {
        return $this->tanggal_bayar ? formatTanggal($this->tanggal_bayar) : '-';
    }
}
