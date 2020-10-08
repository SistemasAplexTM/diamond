<!DOCTYPE html>
<html>

<head>
  <title>{{ $documento->num_master . ' Guias Hijas' }}</title>
</head>

<body>
  <style>
    * {
      font-size: 12px;
      font-family: sans-serif;
      color: #1d68a4;
    }

    #table_content {
      width: 100%;
      /*margin-top: -10px;*/
    }

    #spaceTable {
      border-bottom: dashed 1px #000;
      margin-top: 30px;
      margin-bottom: 30px;
    }

    .agencia {
      text-align: center;
    }

    .guia {
      text-align: center;
      font-size: 20px;
    }

    #shipper,
    #consignee {
      margin-left: 10px;
      color: #1d1d1e;
    }
  </style>
  <?php
$cont = 0;
$contRegistros = 0;
$toalRegistros = count($detalleConsolidado);
?>

  @if($detalleConsolidado != '')
  @foreach ($detalleConsolidado as $value)
  <?php
        $cont++;
        $contRegistros++;
        ?>
  <table border="0" id="table_content" cellspacing="0" cellpadding="0" <?php if ($cont === 2): ?>
    style="page-break-after:<?php if ($contRegistros === $toalRegistros): ?>avoid;margin-bottom: 0px;<?php else: ?>always<?php endif; ?>" <?php
                   $cont = 0;
               endif;
               ?>>
    <thead>
      <tr>
        <th colspan="2" width="200px">
          @if(env('APP_DEPELOPER'))
          <img alt="image" class="img-circle" id="logo" style="height: 50px;margin-bottom: 10px;"
            src="{{ public_path() . '/storage/' }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? $documento->agencia_logo : 'logo.png') }}" />
          @else
          <img alt="image" class="img-circle" id="logo" style="height: 50px;margin-bottom: 10px;"
            src="{{ asset('storage/') }}/{{ ((isset($documento->agencia_logo) and $documento->agencia_logo != '') ? $documento->agencia_logo : 'logo.png') }}" />
          @endif
        </th>
        <th width="350px" style="text-align: right;">
          <div class="agencia" id="nomAgencia" style="font-size: 20px;">{{ $documento->agencia }}</div>
          <div class="agencia" id="dirAgencia"><span style="color: #1d1d1e;">{{ $documento->agencia_dir }} -
              {{ $documento->agencia_ciudad }} - {{ $documento->agencia_depto }}</span></div>
          <div class="agencia" id="telAgencia">@lang('general.phone'): <span
              style="color: #1d1d1e;">{{ $documento->agencia_tel }}</span></div>
          <div class="agencia" id="telAgencia">Zip: <span style="color: #1d1d1e;">{{ $documento->agencia_zip }}</span>
          </div>
        </th>
        <th>
          <div class="guia">@lang('general.guide') AWB</div>
          <div class="guia" style="color: #1d1d1e;">{{ $value->num_guia }}</div>
          <div class="" style="color: #1d1d1e;text-align: center;margin-top: 10px;font-size: 12px;">Fecha:
            {{-- {{ substr($documento->created_at, 0, 10) }} --}}
            {{ date('Y-m-d', time()) }}
          </div>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td colspan="4">
          <div style="margin-top: 10px;">
            <table border="0" id="" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <th colspan="4"
                  style="text-align:center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">
                  @lang('general.from_shipper') </th>
                <th colspan="4" style="text-align:center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">
                  @lang('general.to_consigned') </th>
              </tr>
              <tr>
                <td colspan="4" style="width: 50%;border-right: 1px solid #ccc;height: 100px;">
                  <table style="width: 100%;">
                    <tr>
                      <td colspan="2">
                        <div id="shipper" style="font-weight: bold;font-size: 13px;">
                          {{-- {{ ($value->ship_nomfull2) ? $value->ship_nomfull2 : ((isset($value->ship_nomfull)) ? $value->ship_nomfull : '&nbsp;') }}
                          --}}
                          <pre style="font-weight: bold;font-size: 13px;color:black;">{{ $value->shipper_data }}</pre>
                        </div>
                      </td>
                    </tr>
                    {{-- <tr>
                                        <td colspan="2">
                                            <div style="margin-left: 10px;"> @lang('general.address')</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div id="shipper">
                                                {{ ($value->ship_dir2) ? $value->ship_dir2 : ((isset($value->ship_dir)) ? $value->ship_dir : '&nbsp;') }}
          </div>
        </td>
      </tr>
      <tr>
        <td width="40%">
          <div style="margin-left: 10px;">@lang('general.phone')</div>
        </td>
        <td>
          <div style="margin-left: 10px;">@lang('general.city') -
            @lang('general.state')</div>
        </td>
      </tr>
      <tr>
        <td>
          <div id="shipper">
            {{ ($value->ship_tel2) ? $value->ship_tel2 : ((isset($value->ship_tel) and $value->ship_tel != '') ? $value->ship_tel : '&nbsp;') }}
          </div>
        </td>
        <td>
          <div id="shipper">
            {{ ($value->ship_ciudad2) ? $value->ship_ciudad2 : ((isset($value->ship_ciudad)) ? $value->ship_ciudad : '&nbsp;') }}
            , {{ ($value->ship_zip2) ? $value->ship_zip2 : $value->ship_zip }}</div>
        </td>
      </tr> --}}
  </table>
  </td>
  <td colspan="4">
    <table style="width: 100%;">
      <tr>
        <td colspan="2">
          <div id="consignee" style="font-weight: bold;font-size: 13px;">
            {{-- {{ ($value->cons_nomfull2) ? $value->cons_nomfull2 : ((isset($value->cons_nomfull)) ? $value->cons_nomfull : '&nbsp;') }}
            --}}
            <pre style="font-weight: bold;font-size: 13px;color:black;">{{ $value->consignee_data }}</pre>
          </div>
        </td>
      </tr>
      {{-- <tr>
            <td colspan="2">
                <div style="margin-left: 10px;">@lang('general.address')</div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="consignee">
                    {{ ($value->cons_dir2) ? $value->cons_dir2 : ((isset($value->cons_dir)) ? $value->cons_dir : '&nbsp;') }}
      </div>
  </td>
  </tr>
  <tr>
    <td width="40%">
      <div style="margin-left: 10px;">@lang('general.phone')</div>
    </td>
    <td>
      <div style="margin-left: 10px;">@lang('general.city') -
        @lang('general.state')</div>
    </td>
  </tr>
  <tr>
    <td>
      <div id="consignee">
        {{ ($value->cons_tel2) ? $value->cons_tel2 : ((isset($value->cons_tel) and $value->cons_tel != '') ? $value->cons_tel : '&nbsp;') }}
      </div>
    </td>
    <td>
      <div id="consignee">
        {{ ($value->cons_ciudad2) ? $value->cons_ciudad2 : ((isset($value->cons_ciudad)) ? $value->cons_ciudad : '&nbsp;') }},
        {{ ($value->cons_zip2) ? $value->cons_zip2 : $value->cons_zip }}</div>
    </td>
  </tr> --}}
  </table>
  </td>
  </tr>
  <tr>
    <td colspan="4"
      style="text-align:center;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;">
      @lang('general.description') - @lang('general.content') - PA: {{ $value->pa }}</td>
    <td colspan="4" style="border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">
      <table style="width: 100%">
        <tr>
          <td width="35%" style="text-align: center;">@lang('general.declared')</td>
          <td width="30%" style="text-align: center;">@lang('general.pieces')</td>
          <td width="35%" style="text-align: center;">@lang('general.weight')</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="4"
      style="margin-left: 10px;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc;color: #1d1d1e;">
      {{ $value->contenido2 }}</td>
    <td colspan="4" style="border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;">
      <table style="width: 100%;">
        <tr>
          <td width="35%" style="text-align: center;">
            <div style="color: #1d1d1e;">{{ '$ '.number_format($value->declarado2, 2) }}
            </div>
            <div style="margin-top: 10px;">Master: <span style="color: #1d1d1e;">{{ $documento->num_master }}</span>
            </div>
          </td>
          <td width="30%" style="color: #1d1d1e;text-align: center;">{{ $value->piezas }}
          </td>
          <td width="35%" style="text-align: center;">
            <div style="color: #1d1d1e;">{{ $value->peso2 }} Lbs</div>
            <div style="margin-top: 10px;color: #1d1d1e;">
              {{ number_format(($value->peso2 * 0.453592), 2) }} Kls</div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="font-size: 9px;">WR</td>
    <td>&nbsp;</td>
    <td style="border-right: 1px solid #ccc;">&nbsp;</td>

    <td colspan="2"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="font-size: 9px;color: #1d1d1e;">{{ $value->num_warehouse }}</td>
    <td>@lang('general.date')</td>
    <td style="border-right: 1px solid #ccc;">@lang('general.time')</td>

    <td colspan="2"></td>
    <td>@lang('general.date')</td>
    <td>@lang('general.time')</td>
  </tr>
  <tr>
    <td colspan="8" style="border-top: 1px solid #ccc;margin-top:5px;font-size: 8px;text-align: justify;">
      The goods written here are apparently accepted in good condition for transport according to the following clauses {{ $documento->agencia }} provide the service as requested by the sender and make the necessary arrangements for air transport through a direct and responsible airline. {{ $documento->agencia }} It will insure this package against loss or damage to the normal market value limit of US $ 100, or dollars during collection and delivery. The responsibility of {{ $documento->agencia }} in accordance with this paragraph will be reduced by the value of any other insurance that the shipper has or by the loss or damage of the shipment. The sender guarantees {{ $documento->agencia }} That the contents of the shipment can be legally embarked on airplanes or ships and does not contain prohibited substances in accordance with current regulations and regulations and that it is adequately wrapped for its purpose if necessary. The mitente will indemnify {{ $documento->agencia }} any damage suffered by the latter for violating this regulation. This authorizes {{ $documento->agencia }} or its agents to designate a customs broker to act on behalf of the consignee who is appointed to carry out the customs procedure.
    </td>
  </tr>
  <tr>
    <td colspan="6" style="margin-top:5px;font-size: 8px;">
      @lang('general.note_for_sample')
    </td>
    <td colspan="2" style="margin-top:5px;text-align:center;font-size: 10px;color: #1d1d1e;font-weight: bold;">
      {{ $value->num_guia }}
    </td>
  </tr>
  <tr>
    <th colspan="8" style="text-align: center;font-size: 10px;">
      @lang('general.by_dispatching_this_shipping')
    </th>
  </tr>
  <tr>
    <th colspan="8" style="text-align: center;font-size: 10px;">
      @lang('general.the_cargo_can_be_inspected')
    </th>
  </tr>
  </table>
  </div>
  </td>
  </tr>
  <tr>
    <td colspan="4">
      @if ($cont == 1)
      <div id="spaceTable">&nbsp;</div>
      @endif
    </td>
  </tr>
  </tbody>
  </table>
  @endforeach
  @else
  <div id="noDatos">@lang('general.there_is_no_data')</div>
  @endif
  <script type="text/javascript">
    function printHTML() {
               if (window.print) {
                   window.print();
               }
            }
            document.addEventListener("DOMContentLoaded", function (event) {
                printHTML();
        });
  </script>

</html>