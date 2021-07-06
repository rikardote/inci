{!! Form::open(['route' => ['users.update', $user], 'method' => 'PUT']) !!}
      @include('admin.users.edit_form')
  
{!! Form::close() !!}

