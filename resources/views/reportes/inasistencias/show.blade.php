@extends('layout.main')


@section('css')
	<link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection

@section('content')


<table class="table table-hover table-condensed" style="width:100%;" id="myTable" >
		<thead>
			<th>Num Empleado</th>
			<th>Empleado</th>
			<th>Codigo</th>
			<th>Fecha Inicial</th>
			<th>Fecha Final</th>
		</thead>
		<tbody>
		{{--*/ $tmp = "" /*--}}
		@foreach($incidencias as $incidencia)
			 <tr id="tr_table" class="no-table">
				@if($incidencia->num_empleado == $tmp)
				
					<td></td>
					<td></td>
					 <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
					 
					 @if(isset($incidencia->periodo))
							 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
					 @else
					 			<td></td>
					 @endif
					 <td align=center>{{ $incidencia->total_dias }}</td>
					 
				</tr>
				
					
				@else
			
					<tr>
						<td align=center>{{ $incidencia->num_empleado }}</td>
						 <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</td>
						 <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
						 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
						 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
						 
						 @if(isset($incidencia->periodo))
								 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
						 @else
						 			<td></td>
						 @endif
						 <td align=center>{{ $incidencia->total }}</td>
						 
					</tr>
					{{--*/ $tmp = $incidencia->num_empleado /*--}}
				@endif
		@endforeach
		</tbody>
	</table>
@endsection
