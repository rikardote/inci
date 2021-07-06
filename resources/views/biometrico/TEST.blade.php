@extends('layout.main')
@section('title', "Listado de Checadas correspondientes a la Qna: ".$qna->qna.'/'.$qna->year.' - '.$qna->description)
@section('content')

	@if(isset($checadas))
	 	<table class="table table-hover table-condensed">
	 		<thead>
				<th>Num Empleado</th>
				<th>Nombre</th>
				<th>Fecha</th>
				<th>Fecha Inicio</th>
				<th>Fecha Final</th>
			</thead>
			<tbody>	
				{{--*/ $tmp = "" /*--}}
				@foreach($checadas as $checada)
					@if($checada->num_empleado == $tmp)
						<tr data-url="{{ route('biometrico.capturar',[$checada->fecha, $checada->num_empleado]) }}" class="load-form-modal panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
							<td></td>
							<td></td>
							<td>{{ fecha_dmy($checada->fecha) }}</td>
							<td>{{ valida_entrada($checada->num_empleado, $checada->fecha, $checada->entrada) }}</td>
			                <td>{{ valida_salida($checada->num_empleado, $checada->fecha, $checada->salida) }}</td>
						</tr>
					@else
						<tr data-url="{{ route('biometrico.capturar',[$checada->fecha, $checada->num_empleado]) }}" class="load-form-modal panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
							<td><b>{{ $checada->num_empleado }}</b></td>
							<td><b>{{ $checada->father_lastname }} {{ $checada->mother_lastname }} {{ $checada->name }}</b></td>
							<td>{{ fecha_dmy($checada->fecha) }}</td>
							<td>{{ valida_entrada($checada->num_empleado, $checada->fecha, $checada->entrada) }}</td>
			                <td>{{ valida_salida($checada->num_empleado, $checada->fecha, $checada->salida) }}</td>
						</tr>
						{{--*/ $tmp = $checada->num_empleado /*--}}

					@endif
				@endforeach
			</tbody>
		</table>
			
	@endif
    @include('modals.form-modal', ['title'=>'Capturar Incidencia'])
@endsection