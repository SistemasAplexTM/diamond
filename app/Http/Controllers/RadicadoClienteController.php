<?php

namespace App\Http\Controllers;

use DataTables;
use App\RadicadoCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RadicadoClienteController extends Controller
{
  public function index()
  {
      return view('templates/radicadoCliente');
  }

  public function store(Request $request)
  {
      try {
          $data             = (new RadicadoCliente)->fill($request->all());
          if ($data->save()) {
              $answer = array(
                  "datos"  => $request->all(),
                  "code"   => 200,
                  "status" => 200,
              );
          } else {
              $answer = array(
                  "error"  => 'Error al intentar Eliminar el registro.',
                  "code"   => 600,
                  "status" => 500,
              );
          }
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

  public function update(Request $request, $id)
  {
      try {
          $data = RadicadoCliente::findOrFail($id);
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
      $data = RadicadoCliente::findOrFail($id);
      $data->delete();
  }

  public function delete($id, $logical)
  {

      if (isset($logical) and $logical == 'true') {
          $data             = RadicadoCliente::findOrFail($id);
          $now              = new \DateTime();
          $data->deleted_at = $now->format('Y-m-d H:i:s');
          if ($data->save()) {
              $answer = array(
                  "datos" => 'Eliminación exitosa.',
                  "code"  => 200,
              );
          } else {
              $answer = array(
                  "error" => 'Error al intentar Eliminar el registro.',
                  "code"  => 600,
              );
          }

          return $answer;
      } else {
          $this->destroy($id);
      }
  }

  public function restaurar($id)
  {
      $data             = RadicadoCliente::findOrFail($id);
      $data->deleted_at = null;
      $data->save();
  }

  public function getAll()
  {
      return \DataTables::of(RadicadoCliente::query()->where([
        ['deleted_at', null],
        ]))->make(true);
  }
}
