<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuRol extends Model
{
    protected $table = "aplex_menu_rol";
    public $timestamps = false;

    protected $fillable = ['menu_id', 'rol_id', 'user_id'];

    public function roles()
    {
      return $this->hasMany('App\Rol', 'id')->select('id', 'name', 'slug', 'description', 'special', 'is_agency')
      ;
    }
}
