@extends('layout.main')

@section('title', '.')

@section('content')


{!! Form::open(['route' => ['biometrico.asignar_post'], 'method' => 'POST']) !!}	
<div class="form-group col-sm-4">
    {!! Form::text('num_empleado', null, [
        'class' => 'form-control',
        'placeholder' => 'Numero de empleado', 
        'required',
        'autocomplete' => 'off',
        'id' => 'nombre'
      ]) !!}

    {!! Form::text('fecha', null, [
      'class' => 'form-control',
      'placeholder' => 'DD/MM/AAAA', 
      'required',
      'autocomplete' => 'off',
      'id' => 'fecha'
    ]) !!}
  
  </div>
    <div class="form-group"> {!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success pull pull-right']) !!} </div>
{!!Form::close()!!}
@endsection

@section('js')
    <script>
        $("#fecha").flatpickr({
            enableTime: true,
            allowInput: true,
            dateFormat: "Y-m-d H:i",
            locale: 'es',
        });
    </script>
@endsection
