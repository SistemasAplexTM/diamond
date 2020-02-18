<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Bodega</title>
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
				<th colspan="6" class="title">CONSOLIDADO: {{ $data[0]->consecutivo_documento }}</th>
			</tr>
			<tr>
				<td colspan="6">&nbsp;</td>
			</tr>
			<tr>
				{{-- <td>Warehouse#</td> --}}
				<td>Mintic</td>
				<td>Guia#</td>
				<td>Tracking</td>
				<td>Peso lb</td>
				<td>Nombre Destinatario</td>
				<td>Observaciones</td>
			</tr>
		</thead>
		<tbody>
			@foreach ($data as $key => $value)
			<tr>
				{{-- <td>{{ $value->num_warehouse }}</td> --}}
				<td>{{ $value->mintic }}</td>
				<td>{{ $value->num_guia }}</td>
				<td>{{ $value->tracking }}</td>
				<td>{{ $value->peso_lb }}</td>
				<td>{{ $value->cons }}</td>
				<td>&nbsp;</td>
			</tr>
			@endforeach
			<tr>
				<td colspan="6"></td>
			</tr>
			@if($mintics)
			@php
			$flag = true;
			$mintic = '';
			@endphp
			<tr>
				<th colspan="6" style="text-align: center;background-color: goldenrod;">MINTIC</th>
			</tr>
			@foreach ($mintics as $key => $value)
			<tr>
				{{-- <td>{{ $value->num_warehouse }}</td> --}}
				<td>{{ ($value->is_mintic != '') ? $value->mintic : '' }}</td>
				<td>{{ $value->num_guia }}</td>
				<td>{{ $value->tracking }}</td>
				<td>{{ $value->peso_lb }}</td>
				<td>{{ $value->cons }}</td>
				<td>&nbsp;</td>
			</tr>
			@if($flag)
			@php
			$mintic = $value->mintic;
			$flag = false;
			@endphp
			@endif
			@if($mintic != $value->mintic)
			@php
			$flag = true;
			@endphp
			@endif
			@endforeach
			@endif
		</tbody>
	</table>
</body>

</html>