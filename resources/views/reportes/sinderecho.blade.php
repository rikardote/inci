@extends('layout.main')

@section('title', $title)

@section('content')
<table class="table table-striped">
	<thead>
		<th>Codigo</th>
		<th>Descripcion</th>
		<th>Mes y Año</th>
	</thead>
	<tbody>
 
		@foreach($dptos as $dpto)
			<tr> 
				<td>{{$dpto->code}}</td>
				<td>{{$dpto->description}}</td>
				<td>
					<div class="col-md-4">
						{!! Form::open(['route' => ['reports.sinderecho_show', $dpto->code], 'method' => 'POST']) !!}
						<div class="form-group">
							 {!! Form::select('month', ['1' => 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE', 'DICIEMBRE'], $month, [
									'class' => 'form-control',
									'required'
							  ]) !!} 
						</div>
					</div>
					<div class="col-md-3">
						  {!! Form::select('year', $years, null, [
									'class' => 'form-control',
									'required'
						  ]) !!} 
					</div>
					<div class="col-md-2">
						{!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success']) !!}
					</div>
					{!!Form::close()!!}
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection

@section('js')

<script src="{{ asset('js/ajax.js') }}"></script>
	@foreach($dptos as $dpto)
		<script type="text/javascript">
		  $(function() {
		    $( "#{{$dpto->code.'inicial'}}" ).datepicker();
		  });
		  </script>
		<script>
		$.datepicker.setDefaults($.datepicker.regional['es-MX']);
		$('#{{$dpto->code.'inicial'}}').datepicker({
		    dateFormat: 'dd/mm/yy',
		    changeMonth: true,
		    changeYear: true,
		    firstDay: 1,
		    onClose: function () {
		        $('#{{$dpto->code.'final'}}').val(this.value);
		    }
		});
		$('#{{$dpto->code.'final'}}').datepicker({
		    dateFormat: 'dd/mm/yy',
		    changeMonth: true,
		    changeYear: true,
		    firstDay: 1
		});
		</script>
	@endforeach
@endsection
