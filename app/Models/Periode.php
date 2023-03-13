<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;
    protected $fillable = ['kode_periode','tapel','semester','deskripsi', 'active'];

    public function rombels()
    {
        return $this->hasMany(Rombel::class, 'periode_id', 'kode_periode');
    }

    public function tanggal_rapor()
    {
        return $this->hasMany(TanggalRapor::class, 'periode_id', 'kode_periode');
    }
}
//