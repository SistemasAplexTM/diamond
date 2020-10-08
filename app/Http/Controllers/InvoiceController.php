<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use JavaScript;
use App\Moneda;
use App\Invoice;
use App\InvoiceDetail;
use App\InvoiceReceiptPivot;

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

  public function saveRelationReceipt(Request $request)
  {
    try {
      $pivot = InvoiceReceiptPivot::where('document_id', $request->data['document_id'])->with('invoice')->first();
      if ($pivot) {
        $answer = array(
          "error"  => 'El recibo ya esta registrado en la factura #.' . $pivot->invoice->id,
          "data"  => $pivot,
          "code"   => 600,
        );
      } else {
        $data = (new InvoiceReceiptPivot)->fill($request->data);
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
      }
      return $answer;
    } catch (\Exception $e) {
      $answer = array(
        "error"  => $e,
        "dat"  => '',
        "code"   => 600,
      );
      return $answer;
    }
  }

  public function createDetail(Request $request)
  {
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
      $data->update($request->data);
      $answer = array(
        "datos"  => $data,
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
    $data   = invoice::findOrFail($id);
    $detail = $this->getDetail($id);
    $pivot  = $this->getRelationReceipt($id);
    foreach ($detail as $key => $value) {
      $this->destroyDetail($value->id);
    }
    foreach ($pivot as $key => $value) {
      $this->destroyRelationReceipt($value->id);
    }
    $data->delete();
  }

  public function getAll()
  {
    $sql = Invoice::with('detail', 'currency', 'agency')->orderBy('created_at', 'DESC')->get();
    foreach ($sql as $key => $value) {
      if ($value->client_table != '') {
        $sql[$key]->client_id = $this->getClientById($value->client_table, $value->client_id);
      } else {
        $sql[$key]->client_id = (object) ['name' => ''];
      }
    }
    return \DataTables::of($sql)->make(true);
  }

  public function getInvoiceById($id)
  {
    $invoice = Invoice::where('id', $id)->with('detail', 'currency', 'agency')->first();
    if ($invoice->client_table != '') {
      $client = $this->getClientById($invoice->client_table, $invoice->client_id);
    } else {
      $client = (object) ['id' => null, 'name' => null];
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

  public function getRelationReceipt($id)
  {
    return InvoiceReceiptPivot::where('invoice_id', $id)->with('document')->orderBy('created_at', 'DESC')->get();
  }

  public function getClientById($table, $id)
  {
    $client = null;
    if ($table === 'master') {
      $client = DB::table('transportador AS a')
        ->select(['a.id', 'a.nombre as name', 'a.email', 'a.information', DB::raw("'master' as table_name")])
        ->where('a.id', $id)->first();
    } else {
      $client = DB::table($table . ' AS a')
        ->select(['a.id', 'a.nombre_full as name', 'a.direccion', 'a.telefono', 'a.correo', DB::raw("'$table' as table_name")])
        ->where('a.id', $id)->first();
    }
    return $client;
  }

  public function getSelectClient($filter)
  {
    $shipper = DB::table('shipper AS a')
      ->select(['a.id', 'a.nombre_full as name', DB::raw("'shipper' as table_name")])
      ->where([
        ['a.nombre_full', 'LIKE', '%' . $filter . '%'],
        ['a.deleted_at', null],
      ]);
    $consignee = DB::table('consignee AS a')
      ->select(['a.id', 'a.nombre_full as name', DB::raw("'consignee' as table_name")])
      ->where([
        ['a.nombre_full', 'LIKE', '%' . $filter . '%'],
        ['a.deleted_at', null],
      ]);
    $data = DB::table('transportador AS a')
      ->select(['a.id', 'a.nombre as name', DB::raw("'master' as table_name")])
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

  public function getCurrency()
  {
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

  public function destroyRelationReceipt($id)
  {
    $data = InvoiceReceiptPivot::findOrFail($id);
    $data->delete();
  }

  public function pdf($id)
  {
    $data = $this->getInvoiceById($id);
    // echo '<pre>';
    // print_r($data['invoice']->agency);
    // echo '</pre>';
    // exit();
    $pdf  = PDF::loadView('pdf.invoice.invoice', compact('data'));
    $pdf->save(public_path() . '/files/invoice.pdf'); //GUARDAR PARA IMPRIMIR POR DEFECTO
    $dom_pdf = $pdf->getDomPDF();

    $canvas = $dom_pdf->get_canvas();
    $canvas->page_text(522, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
    return $pdf->stream('Invoice #' . $id . '.pdf'); //visualizar en el navegador
  }
}
