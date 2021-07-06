
  
  {!! Form::model($periodo, ['route' => ['periodos.update', $periodo->id], 'method' => 'PATCH']) !!}
    
   @include('admin.periodos._form')

  {!! Form::close() !!}
