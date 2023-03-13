<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kd extends Model
{
    use HasFactory;
    protected $fillable = ['kode_kd','teks', 'mapel_id', 'kelas_id', 'agama', 'aspek'];

    public function mapels()
    {
    	return $this->belongsTo('App\Models\Mapel', 'mapel_id', 'kode_mapel' );
    }

    public function kelas()
    {
    	return $this->belongsTo(Kelas::class, 'kelas_id', 'kode_kelas');
    }

    public function pemetaans()
    {
        return $this->hasMany(Pemetaan::class, 'kd_id', 'kode_kd');
    }
}
