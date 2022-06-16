@extends('layout.main')
@section('title', "Listado de Checadas correspondientes a la Qna: ".$qna->qna.'/'.$qna->year.' | '.$dpto->code.' - '.$dpto->description)

@section('content')

    <a href="{{route('biometrico.pdf', [$qna->id, $dpto->id])}}" class="icon-pdf btn btn-warning"><i class="fa fa-file-pdf-o fa-1x"> Generar Reporte</i></a>


	@if(isset($empleados))
	 	<div class="row">

				{{--*/ $tmp = "" /*--}}
				@foreach($empleados as $empleado)
				<div class="table-responsive col-md-6">
					<table class="table table-condensed table-striped">
						<thead>
							<tr>
								<th align="center">{{ $empleado->num_empleado }} -
								{{ $empleado->father_lastname }} {{ $empleado->mother_lastname }} {{ $empleado->name }}</th>
								<th></th>
								<th align="right">{{$empleado->horario}}</th>
							</tr>
						</thead>
						<thead>
							<th>Fecha</th>
							<th>Entrada</th>
							<th>Salida</th>
						</thead>
							@foreach ( $daterange as $date)
								<tr>
									{{--*/ $entrada = check_entrada($date->format("Y-m-d"), $empleado->num_empleado) /*--}}
									{{--*/ $salida =  check_salida($date->format("Y-m-d"), $empleado->num_empleado) /*--}}
									@if(!isweekend($date->format("Y-m-d")))
										<td> {{ $date->format("d/m/Y") }}</td>
										<td> {!! valida_entrada($empleado->num_empleado, $date->format("Y-m-d"), $entrada) !!}</td>

                                            <td> {!! valida_salida($empleado->num_empleado, $date->format("Y-m-d"), $salida, $entrada) !!}</td>

									@endif
								</tr>		
							@endforeach
							
					</table>
				</div>
				@endforeach
			
			</table>	
		</div>
	@endif

@endsection

    
  