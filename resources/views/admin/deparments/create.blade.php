	{!! Form::model($deparment, [
		'method' => $deparment->exists ? 'put' : 'post', 
		'route' => $deparment->exists ? ['deparments.update', $deparment->code] : ['deparments.store']

		]) !!}
		  
      @include('admin.deparments._form')
  
	{!! Form::close() !!}

