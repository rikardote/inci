<div class="form-group">
	{!! Form::label('username', 'Num Empleado') !!}
	
	{!! Form::text('username', null, [
		'class' => 'form-control',
		'placeholder' => 'Introducir num empleado', 
		'required'
	]) !!}
</div>
<div class="form-group">
	{!! Form::label('name', 'Nombre') !!}
	
	{!! Form::text('name', null, [
		'class' => 'form-control',
		'placeholder' => 'Introducir nombre', 
		'required'
	]) !!}
</div>
	
<div class="form-group">
	{!! Form::label('email', 'Correo') !!}
	{!! Form::text('email', null, [
		'class' => 'form-control',
		'placeholder' => 'Correo', 
		'required'
	]) !!}
</div>
<div class="form-group">
	{!! Form::label('password', 'Password') !!}
	{!! Form::password('password', [
		'class' => 'form-control',
		'placeholder' => '*******', 
		'required'
	]) !!}
</div>


<div class="form-group">
		{!! Form::label('departments', 'Centros') !!}
		{!! Form::select('departments[]', $departments, null,['class' => 'form-control select-tipo', 'multiple', 'required']) !!}
</div>

<div class="form-group">
	{!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
</div>

<script>
		$('.select-tipo').chosen({
			placeholder_text_multiple: 'Seleccione centros de trabajo'
		});
		
</script>