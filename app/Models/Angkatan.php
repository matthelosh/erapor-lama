<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = 'kode';
    protected $fillable = ['kode', 'label', 'ket'];

    public function siswas()
    {
    	return $this->hasMany(Siswa::class, 'angkatan_id', 'kode');
    }
}
