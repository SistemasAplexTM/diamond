<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceReceiptPivot extends Model
{
  
  public $table = "invoice_receipt_pivot";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'invoice_id',
    'document_id',
  ];

  public function document()
  {
    return $this->belongsTo('App\DocumentoDetalle', 'document_id', 'id')->whereNull('deleted_at');
  }

  public function invoice()
  {
    return $this->belongsTo('App\Invoice', 'invoice_id', 'id')->whereNull('deleted_at');
  }
}