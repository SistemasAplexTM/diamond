<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Delivery Order - {{ $master->num_master }}</title>
  <style>
    * {
      font-family: Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif"
    }

    .title {
      padding-left: 5px;
      padding-top: 1px;
      font-size: 9px;
    }

    .content {
      padding: 5px;
      font-size: 12px;
    }

    .column {
      width: 25%;
    }

    .column_title1 {
      border-left: 1px solid black;
      border-top: 1px solid black;
      border-right: 1px solid black;
    }

    .column_title2 {
      border-top: 1px solid black;
      border-right: 1px solid black;
    }

    .title_detail {
      padding: 5px;
      font-size: 12px;
    }

    .content_detail {
      padding: 5px;
    }
  </style>
</head>

<body>
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
      <tr>
        <td colspan="2" rowspan="2" style="text-align:center;">
          @if(env('APP_DEPELOPER'))
          <img src="{{ public_path() . '/storage/' }}/{{ $agencia->logo }}" alt="" style="margin: 0 auto">
          @else
          <img src="{{ '/storage/' . $agencia->logo }}" alt="" style="margin: 0 auto">
          @endif
        </td>
        <td colspan="2" valign="top" style="padding-bottom: 30px;text-align: center;">DELIVERY ORDER</td>
      </tr>
      <tr>
        <td class="column column_title1">
          <div class="title">Date</div>
          <div class="content">{{ $master->fecha_vuelo1 }}</div>
        </td>
        <td class="column column_title2">
          <div class="title">File Number</div>
          <div class="content">&nbsp;</div>
        </td>
      </tr>
    </tbody>
  </table>
  <table border="1" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
      <tr>
        <td class="column">
          <div class="title">Flight/Voyage</div>
          <div class="content">{{ $master->fecha_vuelo1 }}</div>
        </td>
        <td class="column">
          <div class="title">Way Bill Number</div>
          <div class="content">{{ $master->num_master }}</div>
        </td>
        <td class="column">
          <div class="title">Destination</div>
          <div class="content">{{ $master->to1 }}</div>
        </td>
        <td class="column">
          <div class="title">Prepared By</div>
          <div class="content">&nbsp;</div>
        </td>
      </tr>
      <tr>
        <td colspan="2" valign="top">
          <div class="title">Pickup Location</div>
          <div class="content">
            <pre>{{ $master->carrier }}</pre>
          </div>
        </td>
        <td colspan="2" valign="top">
          <div class="title">Deliverid To (Name And Address)</div>
          <div class="content">
            <pre>{{ $master->nombre_aerolinea }}</pre>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2" valign="top">
          <div class="title">Shipper (Name And Address)</div>
          <div class="content">
            <pre>{{ $master->shipper }}</pre>
          </div>
        </td>
        <td colspan="2" valign="top">
          <div class="title">Consignee (Name And Address)</div>
          <div class="content">
            <pre>{{ $master->consignee }}</pre>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" colspan="5" style="height: 600px;">
        <table border="1" width="100%" style="height: 100%" cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th style="width: 25%;">
                <div class="title_detail">MARKS AND NUMBERS</div>
              </th>
              <th style="width: 7%;">
                <div class="title_detail">QTY</div>
              </th>
              <th style="width: 40%;">
                <div class="title_detail">DESCRIPTION</div>
              </th>
              <th style="width: 15%;">
                <div class="title_detail">VOL WEIGHT</div>
              </th>
              <th>
                <div class="title_detail">WEIGHT</div>
              </th>
            </tr>
          </thead>
          <tbody>
            @php
            $peso_cobrado = 0;
            $peso_kl = 0;
            $piezas = 0;
            @endphp

            @foreach ($detalle as $item)
            <tr>
              <td valign="top">
                <div class="content_detail"></div>
              </td>
              <td valign="top">
                <div class="content_detail">{{ $item->piezas }}</div>
              </td>
              <td valign="top">
                <div class="content_detail">
                  <pre>{{ $item->descripcion }}</pre>
                </div>
              </td>
              <td valign="top">
                <div class="content_detail">{{ number_format($item->peso_cobrado, 2, '.', ',') }}</div>
              </td>
              <td valign="top">
                <div class="content_detail">{{ number_format($item->peso_kl, 2, '.', ',') }}
                  {{ $item->unidad_medida }}
                </div>
              </td>
            </tr>

            @php
            $piezas += $item->piezas;
            $peso_cobrado +=$item->peso_cobrado;
            $peso_kl += $item->peso_cobrado;
            @endphp
            @endforeach

          </tbody>
          <thead>
            <tr>
              <td></td>
              <td style="padding: 10px;font-weight: bold;font-size: 20px;">
                {{ number_format($piezas, 0, '.', ',') }}
              </td>
              <td style="padding: 10px;font-weight: bold;font-size: 20px;text-align:right">TOTAL</td>
              <td style="padding: 10px;font-weight: bold;font-size: 20px;">
                {{ number_format($peso_cobrado, 2, '.', ',') }}
              </td>
              <td style="padding: 10px;font-weight: bold;font-size: 20px;">
                {{ number_format($peso_kl, 2, '.', ',') }}
              </td>
            </tr>
          </thead>
        </table>
      </td>
    </tr>
  </table>
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
</body>

</html>