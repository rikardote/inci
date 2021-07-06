@extends('layout.main')
	@section('title', 'Ausentismo')

	@section('content')
	<table class="table table-striped">
		<tr>
			{!! Form::open(['route' => ['admin.ausentismo.delegacion'], 'method' => 'POST']) !!}	
			<td><strong>Ausentimo por Delegacion</strong></td>
			<td>
				
				<div class="form-group">
					 {!! Form::text('fecha_inicio', null, [
					    'class' => 'form-control',
					    'placeholder' => 'Fecha Inicial', 
					    'required',
					    'id' => 'datepicker_inicial1',
					    'autocomplete' => 'off'
					  ]) !!}
				</div>
		
						  
				  {!! Form::text('fecha_final', null, [
				   'class' => 'form-control',
				   'placeholder' => 'Fecha Final', 
				   'required',
				   'id' => 'datepicker_final1-2',
				   'autocomplete' => 'off'
				  ]) !!}
				  <br>
				{!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success pull pull-right']) !!}

				
			</td>
			{!!Form::close()!!}
		</tr>

		<tr>
			{!! Form::open(['route' => ['admin.ausentismo.centro'], 'method' => 'POST']) !!}	
			<td><strong>Ausentimo por Centro</strong></td>
			<td>
				<div class="form-group">
					
					{!! Form::select('deparment_id', $deparments, null, [
						'class' => 'form-control',
						'placeholder' => 'Selecciona un departamento', 
						'required'
					]) !!}
				</div>
				<div class="form-group">
					 {!! Form::text('fecha_inicio', null, [
					    'class' => 'form-control',
					    'placeholder' => 'Fecha Inicial', 
					    'required',
					    'id' => 'datepicker_inicial2',
					    'autocomplete' => 'off'
					  ]) !!}
				</div>
				
		
						  
				  {!! Form::text('fecha_final', null, [
				   'class' => 'form-control',
				   'placeholder' => 'Fecha Final', 
				   'required',
				   'id' => 'datepicker_final2-2',
				   'autocomplete' => 'off'
				  ]) !!}
				  <br>
				  {!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success pull pull-right']) !!}
			
				
				
			</td>
			{!!Form::close()!!}
		</tr>

		<tr>
			{!! Form::open(['route' => ['admin.ausentismo.empleado'], 'method' => 'POST']) !!}	
			<td><strong>Ausentimo por Empleado</strong></td>
			<td>
				<div class="form-group">
					 {!! Form::text('num_empleado', null, [
					    'class' => 'form-control',
					    'placeholder' => 'Numero de Empleado', 
					    'required'
					  ]) !!}
				</div>
				<div class="form-group">
					 {!! Form::text('fecha_inicio', null, [
					    'class' => 'form-control',
					    'placeholder' => 'Fecha Inicial', 
					    'required',
					    'id' => 'datepicker_inicial3',
					    'autocomplete' => 'off'
					  ]) !!}
				</div>
				
		
						  
				  {!! Form::text('fecha_final', null, [
				   'class' => 'form-control',
				   'placeholder' => 'Fecha Final', 
				   'required',
				   'id' => 'datepicker_final3-2',
				   'autocomplete' => 'off'
				  ]) !!}
				<br>
				{!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success pull pull-right']) !!}
				@if(isset($error))
				<div align="center" class="alert alert-warning">
					<strong><i class='fa fa-exclamation-triangle'></i> Atencion!</strong>
					<br>
		  			{!!$error!!}
				</div>  
			@endif
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

		<script type="text/javascript">
		  $(function() {
		    $( "#datepicker_inicial2" ).datepicker();
		  });
		  </script>
		<script>
		$.datepicker.setDefaults($.datepicker.regional['es-MX']);
		$('#datepicker_inicial2').datepicker({
		    dateFormat: 'dd/mm/yy',
		    changeMonth: true,
		    changeYear: true,
		    firstDay: 1,
		    onClose: function () {
		        $('#datepicker_final2-2').val(this.value);
		    }
		});
		$('#datepicker_final2-2').datepicker({
		    dateFormat: 'dd/mm/yy',
		    changeMonth: true,
		    changeYear: true,
		    firstDay: 1
		});
		</script>

		<script type="text/javascript">
		  $(function() {
		    $( "#datepicker_inicial3" ).datepicker();
		  });
		  </script>
		<script>
		$.datepicker.setDefaults($.datepicker.regional['es-MX']);
		$('#datepicker_inicial3').datepicker({
		    dateFormat: 'dd/mm/yy',
		    changeMonth: true,
		    changeYear: true,
		    firstDay: 1,
		    onClose: function () {
		        $('#datepicker_final3-2').val(this.value);
		    }
		});
		$('#datepicker_final3-2').datepicker({
		    dateFormat: 'dd/mm/yy',
		    changeMonth: true,
		    changeYear: true,
		    firstDay: 1
		});
		</script> 
	@endsection

