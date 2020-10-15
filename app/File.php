<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $table = "_files";
    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name_file', 
        'name_old', 
        'extension',
        'size',
        'module',
        'module_id',
        'prealerta_email',
    ];
}
