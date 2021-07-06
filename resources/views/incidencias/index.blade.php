@extends('layout.main')
	@section('title', 'Captura de Incidencias / Buscar Empleado')
	
	@section('content')
	
	@include('incidencias.search')

	@if(isset($empleado))
		<table class="table table-striped">
			<thead>
				<th>Num Empleado</th>
				<th>Nombre</th>
				<th>Qnas</th>
			</thead>
			<tbody>
				<tr>
					<td>{{$empleado->num_empleado}}</td>
					<td>{{$empleado->name}} {{$empleado->father_lastname}} {{$empleado->mother_lastname}}</td>
					<td>
						@foreach($qnas as $qna)
							<a href="{{ route('admin.incidencias.create', [$empleado->num_empleado, $qna->id]) }}" class="btn btn-primary">{{$qna->qna}}/{{$qna->year}}</a>
						@endforeach
					</td>
				</tr>
			</tbody>
		</table>
	@endif
	<br>
	@if(isset($noencontrado))
		<div class="alert alert-warning" align="center">
			<h4>{!! $noencontrado !!}</h4>
		</div>
	@endif

	@endsection

	

