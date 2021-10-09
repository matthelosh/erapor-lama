<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggalRapor extends Model
{
    use HasFactory;

    protected $fillable = ['periode_id', 'tanggal', 'jenis_rapor'];

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id', 'kode_periode');
    }
}
