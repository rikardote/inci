{!! Form::model($employe, [
		'method' => $employe->exists ? 'put' : 'post', 
		'route' => $employe->exists ? ['employees.update', $employe->num_empleado] : ['employees.store']

		]) !!}
		  
      @include('admin.employees._form')
   
{!! Form::close() !!}
<script>
    $("#lactancia_inicio").flatpickr({
        enableTime: false,
        allowInput: true,
        dateFormat: "d/m/Y",
        locale: 'es',
    });
    $("#lactancia_fin").flatpickr({
        enableTime: false,
        allowInput: true,
        dateFormat: "d/m/Y",
        locale: 'es',
    });
</script>

<script>
    if(document.querySelector('#lactancia').checked) {
        $('#lactancia_well').show();
      } else {
        $('#lactancia_well').hide();
      }

    $('#lactancia').on('change', function() {

      if(document.querySelector('#lactancia').checked) {
        $('#lactancia_well').show();
      } 
      else {
        $('#lactancia_well').hide();
      }
    });
    
  </script>

@if($employe->exists)
	<script>
		$('#dob').datetextentry('set_date', '{{$employe->fecha_ingreso}}');
	</script>
@else
	<script>
		$('#dob').datetextentry();
	</script>
@endif
