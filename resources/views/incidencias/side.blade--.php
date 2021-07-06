<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
		<link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
</head>
<body>
  
<table id="records_table" class="table table-hover table-condensed">
		<thead>
			<th>Qna</th>
			<th>Codigo</th>
			<th>Fecha Inicial</th>
			<th>Fecha Final</th>
			<th>Total</th>
			<th>Periodo</th>
			<th>Accion</th>

		</thead>
		<tbody>
		
		@foreach($incidencias as $incidencia)
					<tr>
						<td>{{$incidencia->qna}}/{{$incidencia->qna_year}}</td>
						 <td>{{ $incidencia->code }}</td>
						 <td> {{ fecha_dmy($incidencia->fecha_inicio) }}</td>
						 <td>{{ fecha_dmy($incidencia->fecha_final) }}</td>
						 <td>{{ $incidencia->total_dias }}</td>
						 @if(isset($incidencia->periodo))
								 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
						 @else
						 			<td></td>
						 @endif
						 
						 <td>
						 	<button type="button" id="delete" href="#" <span class="fa fa-times fa-2x"></span></button>
						 	<?php //{{ route('incidencias.destroy', [$incidencia->token, $employee->num_empleado, $incidencia->id]) }} ?>
						 </td>
					</tr>
					
				
		@endforeach
		</tbody>
	</table>
<script src="{{ asset('plugins/jquery/js/jquery.js') }}"></script>

<script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>
</body>
</html>
