@extends('layout.main')

@section('title', $title)

@section('content')
<table class="table table-striped">
	<thead>
		<th>Codigo</th>
		<th>Descripcion</th>
		<th></th>
	</thead>
	<tbody>
		
		@foreach($dptos as $dpto)
			<tr>
				<td>{{$dpto->code}}</td>
				
				<td>{{$dpto->description}}</td>
				<td>
					<div class="col-md-12">
						{!! Form::open(['route' => ['reports.general.show', $dpto->code], 'method' => 'POST']) !!}
						<div class="form-group">
							{!! Form::select('qna_id', $qnas, null, [
								'class' => 'form-control',
								'placeholder' => 'Selecciona una Quincena', 
								'onchange' => 'if(this.value != 0) { this.form.submit(); }'
							]) !!}
						</div>
					</div>

					{!!Form::close()!!}
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection