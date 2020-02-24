<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Liquimp</title>
	<style type="text/css">
		.content {
			border-collapse: collapse;
		}

		.content>thead {
			font-weight: bold;
		}
	</style>
</head>

<body>
	<table class="content" border="1">
		<thead>
			<tr>
				<td>Master</td>
				<td>Guia#</td>
				<td>Peso kl</td>
				<td>Valor Declarado</td>
				<td>Fecha Guia</td>
				<td>Arancel</td>
				<td>P.A</td>
				<td>IVA</td>
				<td>Nombre Destinatario</td>
				<td>Direccion Destinatario</td>
				<td>Código Ciudad</td>
				<td>Ciudad Destinatario</td>
				<td>Descripción</td>
				<td>Nombre Remitente</td>
				<td>Piezas</td>
				<td>Flete</td>
				<td>Costo</td>
				<td>Seguro</td>
				<td>$ CIF</td>
				<td>$ Arancel</td>
				<td>$ IVA</td>
				<td>$ Impuesto</td>
			</tr>
		</thead>
		<tbody>
			@foreach ($data as $key => $value)
			<tr>
				<td>{{ $value->master }}</td>
				<td>{{ $value->num_guia }}</td>
				<td>{{ $peso = number_format($value->peso_lb * 0.453592, 2) }}</td>
				<td>{{ $value->declarado }}</td>
				<td>{{ date_format(new DateTime($value->fecha_guia),"d/m/Y") }}</td>
				<td>{{ $value->arancel }}</td>
				<td>{{ $value->pa }}</td>
				<td>{{ $value->iva }}</td>
				<td>
					{{ $value->cons_nomfull2 }}
				</td>
				<td>{{ $value->cons_dir2 }}</td>
				<td>
					{{ str_pad(($value->cons_ciu_codigo2), 5, "0", STR_PAD_LEFT) }}
				</td>
				<td>{{ $value->cons_ciudad2 }}</td>
				<td>{{ $value->contenido }}</td>
				<td>{{ $value->ship_nomfull2 }}</td>
				<td>{{ $value->piezas }}</td>
				<td>{{ round((($value->rate == 0 || $value->rate == '') ? 1.5 : $value->rate) * $peso, 2) }}</td>
				<td>{{ round($value->declarado, 2) }}</td>
				<td>{{ round($value->declarado * 0.005, 2) }}</td>
				<td>
					{{ $cif = round(((($value->rate == 0 || $value->rate == '') ? 1.5 : $value->rate) * $peso) + $value->declarado + ($value->declarado * 0.005), 2) }}
				</td>
				<td>{{ $arancel = round($value->arancel * $cif, 2) }}</td>
				<td>{{ $iva = round(($arancel + $cif) * $value->iva, 2) }}</td>
				<td>{{ $arancel + $iva }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>

</html>