<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ortu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik_ayah','nama_ayah', 'job_ayah','status_ayah', 'nama_ibu', 'job_ibu', 'status_ibu', 'nama_wali', 'alamat_wali', 'job_wali'
    ];

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'ortu_id', 'id');
    }
}
