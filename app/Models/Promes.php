<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promes extends Model
{
    use HasFactory;

    protected $fillable = ['kelas', 'semester', 'tematik', 'tema_id', 'subtema_id', 'mapel_id','kd_id','bulan','minggu'];

    public function tema()
    {
        return $this->belongsTo(Tema::class, 'tema_id', 'kode_tema');
    }

    public function subtema()
    {
        return $this->belongsTo(Subtema::class, 'subtema_id', 'kode_subtema');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode_mapel');
    }

    public function kd()
    {
        return $this->belongsTo(Kd::class, 'kd_id', 'kode_kd');
    }
}
