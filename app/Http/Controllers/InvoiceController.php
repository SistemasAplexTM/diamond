<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JavaScript;
use App\Moneda;
use App\Invoice;
use App\InvoiceDetail;

class InvoiceController extends Controller
{
  public function index()
  {
    JavaScript::put([
      'data_agencia' => $this->getNameAgencia(),
    ]);
    return view('templates.invoice.index');
  }

  public function store(Request $request)
  {
    try {
      $data = (new Invoice)->fill($request->data);
      if ($data->save()) {
        $answer = array(
          "datos"  => $data,
          "code"   => 200,
          "status" => 200,
        );
      } else {
        $answer = array(
          "error"  => 'Error al intentar Crear el registro.',
          "code"   => 600,
        );
      }
      return $answer;
    } catch (\Exception $e) {
      $answer = array(
        "error"  => $e,
        "code"   => 600,
      );
      return $answer;
    }
  }

  public function createDetail(Request $request){
    try {
      $data = (new InvoiceDetail)->fill($request->data);
      if ($data->save()) {
        $answer = array(
          "datos"  => $data,
          "code"   => 200,
          "status" => 200,
        );
      } else {
        $answer = array(
          "error"  => 'Error al intentar Crear el registro.',
          "code"   => 600,
        );
      }
      return $answer;
    } catch (\Exception $e) {
      $answer = array(
        "error"  => $e,
        "code"   => 600,
      );
      return $answer;
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $data = Invoice::findOrFail($id);
      $data->update($request->all());
      $answer = array(
        "datos"  => $request->all(),
        "code"   => 200,
        "status" => 500,
      );
      return $answer;
    } catch (\Exception $e) {
      $answer = array(
        "error"  => $e,
        "code"   => 600,
        "status" => 500,
      );
      return $answer;
    }
  }

  public function destroy($id)
  {
    $data = invoice::findOrFail($id);
    $data->delete();
  }

  public function getAll()
  {
    $sql = Invoice::with('detail')->orderBy('created_at', 'DESC')->get();
    return \DataTables::of($sql)->make(true);
  }

  public function getInvoiceById($id)
  {
    $invoice = Invoice::where('id', $id)->with('detail')->first();

    if ($invoice->client_table === 'master') {
      $client = DB::table('transportador AS a')
      ->select(['a.id','a.nombre as name',DB::raw("'master' as table_name")])
      ->where('a.id', $invoice->client_id)->first();
    }else{
      $client = DB::table($invoice->client_table . ' AS a')
      ->select(['a.id','a.nombre_full as name',DB::raw("'$invoice->client_table' as table_name")])
      ->where('a.id', $invoice->client_id)->first();
    }
    $data = array(
      'invoice' => $invoice,
      'client' => $client
    );
    return $data;
  }

  public function getDetail($id)
  {
    return InvoiceDetail::where('invoice_id', $id)->orderBy('created_at', 'DESC')->get();
  }

  public function getSelectClient($filter)
  {
    $shipper = DB::table('shipper AS a')
      ->select(['a.id','a.nombre_full as name',DB::raw("'shipper' as table_name")])
      ->where([
        ['a.nombre_full', 'LIKE', '%' . $filter . '%'],
        ['a.deleted_at', null],
      ]);
    $consignee = DB::table('consignee AS a')
      ->select(['a.id','a.nombre_full as name',DB::raw("'consignee' as table_name")])
      ->where([
        ['a.nombre_full', 'LIKE', '%' . $filter . '%'],
        ['a.deleted_at', null],
      ]);
    $data = DB::table('transportador AS a')
      ->select(['a.id','a.nombre as name',DB::raw("'master' as table_name")])
      ->union($shipper)
      ->union($consignee)
      ->where([
        ['a.nombre', 'LIKE', '%' . $filter . '%'],
        ['a.deleted_at', null],
      ])->get();
    $answer = array(
      'data' => $data
    );
    return \Response::json($answer);
  }

  public function getCurrency(){
    try {
      $data = Moneda::whereNull('deleted_at')->get();
      $answer = array(
        "data"  => $data,
        "code"   => 200,
      );
      return $answer;
    } catch (\Exception $e) {
      $answer = array(
        "error"  => $error,
        "code"   => 600,
      );
      return $answer;
    }
  }

  public function destroyDetail($id)
  {
    $data = invoiceDetail::findOrFail($id);
    $data->delete();
  }

}