<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prosem extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas', 'semester', 'mapel_id', 'mid', 'kd_id'
    ];

    public function mapels()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode_mapel');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas', 'kode_kelas');
    }
}
