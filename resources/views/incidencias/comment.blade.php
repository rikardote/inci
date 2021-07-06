	{!! Form::model($comentario, [
		'method' => $comentario!=null ? 'put' : 'post', 
		'route' => $comentario!=null ? ['incidencias.update.comment', $empleado_id] : ['incidencias.store.comment',$empleado_id]

		]) !!}

	<div class="form-group">
		{!! Form::label('comment', 'Ingresa Comentario') !!}
		  {!! Form::textarea('comment', null, [
    		'class' => 'form-control',
			'onfocus' => 'clearContents(this);'
    		]) 
    	  !!}
		 
	</div>	
	<div class="form-group">
	{!! Form::submit('Registrar', ['class' => 'btn btn-primary']) !!}
</div>
{!!Form::close()!!}

<script>
function clearContents(element) {
  element.value = ' ';
}
</script>