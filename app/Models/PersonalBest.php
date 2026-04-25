<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalBest extends Model
{
    use HasFactory;

    protected $table = 'personal_best';

    protected $fillable = [
        'siswa_id',
        'nomor_lomba',
        'gaya_renang',
        'jarak',
        'catatan_waktu',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Scopes
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByNomor($query, $gaya, $jarak)
    {
        return $query->where('gaya_renang', $gaya)->where('jarak', $jarak);
    }

    // Helper method untuk convert waktu ke seconds untuk comparison
    public function getWaktuInSecondsAttribute()
    {
        // Convert MM:SS.MS format to total seconds
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
