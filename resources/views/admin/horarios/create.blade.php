	{!! Form::model($horario, [
		'method' => $horario->exists ? 'put' : 'post', 
		'route' => $horario->exists ? ['horarios.update', $horario->id] : ['horarios.store']

		]) !!}
		  
      @include('admin.horarios._form')
  
	{!! Form::close() !!}

