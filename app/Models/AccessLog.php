<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'os','browser', 'ip'];

    public function users()
    {
    	return $this->belongsTo('App\Models\User', 'user_id', 'userid');
    }
}
