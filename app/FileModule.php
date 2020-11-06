<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileModule extends Model
{
    public $table = "_files_module";
    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'file_id',
        'module_id',
        'module_record_id',
        'agency_id',
    ];
}
