<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'deskripsi',
        'jadwal',
        'coach_id',
        'kapasitas',
        'biaya_bulanan',
        'is_active',
    ];

    protected $casts = [
        'biaya_bulanan' => 'decimal:2',
        'is_active' => 'boolean',
        'kapasitas' => 'integer',
    ];

    // Relationships
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function sesi()
    {
        return $this->hasMany(Sesi::class, 'kelas_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithCoach($query)
    {
        return $query->with('coach');
    }

    // Accessors
    public function getSiswaCountAttribute()
    {
        return $this->siswa()->count();
    }

    public function getAvailableSlotAttribute()
    {
        return $this->kapasitas - $this->siswa_count;
    }

    public function getFormattedBiayaAttribute()
    {
        return formatRupiah($this->biaya_bulanan);
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'success' : 'secondary';
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }
}
