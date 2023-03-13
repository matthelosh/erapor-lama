<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saran extends Model
{
    use HasFactory;

    protected $fillable = ['periode_id', 'rombel_id', 'siswa_id', 'teks', 'jenis_rapor'];

    public function siswas()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    
}
