<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterUkuranJersey extends Model
{
    use HasFactory;

    protected $table = 'master_ukuran_jersey';

    protected $fillable = [
        'ukuran',
        'keterangan',
    ];

    // Relationships
    public function jersey()
    {
        return $this->hasMany(Jersey::class, 'master_ukuran_jersey_id');
    }

    public function jerseys()
    {
        return $this->hasMany(Jersey::class, 'master_ukuran_jersey_id');
    }
}
