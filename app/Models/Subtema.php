<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtema extends Model
{
    use HasFactory;

    protected $fillable = ['kode_subtema', 'teks', 'tema_id'];

    public function temas()
    {
    	return $this->belongsTo('App\Models\Tema');
    }

    public function pemetaans()
    {
        return $this->hasMany(Pemetaan::class, 'subtema_id', 'kode_subtema');
    }

    public function pembelajaran()
    {
        return $this->hasMany(Pembelajaran::class, 'subtema_id', 'kode_subtema');
    }
}
