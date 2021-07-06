@extends('layout.main')

@section('title', 'Departamentos')

@section('content')
<div class="supreme-container">
<a data-url="{{ route('deparments.create') }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
    <span class="fa fa-plus-circle fa-2x" aria-hidden='true'></span>
  </a> 
	<table class="table table-striped">
		<thead>
			<th>Codigo</th>
			<th>Descripcion</th>
			
			<th>Accion</th>
		</thead>
		<tbody>
		@foreach($deparments as $deparment)
			<tr>
			 <td>{{ $deparment->code }}</td>
			 <td>{{ $deparment->description }}</td>
			 
			 <td>
				<a data-url="{{ route('deparments.edit', $deparment->code) }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
			 		<span class="fa fa-pencil-square-o fa-2x" aria-hidden='true'></span>
  				</a> 
			 	<a href="{{route('deparments.destroy', $deparment->code) }}"><span class="fa fa-times fa-2x" aria-hidden="true"></span></a>

			 </td>
			</tr>
		@endforeach
		</tbody>
	</table>
	{!! $deparments->render() !!}
</div>
	@include('modals.form-modal', ['title'=>'Agregar/Modificar Departamentos'])
@endsection