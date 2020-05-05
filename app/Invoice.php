<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
  use SoftDeletes;
  
  public $table = "invoice";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'agency_id',
      'consecutive',
      'date_document',
      'client_table',
      'client_id',
      'currency',
      'observation'
  ];

  public function detail()
    {
        return $this->hasMany('App\InvoiceDetail', 'invoice_id', 'id')->whereNull('deleted_at');
    }
}
