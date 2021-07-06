@extends('layout.main')
@section('title', 'Pendientes de: '.$dpto->description)

@section('content')
<ul class="nav nav-pills nav-center">
  <li><a href="{{route('reports.pendientes.id', [$qna_id, $dpto->code, 35])}}"><span class="badge">Enfermeria</span></a></li>
  <li><a href="{{route('reports.pendientes.id', [$qna_id, $dpto->code, 36])}}"><span class="badge">Administrativos</span></a></li>
  <li><a href="{{route('reports.pendientes.id', [$qna_id, $dpto->code, 37])}}"><span class="badge">Medicos</span></a></li>
</ul>
@if(isset($pendientes))
	<table class="table table-striped">
	<tr>
		<th>Num Empleado</th>
		<th>Nombre</th>
		<th>Pendiente</th>
		<th>Fecha Inicio</th>
		<th>Fecha Final</th>
	</tr>
	
		@foreach($pendientes as $pendiente)
			<tr>
				<td>{{$pendiente->num_empleado}}</td>
				<td>{{$pendiente->father_lastname }} {{ $pendiente->mother_lastname }} {{ $pendiente->name }}</td>
				<td>{{$pendiente->pendientes}}</td>
				<td>{{ fecha_dmy($pendiente->fecha_inicio) }}</td>
                <td>{{ fecha_dmy($pendiente->fecha_final) }}</td>
			</tr>
		@endforeach
	</table>
@endif
@endsection