<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nama',
        'nis',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'foto',
        'status',
        'nama_ortu',
        'telepon_ortu',
        'telepon_siswa',
        'tanggal_daftar',
        'catatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'siswa_id');
    }

    public function iuranRutin()
    {
        return $this->hasMany(IuranRutin::class, 'siswa_id');
    }

    public function iuranInsidentil()
    {
        return $this->hasMany(IuranInsidentil::class, 'siswa_id');
    }

    public function iuranKejuaraan()
    {
        return $this->hasMany(IuranKejuaraan::class, 'siswa_id');
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'siswa_id');
    }

    public function catatanWaktu()
    {
        return $this->hasMany(CatatanWaktu::class, 'siswa_id');
    }

    public function personalBest()
    {
        return $this->hasMany(PersonalBest::class, 'siswa_id');
    }

    public function catatanLatihan()
    {
        return $this->hasMany(CatatanLatihan::class, 'siswa_id');
    }

    public function rapor()
    {
        return $this->hasMany(Rapor::class, 'siswa_id');
    }

    public function jersey()
    {
        return $this->hasOne(Jersey::class, 'siswa_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Accessors
    public function getFotoUrlAttribute()
    {
        return $this->foto ? Storage::url($this->foto) : null;
    }

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    public function getFormattedTanggalLahirAttribute()
    {
        return $this->tanggal_lahir ? formatTanggal($this->tanggal_lahir) : '-';
    }

    public function getFormattedTanggalDaftarAttribute()
    {
        return $this->tanggal_daftar ? formatTanggal($this->tanggal_daftar) : '-';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'aktif' => 'success',
            'cuti' => 'warning',
            'nonaktif' => 'danger',
            default => 'secondary'
        };
    }

    public function getJenisKelaminTextAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
}
