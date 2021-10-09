<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = ['nik', 'nisn','nis','nama','jk','tempat_lahir','tanggal_lahir','agama','alamat','rt','rw','desa','kec','kab','kode_pos','hp','email','sekolah_asal','ortu_id', 'angkatan_id'];

    public function user()
    {
        return $this->morphMany('App\Models\User', 'userable');
    }

    public function rombel()
    {
        return $this->belongsToMany(Rombel::class, 'rombel_siswa')->withPivot('periode');   
    }

    public function angkatan()
    {
    	return $this->belongsTo(Angkatan::class, 'angkatan_id', 'kode');
    }

    public function nilais() 
    {
        return $this->hasMany(Nilai::class, 'siswa_id', 'nisn');
    }

    public function ekskuls()
    {
        return $this->hasMany(EkskulSiswa::class, 'siswa_id', 'nisn');
    }

    public function ortu()
    {
        return $this->belongsTo(Ortu::class);
    }
}
