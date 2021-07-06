@extends('layout.main')

@section('title', 'Horarios')
@section('css')
	
@endsection
<link rel="stylesheet" href="{{ asset('plugins/timepicker/jquery.timepicker.css') }}">

@section('content')
<div class="supreme-container">
<a data-url="{{ route('horarios.create') }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
    <span class="fa fa-plus-circle fa-2x" aria-hidden='true'></span>
  </a> 
	<table class="table table-striped">
		<thead>
			<th>Horario</th>
			<th>Accion</th>
		</thead>
		<tbody>
		@foreach($horarios as $horario)
			<tr>
			 <td>{{ $horario->horario }}</td>
			
			 
			 <td>
				
			 	<a href="{{route('horarios.destroy', $horario->id) }}"><span class="fa fa-times fa-2x" aria-hidden="true"></span></a>

			 </td>
			</tr>
		@endforeach
		</tbody>
	</table>
	{!! $horarios->render() !!}
</div>
	@include('modals.form-modal', ['title'=>'Agregar/Modificar Horarios'])
@endsection
@section('js')
	<script src="{{ asset('plugins/timepicker/jquery.timepicker.js') }}"></script>

@endsection