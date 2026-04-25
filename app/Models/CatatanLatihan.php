<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanLatihan extends Model
{
    use HasFactory;

    protected $table = 'catatan_latihan';

    protected $fillable = [
        'siswa_id',
        'sesi_id',
        'coach_id',
        'nomor_latihan',
        'gaya_renang',
        'jarak',
        'catatan_waktu',
        'catatan',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // Scopes
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeBySesi($query, $sesiId)
    {
        return $query->where('sesi_id', $sesiId);
    }

    public function scopeByCoach($query, $coachId)
    {
        return $query->where('coach_id', $coachId);
    }

    public function scopeByGaya($query, $gaya, $jarak = null)
    {
        $query = $query->where('gaya_renang', $gaya);
        if ($jarak) {
            $query->where('jarak', $jarak);
        }
        return $query;
    }

    // Helper method untuk convert waktu ke seconds
    public function getWaktuInSecondsAttribute()
    {
        $parts = explode(':', $this->catatan_waktu);
        if (count($parts) === 2) {
            $minutes = (int) $parts[0];
            $secondsParts = explode('.', $parts[1]);
            $seconds = (int) $secondsParts[0];
            $milliseconds = isset($secondsParts[1]) ? (int) $secondsParts[1] : 0;
            
            return ($minutes * 60) + $seconds + ($milliseconds / 100);
        }
        return 0;
    }
}
