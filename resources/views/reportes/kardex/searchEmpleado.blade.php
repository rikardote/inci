<div class="col-md-5">
		{!! Form::open(['route' => 'reportes.empleado.search', 'method' => 'post', 'id' => 'myForm']) !!}
					<div class="form-group">

							<div class="input-group">
						      <input id="auto" type="text" name="num" class="form-control" placeholder="Buscar por numero de empleado">
						      <span class="input-group-btn">
						        <button class="btn btn-success" type="submit"><span class="fa fa-search"></span></button>
						      </span>
						    </div><!-- /input-group -->
					</div>
		{!! Form::close() !!}

		@if(isset($empleado))

		  Ingresar por rango de fechas

			{!! Form::open(['route' => ['reports.empleado.show', $empleado->num_empleado], 'method' => 'post']) !!}
				 	 <div class="form-group col-sm-4">
							  {!! Form::text('fecha_inicio', null, [
							    'id' => 'fecha_inicio',
							    'class' => 'form-control',
							    'placeholder' => 'Fecha Inicial',
							    'required',
							    'id' => 'datepicker_inicial',
							    'autocomplete' => 'off'
							  ]) !!}
		 			 </div>
					<div class="form-group col-sm-4">
					  	{!! Form::text('fecha_final', null, [
						   'id' => 'fecha_final',
						   'class' => 'form-control',
						   'placeholder' => 'Fecha Final',
						   'required',
						   'id' => 'datepicker_final',
						   'autocomplete' => 'off'
						 	]) !!}
					</div>
					{!! Form::button('<span class="fa fa-search"> Consultar</span>', ['id' => 'registro', 'type' => 'submit', 'class' => 'pull-right btn btn-info']) !!}
			{!! Form::close() !!}

			<div class="">
				<a href="{{route('reporte.kardex.todo', [$empleado->num_empleado])}}" class="pull-right btn btn-danger"><span class="fa fa-search"> Consultar Todas las incidencias</span></a>
			</div>
		@endif


</div>

<div class="col-md-7">
		@if(isset($empleado))
				 <div class="notice notice-info notice-sm">
				     <h4 align="center"><strong>{{$empleado->num_empleado}} - {{$empleado->name}} {{$empleado->father_lastname}} {{$empleado->mother_lastname}}</strong></h4>
				 </div>
		@endif
		@if(isset($incidencias))
		<table class="table table-hover table-condensed">
        <thead>
            <th>Codigo</th>
            <th>Fecha Inicial</th>
            <th>Fecha Final</th>
            <th>Periodo</th>
            <th>Total</th>
            <th>Comentario</th>
            <th>Capturado por</th>
        </thead>
        <tbody>

	        @foreach($incidencias as $incidencia)
	            <tr class="no-table">
	                     <td>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}
	                     <td>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
	                     <td>{{ fecha_dmy($incidencia->fecha_final) }}</td>
	                     @if(isset($incidencia->periodo))
	                             <td>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
	                     @else
	                                <td></td>
	                     @endif
	                     <td>{{ $incidencia->total_dias }}</td>
	                     <td>{{ $incidencia->otorgado }},{{ $incidencia->horas_otorgadas }}</td>
                         <td align=center>{{$incidencia->capturado_por }}</td>

	            </tr>
	        @endforeach
        </tbody>
    </table>
       @endif
</div>

<br><br>

@if(isset($noencontrado))
		<div class="alert alert-warning" align="center">
			<h4>{!! $noencontrado !!}</h4>
		</div>
@endif
