<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    public $table = "localizacion";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'deptos_id', 'prefijo'
    ];

    public function state()
    {
        return $this->belongsTo('App\Departamento', 'deptos_id', 'id')->whereNull('deleted_at');
    }
}
