<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'lomba_id'
    ];

    /**
     * Relasi ke tabel Lomba
     */
    public function lomba()
    {
        return $this->belongsTo(Lomba::class, 'lomba_id');
    }
}
