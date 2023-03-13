<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $fillable = ['kode_kelas','label','tingkat'];

    public function mapels()
    {
    	return $this->belongsToMany(Mapel::class, 'kelas_mapel')->withPivot('kkm');
    }

    public function rombels()
    {
    	return $this->hasMany(Rombel::class, 'kelas_id', 'id');
    }

    public function kds()
    {
        return $this->hasMany(Kd::class, 'kelas_id', 'kode_kelas');
    }

    public function temas()
    {
        return $this->hasMany(Tema::class, 'kelas_id', 'kode_kelas');
    }

    public function subtemas()
    {
        return $this->hasManyThrough(Subtema::class, Tema::class, 'kelas_id', 'tema_id', 'kode_kelas', 'kode_tema');
    }

    public function pemetaans()
    {
        return $this->hasMany(Pemetaan::class, 'kelas_id', 'koed_kelas');
    }

    public function prosems()
    {
        return $this->hasMany(Prosem::class, 'kelas', 'kode_kelas');
    }
}
