@extends('layout.main')

@section('content')
<table class="table table-striped">
		<tr>
			{!! Form::open(['route' => ['reports.inasistencias.get'], 'method' => 'POST']) !!}	
			<td><strong>Reporte de Inasistencias</strong></td>
			<td>
				
				<div class="form-group">
					 {!! Form::text('fecha_inicio', null, [
					    'class' => 'form-control',
					    'placeholder' => 'Fecha Inicial', 
					    'required',
					    'id' => 'datepicker_inicial1'
					  ]) !!}
				</div>
		
						  
				  {!! Form::text('fecha_final', null, [
				   'class' => 'form-control',
				   'placeholder' => 'Fecha Final', 
				   'required',
				   'id' => 'datepicker_final1-2'
				  ]) !!}
				  <br>
				{!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success pull pull-right']) !!}

				
			</td>
			{!!Form::close()!!}
		</tr>

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
		    onClose: function () {
		        $('#datepicker_final1-2').val(this.value);
		    }
		});
		$('#datepicker_final1-2').datepicker({
		    dateFormat: 'dd/mm/yy',
		    changeMonth: true,
		    changeYear: true,
		    firstDay: 1
		});
	</script> 
@endsection