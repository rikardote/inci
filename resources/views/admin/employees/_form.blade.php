<?php use Carbon\Carbon; ?>
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label('num_empleado', 'Numero de empleado') !!}
			{!! Form::text('num_empleado', null, [
				'class' => 'form-control',
				'placeholder' => 'Numero de empleado', 
				'required'
			]) !!}
		</div>

		<div class="form-group">
			{!! Form::label('name', 'Nombre') !!}
			{!! Form::text('name', null, [
				'class' => 'form-control',
				'placeholder' => 'Nombre', 
				'required'
			]) !!}
		</div>

		<div class="form-group">
			{!! Form::label('father_lastname', 'Apellido Paterno') !!}
			{!! Form::text('father_lastname', null, [
				'class' => 'form-control',
				'placeholder' => 'Apellido Paterno', 
				'required'
			]) !!}
		</div>

		<div class="form-group">
			{!! Form::label('mother_lastname', 'Apellido Materno') !!}
			{!! Form::text('mother_lastname', null, [
				'class' => 'form-control',
				'placeholder' => 'Apellido Materno', 
				'required'
			]) !!}
		</div>
		<div class="form-group">
			{!! Form::label('condicion_id', 'Base, Confianza o Funcionario') !!}
			{!! Form::select('condicion_id', $condiciones, null, [
				'class' => 'form-control',
				'placeholder' => 'Seleccione una opcion', 
				'required'
			]) !!}
		</div>

		<div class="form-group">
			{!! Form::label('fecha_ingreso', 'Fecha de Ingreso') !!}
			{!! Form::text('fecha_ingreso', $employe->fecha_ingreso ? fecha_dmy($employe->fecha_ingreso):null, [
				'class' => 'form-control',
				 'required',
				'id' => 'dob',
				'style' => 'width: 10em;'
			]) !!}

			
		</div>
		
	</div>
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label('deparment_id', 'Departamento') !!}
			{!! Form::select('deparment_id', $deparments, null, [
				'class' => 'form-control',
				'placeholder' => 'Seleccion un departamento', 
				'required'
			]) !!}
		</div>
		<div class="form-group">
			{!! Form::label('jornada_id', 'Jornada') !!}
			{!! Form::select('jornada_id', $jornadas, null, [
				'class' => 'form-control',
				'placeholder' => 'Selecciona una Jornada', 
				'required'
			]) !!}
		</div>
		<div class="form-group">
			{!! Form::label('horario_id', 'Horario') !!}
			{!! Form::select('horario_id', $horarios, null, [
				'class' => 'form-control',
				'placeholder' => 'Seleccion un horario', 
				'required'
			]) !!}
		</div>
		<div class="form-group">
			{!! Form::label('puesto_id', 'Puesto') !!}
			{!! Form::select('puesto_id', $puestos, null, [
				'class' => 'form-control',
				'placeholder' => 'Seleccion un puesto', 
				'required'
			]) !!}
		</div>
		
		
	</div>

	<div class="col-md-4">
		<div class="form-group">
				{!! Form::label('a', 'Antiguedad') !!}
				<br>
				{{--*/ $dt = Carbon::parse($employe->fecha_ingreso) /*--}}
				{!! Form::text('num_seguro', Carbon::createFromDate($dt->year, $dt->month, $dt->day)->diff(Carbon::now())->format('%y Años, %m meses y %d dias'), [
					'class' => 'form-control',
					'placeholder' => 'Num Seguro', 
					'disabled'
				]) !!}	
				
		</div> 
		

<br>
		
		<div class="form-group">
			{!! Form::label('estancia', 'ESTANCIA: ') !!}
			{!! Form::checkbox('estancia') 
			!!}
		</div>
		<div class="form-group">
			{!! Form::label('lactancia', 'LACTANCIA: ') !!}
			{!! Form::checkbox('lactancia') !!}
		</div>

        
        <div id="lactancia_well" class="form-group well well-sm">
            <div class="form-group">
                {!! Form::label('lactancia_inicio', 'Fecha de Inicio') !!}
                {!! Form::text('lactancia_inicio', $employe->lactancia_inicio ? fecha_dmy($employe->lactancia_inicio):"", [
                    'class' => 'form-control',
                    'id' => 'lactancia_inicio',
                ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('lactancia_fin', 'Fecha final') !!}
                {!! Form::text('lactancia_fin', $employe->lactancia_fin ? fecha_dmy($employe->lactancia_fin):"", [
                    'class' => 'form-control',
                    'id' => 'lactancia_fin',
                ]) !!}
            </div>

        </div>
       
        <div class="form-group">
			{!! Form::label('comisionado', 'COMISIONADO: ') !!}
			{!! Form::checkbox('comisionado') 
			!!}
		</div>
		
	</div>

</div>

		

</div>
<div align="right">
		{{--*/ $employe->exists ? $leyenda='Actualizar':$leyenda='Registrar' /*--}}
		{!! Form::submit($leyenda, ['class' => 'btn btn-success']) !!}
</div>
