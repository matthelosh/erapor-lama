<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;
    protected $fillable = ['kode_tema','teks', 'kelas_id'];

    public function subtemas()
    {
    	return $this->hasMany('App\Models\Subtema', 'tema_id', 'kode_tema');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'kode_kelas');
    }

    public function pemetaans()
    {
        return $this->hasManyThrough(Pemetaan::class, Subtema::class, 'tema_id', 'subtema_id', 'kode_tema', 'kode_subtema');
    }

    public function pembelajaran()
    {
        // return $this->hasMany(pembelajaran::class, 'tema_id', 'kode_tema');
        return $this->hasManyThrough(Pembelajaran::class, Subtema::class, 'tema_id', 'subtema_id', 'kode_tema', 'kode_subtema');
    }
}
