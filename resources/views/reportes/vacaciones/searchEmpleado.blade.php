<div class="col-md-5">
		{!! Form::open(['route' => 'reportes.vacaciones.search', 'method' => 'post', 'id' => 'myForm']) !!}
					<div class="form-group">

							<div class="input-group">
						      <input id="auto" type="text" name="num" class="form-control" placeholder="Buscar por numero de empleado">
						      <span class="input-group-btn">
						        <button class="btn btn-success" type="submit"><span class="fa fa-search"></span></button>
						      </span>
						    </div><!-- /input-group -->
					</div>
		{!! Form::close() !!}

	
</div>



@if(isset($noencontrado))
		<div class="alert alert-warning" align="center">
			<h4>{!! $noencontrado !!}</h4>
		</div>
@endif


