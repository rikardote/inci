@extends('layout.main')

@section('title', 'Reporte Biometrico por Centro de Trabajo')

@section('content')


{!! Form::open(['route' => ['biometrico.buscar'], 'method' => 'POST']) !!}

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
					<div class="col-md-6">
						{!! Form::open(['route' => ['biometrico.buscar'], 'method' => 'POST']) !!}
						<div class="form-group">
							{!! Form::select('qna', $qnas, null, [
								'class' => 'form-control',
								'placeholder' => 'Selecciona la Quincena',
                                'required'
							]) !!}
    					</div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
							{!! Form::select('year', $years, $default_year, [
								'class' => 'form-control',
                                'required',
							]) !!}
    					</div>
                        <input type="hidden" name="dpto" value={{ $dpto->code }}>
					</div>
                    <div class="input-group">
                        {{ Form::button('<i class="fa fa-search" aria-hidden="true"></i>', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) }}
                    </div>

					{!!Form::close()!!}
				</td>
			</tr>
		@endforeach
	</tbody>
</table>

{!!Form::close()!!}
@endsection
