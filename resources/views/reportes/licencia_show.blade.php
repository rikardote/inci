@extends('layout.main')

@section('title', 'Reporte de Licencias Medicas')

@section('content')

<table class="table table-hover table-condensed">
		<thead>
			<th>Num Empleado</th>
			<th>Empleado</th>
			<th>Total</th>
			<th>Fecha Inicial</th>
			<th>Fecha Final</th>
			<th>Diagnostico</th>
			<th>Medico Tratante</th>
			<th>Folio</th>
			<th>Expedida</th>
		</thead>
		<tbody>
			@foreach($incidencias as $incidencia)
			<tr>
				<td><small>{{$incidencia->num_empleado}}</small></td>
				<td><small>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</small></td>
				<td align=center>{{ $incidencia->total }}</td>
				<td align=center><small>{{ fecha_dmy($incidencia->fecha_inicio) }}</small></td>
				<td align=center><small>{{ fecha_dmy($incidencia->fecha_fin) }}</small></td>
				<td> <small>{{$incidencia->diagnostico}}</small> </td>
				<td> <small>{{getDoctor($incidencia->medico_id)}}</small> </td>
				<td><small> {{$incidencia->num_licencia}}</small> </td>
				@if($incidencia->fecha_expedida > $incidencia->fecha_inicio)
					<td><strong>{{fecha_dmy($incidencia->fecha_expedida)}}*<strong></td>
				@else
					<td>@if($incidencia->fecha_expedida)
						{{fecha_dmy($incidencia->fecha_expedida)}}
						 @else
						 <strong>**********</strong>
						 @endif
					</td>
				@endif

			</tr>
			@endforeach
		</tbody>
</table>

@endsection
