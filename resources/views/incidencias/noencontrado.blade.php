
@extends('layout.main')

@section('title', 'Agregar Incidencia')

@section('content')
@include('incidencias.search')
<br>
 <div align="center" class="alert alert-warning">
 	<strong><i class='fa fa-exclamation-triangle'></i> Atencion!</strong><br>
  	{!!$error!!}
 </div>

@endsection

