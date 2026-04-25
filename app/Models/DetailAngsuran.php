<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAngsuran extends Model
{
    use HasFactory;

    protected $table = 'detail_angsuran';

    protected $fillable = [
        'angsuran_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'metode_pembayaran_id',
        'catatan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    // Relationships
    public function angsuran()
    {
        return $this->belongsTo(Angsuran::class, 'angsuran_id');
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
    public function scopeByAngsuran($query, $angsuranId)
    {
        return $query->where('angsuran_id', $angsuranId);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal_bayar', $tanggal);
    }

    public function scopeByPeriode($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_bayar', [$startDate, $endDate]);
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($detail) {
            // Update angsuran total_dibayar dan sisa
            $detail->angsuran->updateSisa();
        });

        static::deleted(function ($detail) {
            // Update angsuran total_dibayar dan sisa
            $angsuran = $detail->angsuran;
            $angsuran->total_dibayar -= $detail->jumlah_bayar;
            $angsuran->updateSisa();
        });
    }
}
