<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', 'Default')</title>
	
	<link rel="stylesheet" href="{{ asset('css/bootswatch/paper.css') }}"> 
	<link rel="stylesheet" href="{{ asset('css/jquery-bootstrap-datepicker.css') }}">
	<link rel="stylesheet" href="{{ asset('css/bootstrap-chosen.css') }}">

	<link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/datetextentry/datetextentry.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/sweetalert/dist/sweetalert.css') }}">

	<link rel="stylesheet" href="{{ asset('plugins/chosen/chosen.css') }}">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">

	<link rel="stylesheet" href="{{ asset('css/card.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/toastr/css/toastr.min.css') }}">
	<link rel="stylesheet" href="{{ asset('plugins/jquery/css/themes/smothness/jquery-ui.css') }}">

	@yield('css')
	
</head>
<body>

		<!--NAVEGACION PARTIAL-->
		<div class="supreme-container">
		 	@include('layout._navbar')
		</div>
		<div class="container">

			<!--CONTENIDO-->
			@if(check_manto())
				<div class="panel panel-warning">
			@else
				<div class="panel panel-primary">
			@endif
	  			<div class="panel-heading supreme-container">
	    			<h3 class="panel-title">@yield('title')</h3>
	  			</div>
	  			<div class="panel-body">
	  				<div id="msj-success">
	  					@include('flash::message')
	  					{!! Toastr::render() !!}
					</div>
					@if(check_manto() && !\Auth::user()->admin())
					 	@include('admin.mantenimiento.index')
					@else
					 	@yield('content')
					@endif
	  			</div>
			</div>
			<div class="noprint">
				<p class="text-muted text-white"> Para soporte comunicarse a la ext. 53033 con: </p>
				<p class="text-muted text-white"> Lic. Dulce Tania Ramirez Coss - Jefa de Incidencias</p>
				<p class="text-muted text-white"> Lic. Maria Altagracia Jimenez de la Torre - Area de Captura</p>
				<p class="text-muted text-white"> {{date('Y')}} ISSSTE BAJA CALIFORNIA Creado Por: &copy; Hector Ricardo Fuentes Armenta Ext. 53040</p>
			</div>
		</div>
	
<script src="{{ asset('plugins/jquery/js/jquery.js') }}"></script>
<script src="{{ asset('plugins/datepicker/js/jquery-ui.min.js') }}"></script> 
<script src="{{ asset('plugins/datepicker/js/ui.datepicker-es-MX.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>
<script src="{{ asset('plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('plugins/datetextentry/datetextentry.js') }}"></script>
<script src="{{ asset('plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>


@yield('js')

</body>
</html>