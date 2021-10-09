<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;

    protected $fillable = ['kode_ekskul', 'label'];

    public function ekskul_siswa()
    {
        return $this->hasMany(EkskulSiswa::class, 'ekskul_id', 'kode_ekskul');
    }
}
