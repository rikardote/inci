@extends('layout.main')
	@section('title', 'Logs')

	@section('content')
		<div>
			<strong>
				<p>
					La idea de esta función es mostrar todas las incidencias capturadas, incluidas las que han sido eliminadas, de manera que cuando la unidades soliciten apertura extraordinaria del sistema, ver lo que capturaron o borraron de una forma mas clara.
				</p>
			</strong>
		</div>

		<table class="table table-striped table-condensed" style="width:100%;" >
		<thead>
			<th>Unidad</th>
			<th>Num Empleado</th>
			<th>Empleado</th>
			<th>Codigo</th>
			<th>Fecha Inicial</th>
			<th>Fecha Final</th>
			<th>Total Dias</th>
			<th>Periodo</th>
			<th>Fecha de captura</th>
			<th>Fecha de Borrado</th>
		</thead>
		<tbody>
			@foreach($incidencias as $incidencia)
				<tr class={{ is_null($incidencia->inc_deleted) ? '' : 'danger'}}>
					 <td>{{ getDeparment($incidencia->deparment_id) }}</td>
					 <td align=center>{{ $incidencia->num_empleado }}</td>
					 <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</td>
					 <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
					 <td align=center>{{ $incidencia->total_dias }}</td>
					 <td align=center>{{ ($incidencia->periodo) ?  $incidencia->periodo.'/'.$incidencia->periodo_year : ''}} </td>
					 <td align=center>{{ fecha_dmy_hora($incidencia->inc_crea) }}</td>
					 <td align=center>{{ fecha_dmy_hora_los_angeles($incidencia->inc_deleted) }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	@endsection
