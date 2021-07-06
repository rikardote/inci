<div class="col-md-6">
	<div class="form-group">
		{!! Form::label('horario', 'Horario Entrada') !!}
		{!! Form::text('horario_entrada', null, [
			'id' => 'entrada',
			'class' => 'form-control',
			'placeholder' => 'Horario Entrada', 
			'required'
		]) !!}
	</div>
</div>
<div class="col-md-6">
	<div class="form-group">
		{!! Form::label('horario_salida', 'Horario Salida') !!}
		{!! Form::text('horario_salida', null, [
			'id' => 'salida',
			'class' => 'form-control',
			'placeholder' => 'Horario Salida', 
			'required'
		]) !!}
	</div>
</div>
	
<div align="right">
	{!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
</div>


<script>
$(function() {
	$('#entrada').timepicker();
	$('#salida').timepicker();
});
</script>