<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'item_kas_id',
        'nama_pengeluaran',
        'jumlah',
        'tanggal',
        'keterangan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date',
    ];

    // Relationships
    public function itemKas()
    {
        return $this->belongsTo(ItemKas::class, 'item_kas_id');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Scopes
    public function scopeByItemKas($query, $itemKasId)
    {
        return $query->where('item_kas_id', $itemKasId);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    public function scopeByPeriode($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeByBulan($query, $bulan, $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->whereYear('tanggal', $tahun);
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
}
