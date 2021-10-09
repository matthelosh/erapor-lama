<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Rombel extends Model
{
    use HasFactory;
    protected $fillable = ['kode_rombel','label','kelas_id','guru_id','periode_id'];

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'rombel_siswa', 'rombel_id', 'siswa_id')->withPivot('periode');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'kode_kelas');
    }

    public function walis()
    {
        return $this->belongsTo(Guru::class, 'guru_id','nip');
    }
    // public function walis()
    //     {
    //         return $this->belongsTo(User::class, 'guru_id','nip');
    //     }

    public function periodes()

    {
        return $this->belongsTo(Periode::class, 'periode_id', 'kode_periode');
    }
}
