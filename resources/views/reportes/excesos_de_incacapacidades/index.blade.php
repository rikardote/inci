@extends('layout.main')
	@section('title', 'Reporte de Exceso de Incapacidades')

	@section('content')
		<thead>
		<table class="table">
			<th>Num Empleado</th>
			<th>Nombre</th>
			<th>total licencia</th>
			<th>Antiguedad</th>
			<th>Del</th>
			<th>Al</th>
		</thead>
		<tbody>
			@foreach($data as $empleado)
				<tr>

					{{--*/ $fecha_actual = getdateActual($empleado->fecha_ingreso); /*--}}
         			{{--*/ $fecha_posterior = getdatePosterior($fecha_actual); /*--}}
         			{{--*/ $antiguedad = getAntiguedad($empleado->fecha_ingreso); /*--}}
 					{{--*/ $a = getExcesodeIncapacidad($empleado->total, $antiguedad); /*--}}


					@if($a)
						<td>{{$empleado->num_empleado}}</td>
						<td>{{$empleado->father_lastname}} {{$empleado->mother_lastname}} {{$empleado->name}}</td>
						<td>{{$empleado->total}}</td>	
						<td>{{$antiguedad}}</td>
						<td>{{fecha_dmy($fecha_actual)}}</td>
						<td>{{fecha_dmy($fecha_posterior)}}</td>
					@endif
				</tr>
			
			@endforeach
		</table>
		</tbody>
	@endsection


