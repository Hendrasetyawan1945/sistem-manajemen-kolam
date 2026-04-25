<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemKas extends Model
{
    use HasFactory;

    protected $table = 'item_kas';

    protected $fillable = [
        'nama',
        'kategori',
        'keterangan',
    ];

    // Relationships
    public function iuranInsidentil()
    {
        return $this->hasMany(IuranInsidentil::class, 'item_kas_id');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'item_kas_id');
    }

    // Scopes
    public function scopePendapatan($query)
    {
        return $query->where('kategori', 'pendapatan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('kategori', 'pengeluaran');
    }
}
