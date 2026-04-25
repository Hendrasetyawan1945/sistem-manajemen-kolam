<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanWaktu extends Model
{
    use HasFactory;

    protected $table = 'catatan_waktu';

    protected $fillable = [
        'siswa_id',
        'kejuaraan_id',
        'nomor_lomba',
        'gaya_renang',
        'jarak',
        'catatan_waktu',
        'posisi',
        'keterangan',
    ];

    protected $casts = [
        'posisi' => 'integer',
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

    // Scopes
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByKejuaraan($query, $kejuaraanId)
    {
        return $query->where('kejuaraan_id', $kejuaraanId);
    }

    public function scopeByNomor($query, $gaya, $jarak)
    {
        return $query->where('gaya_renang', $gaya)->where('jarak', $jarak);
    }

    public function scopeJuara($query)
    {
        return $query->whereIn('posisi', [1, 2, 3]);
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

    // Method untuk update Personal Best
    public function updatePersonalBest()
    {
        $personalBest = PersonalBest::where([
            'siswa_id' => $this->siswa_id,
            'gaya_renang' => $this->gaya_renang,
            'jarak' => $this->jarak,
        ])->first();

        $waktuBaru = $this->waktu_in_seconds;

        if (!$personalBest) {
            // Buat personal best baru
            PersonalBest::create([
                'siswa_id' => $this->siswa_id,
                'nomor_lomba' => $this->nomor_lomba,
                'gaya_renang' => $this->gaya_renang,
                'jarak' => $this->jarak,
                'catatan_waktu' => $this->catatan_waktu,
                'tanggal' => now()->toDateString(),
                'keterangan' => "Dari kejuaraan: {$this->kejuaraan->nama}",
            ]);
        } else {
            // Update jika waktu lebih baik (lebih kecil)
            $waktuLama = $personalBest->waktu_in_seconds;
            if ($waktuBaru < $waktuLama) {
                $personalBest->update([
                    'catatan_waktu' => $this->catatan_waktu,
                    'tanggal' => now()->toDateString(),
                    'keterangan' => "Dari kejuaraan: {$this->kejuaraan->nama}",
                ]);
            }
        }
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($catatanWaktu) {
            $catatanWaktu->updatePersonalBest();
        });

        static::updated(function ($catatanWaktu) {
            $catatanWaktu->updatePersonalBest();
        });
    }
}
