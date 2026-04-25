<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'telepon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function kelasAsCoach()
    {
        return $this->hasMany(Kelas::class, 'coach_id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    public function sesiAsCoach()
    {
        return $this->hasMany(Sesi::class, 'coach_id');
    }

    public function catatanLatihan()
    {
        return $this->hasMany(CatatanLatihan::class, 'coach_id');
    }

    public function raporAsCoach()
    {
        return $this->hasMany(Rapor::class, 'coach_id');
    }

    // Missing relationships - tambahan
    public function iuranRutinDibuat()
    {
        return $this->hasMany(IuranRutin::class, 'dibuat_oleh');
    }

    public function iuranInsidentilDibuat()
    {
        return $this->hasMany(IuranInsidentil::class, 'dibuat_oleh');
    }

    public function iuranKejuaraanDibuat()
    {
        return $this->hasMany(IuranKejuaraan::class, 'dibuat_oleh');
    }

    public function angsuranDibuat()
    {
        return $this->hasMany(Angsuran::class, 'dibuat_oleh');
    }

    public function pengeluaranDibuat()
    {
        return $this->hasMany(Pengeluaran::class, 'dibuat_oleh');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
