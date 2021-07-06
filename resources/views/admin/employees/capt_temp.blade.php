{!! Form::model($employe, ['route' => ['employees.capt_update', $employe->num_empleado], 'method' => 'PATCH']) !!}

	<div class="form-group">
		{!! Form::label('jornada_id', 'Jornada') !!}
		{!! Form::select('jornada_id', $jornadas, $employe->jornada_id, [
			'class' => 'form-control',
			'placeholder' => 'Selecciona una Jornada', 
			'required'
		]) !!}
	</div>
	<div class="form-group">
		{!! Form::label('horario_id', 'Horario') !!}
		{!! Form::select('horario_id', $horarios, $employe->horario_id, [
			'class' => 'form-control',
			'placeholder' => 'Seleccion un horario', 
			'required'
		]) !!}
	</div>
	{{ Form::hidden('num_empleado', $employe->num_empleado) }}
	<div align="right">
		{!! Form::submit('Registrar', ['class' => 'btn btn-success']) !!}
	</div>
{!! Form::close() !!}