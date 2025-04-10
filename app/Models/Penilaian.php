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
        return $this->belongsTo(Karya::class);
    }

    /**
     * Relasi ke tabel daftar_kriterias
     */
    public function daftarKriteria()
    {
        return $this->belongsTo(DaftarKriteria::class);
    }

    /**
     * Relasi ke tabel users sebagai juri
     */
    public function juri()
    {
        return $this->belongsTo(User::class, 'juri_id');
    }
}