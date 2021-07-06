  
	{!! Form::model($qna, ['route' => 'qnas.store', 'method' => 'POST']) !!}
		
	@include('admin.qnas._form')

	{!! Form::close() !!}

