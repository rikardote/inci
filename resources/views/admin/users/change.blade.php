{!! Form::open(['route' => ['user.change.store', $user->id], 'method' => 'PATCH']) !!}
	<div class="form-group">
	{!! Form::label('password', 'Contraseña Nueva') !!}
	{!! Form::password('password', [
		'class' => 'form-control', 
		'placeholder'=>"**************",
		'required'
	]) !!}
</div>
<div align="right">
	     {!! Form::submit('Actualizar', ['class' => 'btn btn-success']) !!}
	</div>  
{!! Form::close() !!}

