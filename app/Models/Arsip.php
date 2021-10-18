<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $fillable = ['kode_rapor', 'periode_id', 'semester', 'rombel_id', 'siswa_id', 'jenis'];

    public function siswas()
    {
        return $this->belongsTo('App\Models\Siswa', 'siswa_id', 'nisn');
    }

    public function periodes()
    {
        return $this->belongsTo('App\Models\Periode', 'periode_id', 'kode_periode');
    }

    public function rombels()
    {
        return $this->belongsTo('App\Models\Rombel', 'rombel_id', 'kode_rombel');
    }
}
