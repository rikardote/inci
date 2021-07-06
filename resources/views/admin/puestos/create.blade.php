	{!! Form::model($puesto, [
		'method' => $puesto->exists ? 'put' : 'post', 
		'route' => $puesto->exists ? ['puestos.update', $puesto->id] : ['puestos.store']

		]) !!}
		  
      @include('admin.puestos._form')
  
	{!! Form::close() !!}

