<div class="form-group">
	{!! Form::label('code', 'Codigo') !!}
	
	{!! Form::text('code', null, [
		'class' => 'form-control',
		'placeholder' => 'Codigo del departamento', 
		'required'
	]) !!}
</div>
	
<div class="form-group">
	{!! Form::label('description', 'Descripcion') !!}
	{!! Form::text('description', null, [
		'class' => 'form-control',
		'placeholder' => 'Descripcion', 
		'required'
	]) !!}
</div>


<div class="form-group">
	{!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
</div>