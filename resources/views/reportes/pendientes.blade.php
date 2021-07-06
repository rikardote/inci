@extends('layout.main')
@section('title', 'Pendientes')

@section('content')
	<table class="table table-striped">
	<thead>
		<th>Codigo</th>
		<th>Descripcion</th>
		<th>Acciones</th>
	</thead>
	<tbody>
		
		@foreach($dptos as $dpto)
			<tr>
				<td>{{$dpto->code}}</td>
				<td>{{$dpto->description}}</td>
				<td>
					<div class="col-md-6">
						{!! Form::open(['route' => ['reports.pendientes.show', $dpto->code], 'method' => 'POST']) !!}
						<div class="form-group">
							{!! Form::select('qna_id', $qnas, null, [
								'class' => 'form-control',
								'placeholder' => 'Seleccion una Quincena', 
							]) !!}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							{!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success']) !!}
						</div>
						{!!Form::close()!!}
					</div>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection