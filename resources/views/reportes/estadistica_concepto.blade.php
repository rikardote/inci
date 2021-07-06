@extends('layout.main')

@section('title', 'Reporte Estadistico por concepto')

@section('content')

{!! Form::open(['route' => ['reports.estadistica_concepto'], 'method' => 'POST']) !!}	
<table class="table table-striped">
	<thead>
		<th>Codigo</th>
		<th>Fecha Inicial</th>
		<th>Fecha Final</th>
	</thead>
	<tbody>
		<tr>
			<td>
				<div class="form-group">
						 {!! Form::text('code', null, [
						    'class' => 'form-control',
						    'placeholder' => 'Codigo', 
						    'required'
						  ]) !!}
				</div>
				
			</td>
			<td>
				
				<div class="form-group">
					 {!! Form::text('fecha_inicio', null, [
					    'class' => 'form-control',
					    'placeholder' => 'Fecha Inicial', 
					    'required',
					    'id' => 'datepicker_inicial1'
					  ]) !!}
				</div>
			</td>
			<td>
		
				<div class="form-group">
				  {!! Form::text('fecha_final', null, [
				   'class' => 'form-control',
				   'placeholder' => 'Fecha Final', 
				   'required',
				   'id' => 'datepicker_final1-2'
				  ]) !!}
				 </div>
			</td>
		</tr>
 </tbody>
	<div class="form-group"> {!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success pull pull-right']) !!} </div>
</table>
{!!Form::close()!!}
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