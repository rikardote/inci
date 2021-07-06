@extends('layout.main')
@section('title', $title)
@section('content')
<table class="table table-striped">
		<tr>
			{!! Form::open(['route' => ['reports.diario.post'], 'method' => 'POST']) !!}	
			<td><strong>Seleccione fecha de consulta  </strong></td>
			<td>
				
				<div class="form-group col-md-12">
					 {!! Form::text('fecha_inicio', null, [
					    'class' => 'form-control',
					    'placeholder' => 'Fecha Inicial', 
					    'required',
					    'id' => 'datepicker_inicial1'
					  ]) !!}
				</div>
				<div class="form-group col-md-12">
					{!! Form::label('solo_medicos', 'Seleccionar solo medicos: ') !!}
					{!! Form::checkbox('solo_medicos') !!}
		
		</div>
		
				{!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success pull pull-right']) !!}

				
			</td>
			{!!Form::close()!!}
		</tr>
</table>
@endsection

@section('js')
	<script type="text/javascript">
		  $(function() {
		    $( "#datepicker_inicial1" ).datepicker();
		  });
		  </script>
		<script>
		$.datepicker.setDefaults($.datepicker.regional['es-MX']);
		$('#datepicker_inicial1').datepicker({
		    dateFormat: 'dd/mm/yy',
		    changeMonth: true,
		    changeYear: true,
		    firstDay: 1,
		   
		});
		
	</script> 
@endsection