<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarKriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'kriteria_id',
        'lomba_id',
        'jenis_kriteria',
    ];

    /**
     * Relasi ke tabel Kriteria
     */
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    /**
     * Relasi ke tabel Lomba
     */
    public function lomba()
    {
        return $this->belongsTo(Lomba::class, 'lomba_id');
    }
}
