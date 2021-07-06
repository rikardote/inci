@extends('layout.main')
	@section('title', 'Captura de Incidencias / Buscar Empleado')
	
	@section('content')
	
		<div class="col-md-4">
			@include('incidencias.create')
		</div>
		<div class="col-md-8">
			@include('incidencias.side')
		</div>
		
	@endsection

	@section('js')

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
		    onClose: function () {
		        $('#datepicker_final').val(this.value);
		    }
		});
		$('#datepicker_final').datepicker({
		    dateFormat: 'dd/mm/yy',
		    changeMonth: true,
		    changeYear: true,
		    firstDay: 1
		});
		</script> 

	@endsection