<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = 'angsuran';

    protected $fillable = [
        'siswa_id',
        'keterangan',
        'total_tagihan',
        'total_dibayar',
        'sisa',
        'status',
        'dibuat_oleh',
    ];

    protected $casts = [
        'total_tagihan' => 'decimal:2',
        'total_dibayar' => 'decimal:2',
        'sisa' => 'decimal:2',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function detailAngsuran()
    {
        return $this->hasMany(DetailAngsuran::class, 'angsuran_id');
    }

    // Scopes
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    // Methods
    public function updateSisa()
    {
        $this->sisa = $this->total_tagihan - $this->total_dibayar;
        $this->save();

        // Auto update status jika lunas
        if ($this->sisa <= 0) {
            $this->status = 'lunas';
            $this->save();
        }
    }

    public function addPayment($jumlah, $metodePembayaranId, $catatan = null, $dibuatOleh = null)
    {
        // Create detail angsuran
        $detail = DetailAngsuran::create([
            'angsuran_id' => $this->id,
            'jumlah_bayar' => $jumlah,
            'tanggal_bayar' => now()->toDateString(),
            'metode_pembayaran_id' => $metodePembayaranId,
            'catatan' => $catatan,
            'dibuat_oleh' => $dibuatOleh ?? auth()->id(),
        ]);

        // Update total dibayar
        $this->total_dibayar += $jumlah;
        $this->updateSisa();

        return $detail;
    }
}
