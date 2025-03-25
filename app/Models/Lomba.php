<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lomba extends Model
{
    //
    use HasFactory;

    protected $table = 'lomba';

    protected $fillable = [
        'nama_lomba',
        'deskripsi',
        'harga',
        'jenis_pengumpulan',
        'logo_lomba',
        'jenis_lomba',
        'kategori_lomba',
        'no_pj',
    ];

    // Relasi ke Pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function daftarKriteria()
    {
        return $this->hasMany(DaftarKriteria::class, 'lomba_id');
    }
}
