<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $fillable = ['periode_id', 'guru_id', 'rombel_id', 'tematik','hari', 'mapel_id', 'jamke','active'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id','nip');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode_rombel');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode_mapel');
    }
}
