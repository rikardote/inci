@extends('layout.main')

@section('title', 'Puestos')

@section('content')
<div class="supreme-container">
<a data-url="{{ route('puestos.create') }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
    <span class="fa fa-plus-circle fa-2x" aria-hidden='true'></span>
  </a> 
	<table class="table table-striped">
		<thead>
			<th>Id</th>
			<th>Puesto</th>
			<th>Accion</th>
		</thead>
		<tbody>
		@foreach($puestos as $puesto)
			<tr>
			 <td>{{ $puesto->id }}</td>
			 <td>{{ $puesto->puesto }}</td>
			
			 <td>
				<a data-url="{{ route('puestos.edit', $puesto->id) }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
			 		<span class="fa fa-pencil-square-o fa-2x" aria-hidden='true'></span>
  				</a> 
			 	<a href="{{route('puestos.destroy', $puesto->id) }}"><span class="fa fa-times fa-2x" aria-hidden="true"></span></a>

			 </td>
			</tr>
		@endforeach
		</tbody>
	</table>
	{!! $puestos->render() !!}
</div>
	@include('modals.form-modal', ['title'=>'Agregar/Modificar Puestos'])
@endsection