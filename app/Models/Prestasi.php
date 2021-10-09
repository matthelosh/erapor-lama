<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $fillable = ['periode_id', 'rombel_id', 'siswa_id', 'akademik', 'olahraga', 'kesenian'];


    public function siswas()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nian');
    }
}
