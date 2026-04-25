<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    use HasFactory;

    protected $table = 'rapor';

    protected $fillable = [
        'siswa_id',
        'coach_id',
        'periode',
        'teknik_renang',
        'kondisi_fisik',
        'kedisiplinan',
        'semangat_berlatih',
        'catatan_coach',
        'status',
    ];

    protected $casts = [
        'teknik_renang' => 'integer',
        'kondisi_fisik' => 'integer',
        'kedisiplinan' => 'integer',
        'semangat_berlatih' => 'integer',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
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

    public function scopeByCoach($query, $coachId)
    {
        return $query->where('coach_id', $coachId);
    }

    public function scopeByPeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    public function scopeFinal($query)
    {
        return $query->where('status', 'final');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Accessors
    public function getRataRataAttribute()
    {
        return ($this->teknik_renang + $this->kondisi_fisik + $this->kedisiplinan + $this->semangat_berlatih) / 4;
    }

    public function getGradeAttribute()
    {
        $rata = $this->rata_rata;
        if ($rata >= 9) return 'A';
        if ($rata >= 8) return 'B+';
        if ($rata >= 7) return 'B';
        if ($rata >= 6) return 'C+';
        if ($rata >= 5) return 'C';
        return 'D';
    }

    public function getFormattedPeriodeAttribute()
    {
        return $this->periode;
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status === 'final' ? 'success' : 'warning';
    }
}
