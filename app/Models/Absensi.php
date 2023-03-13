<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = ['periode_id', 'rombel_id','siswa_id', 'a','i','s'];

    public function siswas()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }
}
