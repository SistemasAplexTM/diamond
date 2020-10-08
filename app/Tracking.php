<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    public $table = "tracking";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'documento_detalle_id',
        'agencia_id',
        'consignee_id',
        'codigo',
        'contenido',
        'peso_tracking',
        'confirmed_send'
    ];
}
