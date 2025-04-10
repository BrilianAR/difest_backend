<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karya extends Model
{
    //
    use HasFactory;

    protected $table = 'hasil_karya';

    protected $fillable = [
        'pendaftaran_id',
        'judul_karya',
        'deskripsi',
        'karya',
        'link_karya',
        'status_karya',
        'keaslian_karya',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
