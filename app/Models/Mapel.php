<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = ['kode_mapel', 'nama_mapel', 'label', 'deskripsi', 'kategori'];

    public function kds()
    {
    	return $this->hasMany('App\Models\Kd', 'mapel_id', 'kode_mapel');
    }

    public function kelas()
    {
    	return $this->belongsToMany(Kelas::class, 'kelas_mapel')->withPivot('kkm');
    }

    public function pemetaans()
    {
        return $this->hasMany(Pemetaan::class, 'mapel_id', 'koed_mapel');
    }

    public function prosems()
    {
        return $this->hasMany(Prosem::class, 'mapel_id', 'kode_mapel');
    }
}
