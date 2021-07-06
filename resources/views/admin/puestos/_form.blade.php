<div class="form-group">
	{!! Form::label('puesto', 'Puesto') !!}
	
	{!! Form::text('puesto', null, [
		'class' => 'form-control',
		'placeholder' => 'Ingrese el puesto de trabajo', 
		'required'
	]) !!}
</div>
	


<div class="form-group">
	{!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
</div>