@extends('layout.main')

@section('title', $title)

@section('content')
<table class="table table-hover table-condensed">
		<thead>
			
			<th>Num Empleado</th>
			<th>Empleado</th>
			<th>Codigo</th>
			<th>Fecha Inicial</th>
			<th>Fecha Final</th>
			<th>Periodo</th>
			<th>Total</th>
			<th>Accion</th>

		</thead>
		<tbody>
		{{--*/ $tmp = "" /*--}}
		@foreach($incidencias as $incidencia)
			<tr class="no-table">
				@if($incidencia->num_empleado == $tmp)
				
					<td></td>
					<td></td>
					 <td align=center>{{ $incidencia->code }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
					 
					 @if(isset($incidencia->periodo))
							 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
					 @else
					 			<td></td>
					 @endif
					 <td align=center>{{ $incidencia->total }}</td>
					 <td>
					 	<a href="{{ route('incidencias.destroy', [$incidencia->token, $incidencia->num_empleado, $incidencia->qna_id]) }}" <span class="fa fa-times"></span></a>
					 </td>
				</tr>
				
					
				@else
				@for ($i=0; $i < 9; $i++)  
					<td></td>
				@endfor
					<tr>
						<td align=center>{{ $incidencia->num_empleado }}</td>
						 <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</td>
						 <td align=center>{{ $incidencia->code }}</td>
						 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
						 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
						 
						 @if(isset($incidencia->periodo))
								 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
						 @else
						 			<td></td>
						 @endif
						 <td align=center>{{ $incidencia->total }}</td>
						 <td>
						 	<a href="{{ route('incidencias.destroy', [$incidencia->token, $incidencia->num_empleado, $incidencia->qna_id]) }}" <span class="fa fa-times"></span></a>
						 </td>
					</tr>
					{{--*/ $tmp = $incidencia->num_empleado /*--}}
				@endif
		@endforeach
		</tbody>
	</table>
@endsection