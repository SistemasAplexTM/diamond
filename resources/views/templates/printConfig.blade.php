@extends('layouts.app')
@section('title','Agencia')
@section('breadcrumb')
{{-- bread crumbs --}}
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>@lang('layouts.print_config')</h2>
    <ol class="breadcrumb">
      <li>
        <a href="#">@lang('general.home')</a>
      </li>
      <li class="active">
        <strong>@lang('layouts.print_config')</strong>
        <!--No se puede traducir-->
      </li>
    </ol>
  </div>
</div>
<style>
  .el-form-item__label {
    line-height: 10px;
  }
</style>
@endsection

@section('content')
<div class="row" id="printConfig">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>@lang('layouts.print_config')</h5>
      </div>
      <div class="ibox-content">
        <div class="row">
          <div class="col-lg-4">
            <div class="col-lg-12">
              <el-form label-position="top" label-width="100px">
                <el-form-item label="Impresora por defecto:">
                  <el-select v-model="installedPrinter1" clearable placeholder="Seleccione impresora por defecto"
                    size="medium" value-key="id">
                    <el-option v-for="item in prints" :key="item.id" :label="item.name" :value="item">
                    </el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="Impresora para labels:">
                  <el-select v-model="installedPrinter2" clearable placeholder="Seleccione impresora para labels"
                    size="medium" value-key="id">
                    <el-option v-for="item in prints" :key="item.id" :label="item.name" :value="item">
                    </el-option>
                  </el-select>
                </el-form-item>
                <el-form-item>
                  <el-button type="success" size="medium" @click="savePrint()" :loading="loading"><i
                      class="fal fa-save"></i>
                    @lang('general.save')
                  </el-button>
                </el-form-item>
              </el-form>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="table-responsive">
              <table id="tbl-prints" class="table table-striped table-hover" style="width: 100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Impresora Labels</th>
                    <th>Impresora Por Defecto</th>
                    <th>@lang('general.actions')</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in printers">
                    <td>@{{ index + 1 }}</td>
                    <td>@{{ item.label }}</td>
                    <td>@{{ item.default }}</td>
                    <td><a @click="deletePrint(index)" class="delete_btn" data-toggle="tooltip" data-placement="top"
                        title="" data-original-title="Eliminar"><i class="fal fa-trash-alt fa-lg"></i></a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/plugins/zip.js-master/scripts/JSPrintManager.js') }}"></script>
<script src="{{ asset('js/plugins/zip.js-master/scripts/zip.js') }}"></script>
<script src="{{ asset('js/plugins/zip.js-master/scripts/zip-ext.js') }}"></script>
<script src="{{ asset('js/plugins/zip.js-master/scripts/deflate.js') }}"></script>
<script src="{{ asset('js/templates/printConfig.js') }}"></script>
@endsection