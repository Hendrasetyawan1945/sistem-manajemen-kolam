<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'nama', 'tanggal_lahir', 'jenis_kelamin', 'alamat',
        'nama_ortu', 'telepon_ortu', 'email_ortu',
        'kelas_id', 'email', 'password',
        'status', 'catatan_admin',
        'siswa_id', 'diproses_oleh', 'diproses_pada',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'diproses_pada' => 'datetime',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'menunggu'  => 'warning',
            'disetujui' => 'success',
            'ditolak'   => 'danger',
            default     => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu'  => 'Menunggu Review',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => '-',
        };
    }
}
