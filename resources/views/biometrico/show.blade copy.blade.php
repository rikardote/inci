@extends('layout.main')
@section('title', "Listado de Checadas correspondientes a la Qna: ".$qna->qna.'/'.$qna->year.' | '.$dpto->code.' - '.$dpto->description)
@section('css')
	<link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection
@section('content')

	@if(isset($checadas))
	 	<div class="row">

				{{--*/ $tmp = "" /*--}}
				@foreach($checadas as $checada)
					@if($checada->num_empleado == $tmp)
						<tr>
							<td>{{ fecha_dmy($checada->fecha) }}</td>
							<td>{!! valida_entrada($checada->num_empleado, $checada->fecha) !!}</td>
			                <td>{{ valida_salida($checada->num_empleado, $checada->fecha) }}</td>
						</tr>
					@else
							</table>	
							</div>

							<div class="table-responsive col-md-6">
							<table class="table table-condensed table-striped">
								<thead>
									<tr>
										<th align="center">{{ $checada->num_empleado }} - 
										{{ $checada->father_lastname }} {{ $checada->mother_lastname }} {{ $checada->name }}</th>
										<th></th>
										<th align="right">{{$checada->horario}}</th>
									</tr>
								</thead>
								<thead>
									<th>Fecha</th>
									<th>Entrada</th>
									<th>Salida</th>
								</thead>
									<tr>
										<td>{{ fecha_dmy($checada->fecha) }}</td>
										<td>{!! valida_entrada($checada->num_empleado, $checada->fecha) !!}</td>
						                <td>{{ valida_salida($checada->num_empleado, $checada->fecha) }}</td>
					          		</tr>	
						{{--*/ $tmp = $checada->num_empleado /*--}}
								
					@endif
				
				@endforeach
			</div>
			</table>	
		</div>
	@endif
	
    @include('modals.form-modal', ['title'=>'Capturar Incidencia'])
@endsection

    
  