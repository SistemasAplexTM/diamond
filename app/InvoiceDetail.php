<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDetail extends Model
{
  use SoftDeletes;
  
  public $table = "invoice_detail";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'invoice_id',
      'description',
      'quantity',
      'amount',
  ];
}
