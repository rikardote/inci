	{!! Form::open(['route' => 'empleados.search', 'method' => 'post', 'id' => 'myForm']) !!}
		<div class="form-group">
			<div class="col-md-4">
				<div class="input-group">
			      <input id="auto" type="text" name="num" class="form-control" placeholder="Buscar por numero de empleado">
			      
			      @if(isset($qna_id))
			      	{{ Form::hidden('qna_id', $qna_id) }}
			      @endif
			      @if(isset($qna->id))
			      	{{ Form::hidden('qna_id', $qna->id) }}
			      @endif

			      <span class="input-group-btn">
			        <button class="btn btn-success" type="submit"><span class="fa fa-search"></span></button>
			      </span>
			    </div><!-- /input-group -->

			</div>
		</div>
	{!! Form::close() !!}

	<br>