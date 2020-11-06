<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@lang('general.pre_alert') | 4plbox</title>

    <!-- Styles -->
    <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/main.css') }}" rel="stylesheet"> --}}
    <style>
        body{
            background-color: #ffffff;
        }
        #formulario{
            float: none;
            margin:0px auto!important;
        }
        .help-block{
            color: red;
        }
        .el-upload__input{
          display: none !important;
        }
        .upload-file{
            height: 100px;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div>
            <div class="wrapper wrapper-content animated fadeInRight" id="prealerta">
                <div class="row" id="contenido">
                    <div class="col-lg-4 col-md-6" id="formulario">
                        <h1>@lang('general.preview_your_shipment')</h1>
                        <p>@lang('general.enter_the_tracking_and_instruction')</p>
                        <p>Prealertar sus paquetes agilizará el proceso de envío.</p>
                        <form id="formPrealerta" enctype="multipart/form-data" data-id_agencia="{{ $id_age }}" class="form-horizontal casillero_form" role="form" action="#" method="post">
                        <input type="hidden" id="agency_id" value="{{ $id_age }}">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content gray-bg">
                                    <!--***** contenido ******-->
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group" :class="{ 'has-error': errors.has('email') }">
                                                    <label class="control-label" for="email">@lang('general.email')</label>
                                                    <input type="email" placeholder="example@example.com" class="form-control" id="email" name="email" v-model="email" v-validate.disable="'email|required'">
                                                    <small class="help-block">@{{ errors.first('email') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group" :class="{ 'has-error': errors.has('tracking') }">
                                                    <label class="control-label" for="email">@lang('general.tracking') </label>
                                                    <input type="text" name="tracking" class="form-control" placeholder="@lang('general.enter_a_tracking_number')" v-model="tracking" v-validate.disable="'required|unique'">
                                                    <small class="help-block">@{{ errors.first('tracking') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                    <label class="control-label" for="email">@lang('general.content') </label>
                                                    <input type="text" name="contenido" class="form-control" placeholder="@lang('general.content')" v-model="contenido">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                    <label class="control-label" for="email">@lang('general.declared') </label>
                                                    <input type="number" min="0" name="declarado" class="form-control" placeholder="$ 00.0" v-model="declarado">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="control-label" for="email" style="width: 100%">&nbsp;</label>
                                                <input type='checkbox' data-toggle="toggle" id='despachar' data-size='mini' data-on="Despachar" data-off="Esperar" data-width="100" data-style="ios" data-onstyle="primary" data-offstyle="warning">
                                                <span id="msn1" v-if="despachar">@lang('general.dispatch_immediately')</span>
                                                <span id="msn2" v-if="!despachar">@lang('general.wait_until_you_decide')</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group" :class="{ 'has-error': errors.has('instruccion') }">
                                                    <label class="control-label" for="instruccion">@lang('general.instruction') </label>
                                                    <input type="text" placeholder="@lang('general.enter_the_instruction')" class="form-control" name="instruccion" v-model="instruccion">
                                                    <small class="help-block">@{{ errors.first('instruccion') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12" style="text-align: center;">
                                          <div class="form-group" :class="{ 'has-error': errors.has('instruccion') }">
                                            <label class="control-label" for="instruccion">Cargar Factura </label>
                                            <el-upload
                                                ref="upload"
                                                class="upload-file"
                                                action="{{ '/prealerta/' . $id_age . '/uploadFile' }}"
                                                :http-request="uploadFiles"
                                                :limit="1"
                                                :auto-upload="false"
                                                :file-list="fileList"
                                                :on-exceed="handleExceed"
                                                :headers="headersUpload"
                                                accept=".pdf">
                                                <el-button size="small" type="primary" icon="el-icon-upload">Clic para subir archivo</el-button>
                                                <div slot="tip" class="el-upload__tip">Solo archivos con un tamaño menor de 1 MB</div>
                                            </el-upload>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12" style="text-align: center;">
                                            <div class="form-group">
                                              <button @click.prevent="create" type="button" class="ladda-button btn btn-primary hvr-float-shadow" style="width: 50%;" data-style="expand-right" title=""><span class="ladda-label"> Prealertar <i class="fal fa-paper-plane" aria-hidden="true"></i></span><span class="ladda-spinner"></span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/plantilla.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/templates/prealerta.js') }}"></script>
</body>
</html>
