<table style="font-size:9.5pt text-align:center; font-family:family:Arial; width: 100%; ">
	<thead>
	<tr style=" background-color: rgb(171,165,160);  ">
			<td>Num Empleado</td>
			<td>Empleado</td>
			<td>Total</td>
			<td>Fecha Inicial</td>
			<td>Fecha Final</td>
			<td>Diagnostico</td>
			<td>Medico Tratante</td>
			<td>Folio</td>
			<td>Expedida</td>
	</tr>
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