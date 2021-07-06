	{!! Form::model($periodo, ['route' => 'periodos.store', 'method' => 'POST']) !!}
		
	@include('admin.periodos._form')

	{!! Form::close() !!}
