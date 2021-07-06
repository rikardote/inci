
  
  {!! Form::model($qna, ['route' => ['qnas.update', $qna->id], 'method' => 'PATCH']) !!}
    
   @include('admin.qnas._form')

  {!! Form::close() !!}
