<div class="form-group">
	{!! Form::label('name', 'Nombre') !!}
	
	{!! Form::text('name', $user->name, [
		'class' => 'form-control',
		'placeholder' => 'Introducir nombre', 
		'required'
	]) !!}
</div>
	
<div class="form-group">
	{!! Form::label('email', 'Correo') !!}
	{!! Form::text('email', $user->email, [
		'class' => 'form-control',
		'placeholder' => 'Correo', 
		'required'
	]) !!}
</div>
<div class="form-group">
		{!! Form::label('departments', 'Centros') !!}
		{!! Form::select('departments[]', $departments, $departments_select,['class' => 'form-control select-tipo', 'multiple', 'required']) !!}
</div>

<div class="form-group">
	{!! Form::label('type', 'Tipo') !!}
	{!! Form::select('type', ['member' => 'Miembro', 'admin' => 'Administrador', 'consulta' => 'Consulta'], $user->type, ['class' => 'form-control', 'placeholder' => 'Seleccione una opcion...']) !!}
</div>

<div class="form-group">
	{!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
</div>

<script>
		$('.select-tipo').chosen({
			placeholder_text_multiple: 'Seleccione centros de trabajo'
		});

</script>
