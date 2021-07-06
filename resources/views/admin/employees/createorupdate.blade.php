{!! Form::model($employe, [
		'method' => $employe->exists ? 'put' : 'post', 
		'route' => $employe->exists ? ['employees.update', $employe->num_empleado] : ['employees.store']

		]) !!}
		  
      @include('admin.employees._form')
   
{!! Form::close() !!}

@if($employe->exists)
	<script>
		$('#dob').datetextentry('set_date', '{{$employe->fecha_ingreso}}');
	</script>
@else
	<script>
		$('#dob').datetextentry();
	</script>
@endif