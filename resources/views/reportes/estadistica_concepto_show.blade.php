@extends('layout.main')

@section('title', 'Reporte Estadistico por concepto de: '.$codigo->code.' - '.$codigo->description)

@section('content')

<strong>Periodo del: {{$fecha_inicio}} AL {{$fecha_final}}</strong>

	<table class="table table-striped" width="100%">
		<thead>
	 		@foreach($incidencias as $incidencia)
				<th><small>{{ $incidencia->jornada }}</small></th>
			@endforeach
				<th>TOTAL</th>	
		</thead>
		<tbody>
			<tr>
				{{--*/$total=0 /*--}}
				@foreach($incidencias as $incidencia)
					<td><span class="badge">{{ $incidencia->total }}</span></td>
					{{--*/$total+=$incidencia->total /*--}}
				@endforeach
				<td><span class="badge">{{ $total }}</span></td>	
			</tr>
		</tbody>
	</table>

@endsection
