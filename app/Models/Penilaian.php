<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nilai',
        'karya_id',
        'daftar_kriteria_id',
        'juri_id'
    ];

    /**
     * Relasi ke tabel hasil_karya
     */
    public function karya()
    {
        return $this->belongsTo(Karya::class, 'karya_id');
    }

    /**
     * Relasi ke tabel daftar_kriterias
     */
    public function daftarKriteria()
    {
        return $this->belongsTo(DaftarKriteria::class, 'daftar_kriteria_id');
    }

    /**
     * Relasi ke tabel juris
     */
    public function juri()
    {
        return $this->belongsTo(Juri::class, 'juri_id');
    }
}
