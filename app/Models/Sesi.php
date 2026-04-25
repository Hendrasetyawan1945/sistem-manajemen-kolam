<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    use HasFactory;

    protected $table = 'sesi';

    protected $fillable = [
        'kelas_id',
        'coach_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];

    // Relationships
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'sesi_id');
    }

    public function catatanLatihan()
    {
        return $this->hasMany(CatatanLatihan::class, 'sesi_id');
    }

    // Scopes
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByCoach($query, $coachId)
    {
        return $query->where('coach_id', $coachId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', today());
    }
}
