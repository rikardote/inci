@extends('layout.main')

@section('title', $title)

@section('content')
	<p>Para iniciar la captura seleccione como desea realizarla: </p>
<table class="table">

	<thead>
		<td>Opcion</td>
		<td>Total</td>
		<!--<td>Pendientes</td>-->
		<td>Accion</td>
	</thead>
	<tbody>
		
		@foreach ($incidencias as $incidencia)
		<tr>
			<td>{{$incidencia->dep_desc}}</td>
			<td><span class="badge">{{$incidencia->total}}</span></td>
			
			<td><a class="btn btn-info btn-xs glyphicon glyphicon-pencil" href="{{route('admin.capturar.capturar_centro', [$incidencia->qna_id, $incidencia->dep_code])}}"></a></td>
		</tr>
		@endforeach

	</tbody>

</table>

@endsection
