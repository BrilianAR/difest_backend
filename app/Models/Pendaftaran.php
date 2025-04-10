<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    //
    use HasFactory;
    protected $table = 'pendaftarans';

    protected $fillable = [
        'user_id',
        'lomba_id',
        'nama_ketua',
        'email',
        'no_hp',
        'asal_institusi',
        'kartu_identitas_ketua',
        'nama_team',
        'bukti_pembayaran',
        'bukti_follow_ig_difest',
        'bukti_follow_ig_himatikom',
        'bukti_follow_tiktok_difest',
        'bukti_subscribe_youtube_himatikom'
    ];

    // Tambahkan anggota secara dinamis
    public static function getAnggotaFields()
    {
        $fields = [];
        for ($i = 1; $i <= 4; $i++) {
            $fields[] = "nama_anggota_$i";
            $fields[] = "asal_institusi_anggota_$i";
            $fields[] = "kartu_identitas_anggota_$i";
        }
        return $fields;
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Lomba
    public function lomba()
    {
        return $this->belongsTo(Lomba::class);
    }

    // // Relasi ke HasilKarya
    public function hasilKarya()
    {
        return $this->hasOne(Karya::class);
    }
}
