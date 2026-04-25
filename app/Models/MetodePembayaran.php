<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran';

    protected $fillable = [
        'nama',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function iuranRutin()
    {
        return $this->hasMany(IuranRutin::class, 'metode_pembayaran_id');
    }

    public function iuranInsidentil()
    {
        return $this->hasMany(IuranInsidentil::class, 'metode_pembayaran_id');
    }

    public function iuranKejuaraan()
    {
        return $this->hasMany(IuranKejuaraan::class, 'metode_pembayaran_id');
    }

    public function detailAngsuran()
    {
        return $this->hasMany(DetailAngsuran::class, 'metode_pembayaran_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
