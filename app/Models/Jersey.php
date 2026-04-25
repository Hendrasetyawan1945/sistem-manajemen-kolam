<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jersey extends Model
{
    use HasFactory;

    protected $table = 'jersey';

    protected $fillable = [
        'siswa_id',
        'master_ukuran_jersey_id',
        'status',
        'tanggal_pesan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pesan' => 'date',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function masterUkuranJersey()
    {
        return $this->belongsTo(MasterUkuranJersey::class, 'master_ukuran_jersey_id');
    }

    // Scopes
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
