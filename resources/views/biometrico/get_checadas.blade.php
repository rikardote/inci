@extends('layout.main')

@section('title', 'Reporte Estadistico por Incidencia')

@section('content')


{!! Form::open(['route' => ['biometrico.buscar'], 'method' => 'POST']) !!}	
<table class="table table-striped">
	<thead>
		<th>Fecha Inicial</th>
		<th>Fecha Final</th>
	</thead>
	<tbody>
		<tr>
			
			<td>
				<div class="form-group">
					
					<select class="form-control" id="deparment_id" name="deparment_id" required>
					    <option value="">Selecciona un Departamento</option>
					    @foreach($dptos as $deparment)
					      <option value="{{$deparment->id}}">{{$deparment->code}} - {{$deparment->description}}</option>
					    @endforeach
					 </select>  
				</div>
			</td>
			<td>
				
				<div class="form-group">
							{!! Form::select('qna_id', $qnas, null, [
								'class' => 'form-control',
								'placeholder' => 'Selecciona una Quincena',
								'required'
							]) !!}
				</div>
			</td>
			
		</tr>

 </tbody>
	
</table>
<div class="form-group"> {!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success pull pull-right']) !!} </div>
{!!Form::close()!!}
@endsection

