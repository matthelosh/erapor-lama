<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'periode_id','semester','rombel_id','mapel_id','ppn','siswa_id','kd_id','jenis','aspek','nilai','teknik'
    ];

    public function siswas() {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    public function mapels()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode_mapel');
    }
}
