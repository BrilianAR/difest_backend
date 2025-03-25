<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    /**
     * Relasi ke daftar_kriteria
     */
    public function daftarKriteria()
    {
        return $this->hasMany(DaftarKriteria::class, 'kriteria_id');
    }
}
