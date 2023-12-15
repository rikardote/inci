@extends('layout.main')

@section('title', 'Incidencias')

@section('content')
<div class="supreme-container">
<a data-url="{{ route('codigosdeincidencias.create') }}" class="load-form-modal panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
    <span class="fa fa-plus-circle fa-2x" aria-hidden='true'></span>
 </a>

	<table class="table table-striped">
		<thead>
			<th>Codigo</th>
			<th>Descripcion</th>

			<th>Accion</th>
		</thead>
		<tbody>
		@foreach($incidencias as $incidencia)
			<tr>
                <td>{{ $incidencia->id }}</td>
                <td>{{ $incidencia->code }}</td>
                <td>{{ $incidencia->description }}</td>

			 <td>
                {{ $incidencia->id }}
				<a data-url="{{ route('codigosdeincidencias.edit', $incidencia->code) }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
			 		<span class="fa fa-pencil-square-o fa-2x" aria-hidden='true'></span>
  				</a>
			 	<a href="{{route('codigosdeincidencias.destroy', $incidencia->code) }}"><span class="fa fa-times fa-2x" aria-hidden="true"></span></a>

			 </td>
			</tr>
		@endforeach
		</tbody>
	</table>
{!! $incidencias->render() !!}
</div>
	@include('modals.form-modal', ['title'=>'Agregar/Modificar Codeigos de incidencia'])
@endsection
