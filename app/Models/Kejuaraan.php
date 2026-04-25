<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kejuaraan extends Model
{
    use HasFactory;

    protected $table = 'kejuaraan';

    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'biaya_pendaftaran',
        'deskripsi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relationships
    public function iuranKejuaraan()
    {
        return $this->hasMany(IuranKejuaraan::class, 'kejuaraan_id');
    }

    public function catatanWaktu()
    {
        return $this->hasMany(CatatanWaktu::class, 'kejuaraan_id');
    }

    public function peserta()
    {
        return $this->hasManyThrough(Siswa::class, IuranKejuaraan::class, 'kejuaraan_id', 'id', 'id', 'siswa_id');
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_mulai', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('tanggal_selesai', '<', now()->toDateString());
    }

    public function scopeOngoing($query)
    {
        return $query->where('tanggal_mulai', '<=', now()->toDateString())
                    ->where(function($q) {
                        $q->whereNull('tanggal_selesai')
                          ->orWhere('tanggal_selesai', '>=', now()->toDateString());
                    });
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->whereYear('tanggal_mulai', $tahun);
    }

    // Accessors
    public function getStatusAttribute()
    {
        $now = now()->toDateString();
        
        if ($this->tanggal_mulai > $now) {
            return 'upcoming';
        } elseif ($this->tanggal_selesai && $this->tanggal_selesai < $now) {
            return 'finished';
        } else {
            return 'ongoing';
        }
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'upcoming' => 'Akan Datang',
            'ongoing' => 'Sedang Berlangsung',
            'finished' => 'Selesai',
            default => 'Unknown'
        };
    }

    public function getDurasiAttribute()
    {
        if (!$this->tanggal_selesai) {
            return '1 hari';
        }
        
        $durasi = $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
        return $durasi . ' hari';
    }

    // Methods
    public function getTotalPeserta()
    {
        return $this->iuranKejuaraan()->count();
    }

    public function getTotalPendapatan()
    {
        return $this->iuranKejuaraan()->where('status_bayar', 'lunas')->sum('jumlah');
    }
}
