@extends('layout.main')

@section('title', 'Periodos')

@section('content')
<div class="supreme-container">
<a data-url="{{ route('periodos.create') }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
    <span class="fa fa-plus-circle fa-2x" aria-hidden='true'></span>
  </a> 
	<table class="table table-striped">
		<thead>
			<th>Periodo</th>
			<th>Año</th>
			
			<th>Accion</th>
		</thead>
		<tbody>
		@foreach($periodos as $periodo)
			<tr>
			 <td>{{ $periodo->periodo }}</td>
			 <td>{{ $periodo->year }}</td>
			 
			 <td>
				<a data-url="{{ route('periodos.edit', $periodo->id) }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
			 		<span class="fa fa-pencil-square-o fa-2x" aria-hidden='true'></span>
  				</a> 
			 	<a href="{{route('periodos.destroy', $periodo->id) }}"><span class="fa fa-times fa-2x" aria-hidden="true"></span></a>

			 	
			 </td>
			</tr>
		@endforeach
		</tbody>
	</table>
	{!! $periodos->render() !!}
</div>
	@include('modals.form-modal', ['title'=>'Agregar/Modificar Periodos'])
@endsection