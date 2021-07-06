@extends('layout.main')

@section('title', 'Mantenimiento').

@section('content')

        <p>Haz click para activar o desactivar el periodo de mantenimiento: </p>
            @if($mantenimiento->state)        
            <a id='button' class='switch' href='/mantenimiento/state'><input type='checkbox' checked><span class='slider round'></span></a>
            @else
            <a id='button' class='switch' href='/mantenimiento/state'><input type='checkbox' ><span class='slider round'></span></a>
            @endif
    
@endsection



