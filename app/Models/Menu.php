<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'url', 'icon', 'label','role'];

    public function children()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role');
    }
}
