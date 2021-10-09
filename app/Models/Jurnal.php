<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    protected $fillable = [
        'pembelajaran_id', 
        'kegiatan', 
        'keterangan', 
        'media', 
        'metode', 
        'tagihan',
        'materi',
        'tanggal'
    ];

    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id', 'kode_pembelajaran');
    }
}
