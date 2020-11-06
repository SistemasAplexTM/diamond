<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $table = "aplex_menu";
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'route', 'parent', 'order_item', 'meta', 'module_id', 'type'
    ];

    public function roles()
    {
      return $this->hasMany('App\MenuRol', 'menu_id')
      ->join('roles', 'roles.id', 'aplex_menu_rol.rol_id')
      ->select(
        'aplex_menu_rol.rol_id AS id', 
        'aplex_menu_rol.menu_id', 
        'roles.name', 
        'roles.slug',
        'roles.description'
      );
    }

    public function modules()
    {
      return $this->belongsTo('App\Modulo', 'module_id')->select('id', 'nombre');
    }

}
