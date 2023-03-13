<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userid',
        'username',
        'email',
        'password',
        'level',
        'role',
        'userable_id',
        'userable_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userable()
    {
        return $this->morphTo();
    }
    public function guru()
    {
       return $this->belongsTo(Guru::class, 'user_id', 'nip');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'user_id', 'nisn');
    }

    // public function rombel()
    // {
    //     return $this->hasOne('App\Models\Rombel', 'guru_id','nip');
    // }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role', 'kode_role');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\AccessLog', 'user_id', 'userid');
    }
}
