	{!! Form::open(['route' => 'empleados.search', 'method' => 'post', 'id' => 'myForm']) !!}
	
		<div class="form-group">

				<div class="input-group">
			      <input id="auto", type="text" name="num" class="form-control" placeholder="Buscar por numero de empleado">
			      <span class="input-group-btn">
			        <button class="btn btn-success" type="submit"><span class="fa fa-search"></span></button>
			      </span>
			    </div><!-- /input-group -->

			</div>

	{!! Form::close() !!}
	

	@if(isset($noencontrado))
		<div align="center" class="alert alert-warning">
			<strong><i class='fa fa-exclamation-triangle'></i> Atencion!</strong>
			<br>
		  {!!$noencontrado!!}
		</div>  
	@endif

