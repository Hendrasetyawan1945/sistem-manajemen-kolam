<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranRutin extends Model
{
    use HasFactory;

    protected $table = 'iuran_rutin';

    protected $fillable = [
        'siswa_id',
        'bulan',
        'tahun',
        'jumlah',
        'status_bayar',
        'tanggal_bayar',
        'metode_pembayaran_id',
        'catatan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'date',
        'bulan' => 'integer',
        'tahun' => 'integer',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Scopes
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByPeriode($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    public function scopeLunas($query)
    {
        return $query->where('status_bayar', 'lunas');
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status_bayar', 'belum');
    }

    // Accessors
    public function getFormattedJumlahAttribute()
    {
        return formatRupiah($this->jumlah);
    }

    public function getFormattedTanggalBayarAttribute()
    {
        return $this->tanggal_bayar ? formatTanggal($this->tanggal_bayar) : '-';
    }

    public function getPeriodeTextAttribute()
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulanNama[$this->bulan] . ' ' . $this->tahun;
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status_bayar) {
            'lunas' => 'success',
            'cicilan' => 'warning',
            'belum' => 'danger',
            default => 'secondary'
        };
    }
}
