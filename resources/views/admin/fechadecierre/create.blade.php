	{!! Form::open(['route' => 'fechadecierre.update', 'method' => 'PUT']) !!}
	<div class="form-group">
		{!! Form::label('qna_id', 'Quincena') !!}
		 <select class="form-control" id="qna_id" name="qna_id">
		    <option value="">Selecciona una Qna</option>
		    @foreach($qnas as $qna)
		      <option value="{{$qna->id}}">{{$qna->qna}}/{{$qna->year}} {{$qna->description}} </option>
		    @endforeach
		  </select>  
	</div>	
	<div class="form-group">
	  {!! Form::label('fecha_inicio', 'Fecha de Cierre') !!}
	  {!! Form::text('fecha_inicio', null, [
	    'class' => 'form-control',
	    'placeholder' => 'Fecha Cierre', 
	    'required',
	    'id' => 'datepicker_inicial',
		'autocomplete' => 'off'
	  ]) !!}

	</div>
	<div class="form-group ">
	  {!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
	</div>
	{!! Form::close() !!}

<script type="text/javascript">
  $(function() {
    $( "#datepicker_inicial" ).datepicker();
  });
  </script>
<script>
$.datepicker.setDefaults($.datepicker.regional['es-MX']);
$('#datepicker_inicial').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    firstDay: 1,
});
  </script>