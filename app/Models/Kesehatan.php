<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kesehatan extends Model
{
    use HasFactory;
    protected $fillable = ['periode_id','rombel_id','siswa_id', 'pendengaran', 'penglihatan', 'gigi', 'lainnya'];

    public function siswas()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id','nisn');
    }
}
