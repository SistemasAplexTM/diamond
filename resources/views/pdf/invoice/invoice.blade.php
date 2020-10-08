<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Invoice - #{{ $data['invoice']->id }}</title>
  <style>
    @page {
      margin: 50px 25px;
    }

    footer {
      position: fixed; 
      bottom: -30px; 
      left: 0px; 
      right: 0px;
      height: 50px; 

      /** Extra personal styles **/
      font-size:8px;
      line-height: 35px;
    }

    * {
      font-family: Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif"
    }

    .title {
      padding-left: 5px;
      padding-top: 1px;
      font-size: 15px;
      font-weight: bold;
    }

    .content {
      padding: 5px;
      font-size: 14px;
    }

    .column {
      width: 18%;
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
      font-size: 12px;
    }

    .border-table{
      border-top:1px solid black;
      border-left:1px solid black;
      border-bottom:1px solid black;
      border-right:1px solid black;
    }
    .bt{
      border-top:1px solid black;
    }
    .br{
      border-right:1px solid black;
    }
    .bl{
      border-left:1px solid black;
    }
    .bb{
      border-bottom:1px solid black;
    }
    
  </style>
</head>

<body>
  <!-- Define header and footer blocks before your content -->

  <footer>
       {{ $data['invoice']->agency->descripcion }} all rights reserved Copyright &copy; <?php echo date("Y");?>
  </footer>

  <!-- Wrap the content of your PDF inside a main tag -->
  <main>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td  rowspan="2" style="text-align:center;padding-bottom:35px;">
            @if(!env('APP_DEPELOPER'))
            <img src="{{ public_path() . '/storage/' }}/{{ $data['invoice']->agency->logo }}" alt="" style="margin: 0 auto">
            @else
            <img src="{{ '/storage/' . $data['invoice']->agency->logo }}" alt="" style="margin: 0 auto">
            @endif
          </td>
        </tr>
        <tr>
          <td class="column column_title1">
            <div class="title">Date</div>
            <div class="content">{{ $data['invoice']->date_document }}</div>
          </td>
          <td class="column column_title1">
            <div class="title">Due Date</div>
            <div class="content">&nbsp;
            {{ $data['invoice']->due_date }}
            </div>
          </td>
          <td class="column column_title1">
            <div class="title">Currency</div>
            <div class="content">{{ $data['invoice']->currency->moneda }}</div>
          </td>
          <td class="column column_title2">
            <div class="title">INVOICE # </div>
            <div class="content" style="font-size:20px;font-weight:bold;text-align:right;padding-right:10px;">
              {{ str_pad($data['invoice']->id,  6, "0", STR_PAD_LEFT) }}
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <table border="1" width="100%" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td colspan="2" valign="top" width="50%">
            <div class="title">Company:</div>
            <div class="content">
              <div>{{ $data['invoice']->agency->descripcion }}</div>
              <div>
                {{ $data['invoice']->agency->direccion }} {{ $data['invoice']->agency->city->nombre }}, 
                {{ $data['invoice']->agency->city->state->abreviatura }} {{ $data['invoice']->agency->zip }}
                </div>
              <div>{{ $data['invoice']->agency->telefono }}</div>
              <div>{{ $data['invoice']->agency->email }}</div>
            </div>
          </td>
          <td colspan="2" valign="top">
            <div class="title">To:</div>
            <div class="content" style="">
            @if ($data['client']->table_name === 'master')
              <pre style="margin:0px;">{{ preg_replace('/<br[^>]*?>/si', "\n",nl2br($data['client']->information)) }}</pre>
            @else
              <div>{{ $data['client']->name }}</div>
              <div>{{ $data['client']->direccion }}</div>
              <div>{{ $data['client']->telefono }}</div>
              <div>{{ $data['client']->correo }}</div>
            @endif
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="border-table" style="margin-top:10px;">
      <thead style="background-color:#fafafa" class="bb">
        <tr>
          <th style="width: 7%;" class="br">
            <div class="title_detail">ITEM</div>
          </th>
          <th style="width: 40%;" class="br">
            <div class="title_detail">DESCRIPTION</div>
          </th>
          <th style="width: 10%;" class="br">
            <div class="title_detail">QUANTITY</div>
          </th>
          <th style="width: 20%;" class="br">
            <div class="title_detail" style="text-align:right">AMOUNT</div>
          </th>
          <th>
            <div class="title_detail" style="text-align:right">TOTAL</div>
          </th>
        </tr>
      </thead>
      <tbody>
        @php
        $valor = 0;
        $total = 0;
        $cont = 1;
        @endphp

        @foreach ($data['invoice']->detail as $item)
        <tr>
          <td class="br" style="height:50px">
            <div class="content_detail" style="text-align:center;">{{ $cont++ }}</div>
          </td>
          <td class="br">
            <div class="content_detail">
              {{ $item->description }}
            </div>
          </td>
          <td class="br">
            <div class="content_detail" style="text-align:center">{{ $item->quantity }}</div>
          </td>
          <td class="br">
            <div class="content_detail" style="text-align:right">
              {{ $data['invoice']->currency->simbolo . ' ' .number_format($item->amount, 2, '.', ',') }}
            </div>
          </td>
          <td>
            <div class="content_detail" style="text-align:right">
            {{ $data['invoice']->currency->simbolo . ' ' .number_format($item->quantity * $item->amount, 2, '.', ',') }}
            </div>
          </td>
        </tr>

        @php
        $valor += $item->amount;
        $total +=$item->quantity * $item->amount;
        @endphp
        @endforeach

        @if ($cont < 8)
            @for ($i = $cont - 8 ; $i <= 5; $i++)
                <tr>
                  <td class="br" style="height:50px">&nbsp;</td>
                  <td class="br">&nbsp;</td>
                  <td class="br">&nbsp;</td>
                  <td class="br">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
            @endfor
        @endif

      </tbody>
      <tfoot>
        <tr>
          {{-- <td style="padding: 10px;font-weight: bold;font-size: 20px;text-align:center">{{ $cont-1 }}</td> --}}
          <td colspan="4" class="bt" style="padding: 10px;font-weight: bold;font-size: 20px;text-align:right">TOTAL</td>
          {{-- <td style="padding: 10px;font-weight: bold;font-size: 20px;">
            {{ number_format($valor, 2, '.', ',') }}
          </td> --}}
          <td class="bt"  style="padding: 10px;font-weight: bold;font-size: 20px;text-align:right">
            {{ $data['invoice']->currency->simbolo . ' ' .number_format($total, 2, '.', ',') }}
          </td>
        </tr>
      </tfoot>
    </table>
  </main>
  
</body>
</html>