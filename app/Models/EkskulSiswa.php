<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EkskulSiswa extends Model
{
    use HasFactory;
    protected $fillable = [
        'periode_id', 'rombel_id', 'ekskul_id', 'siswa_id', 'ket'
    ];

    public function ekskuls()
    {
        return $this->belongsTo(Ekskul::class, 'ekskul_id', 'kode_ekskul');
    }

    public function siswas()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }
}
