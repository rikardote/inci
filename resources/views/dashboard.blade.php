@extends('layout.main')

@section('title', 'Dashboard')

@section('content')
	<section align="center">
	  <div class="container">
	    <div class="row">
	        <div class="col-sm-4 col-xs-6">
	        	<a href="{{route('users.index')}}"><i class="fa fa-users fa-4x"></i><br>USUARIOS</a>
			</div>
			<!--
	        <div class="col-sm-4 col-xs-6">
	        	<a href="{{route('admin.capturar.index')}}"><i class="fa fa-pencil-square-o fa-4x"></i><br>CAPTURAR A META-4</a>
			</div>
			-->
	        <div class="col-sm-4 col-xs-6">
	        	<a href="{{route('admin.ausentismo')}}"><i class="fa fa-bar-chart fa-4x"></i><br>AUSENTISMO</a>
	        </div>
	        <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('deparments.index')}}"><i class="fa fa-list-alt fa-4x"></i><br>DEPARTAMENTOS</a>
	        </div>
	        <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('codigosdeincidencias.index')}}"><i class="fa fa-th-list fa-4x"></i><br>CODIGOS DE INCIDENCIA</a>
	        </div>
	        <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('periodos.index')}}"><i class="fa fa-plane fa-4x"></i><br>PERIODOS VACACIONALES</a>
	        </div>
	        <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('puestos.index')}}"><i class="fa fa-briefcase fa-4x"></i><br>PUESTOS</a>
	        </div>
	        <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('horarios.index')}}"><i class="fa fa-clock-o fa-4x"></i><br>HORARIOS</a>
	        </div>
	       <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('qnas.index')}}"><i class="fa fa-calendar-check-o fa-4x"></i><br>QNAS</a>
	        </div>
	        <div class="col-sm-4 col-xs-6 divpadding">
	        	<a data-url="{{ route('fechadecierre.create') }}" class="load-form-modal" data-toggle ="modal" data-target='#form-modal'"><i class="fa fa-calendar fa-4x"></i><br>FECHA DE CIERRE</a>
	        </div>
	        <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('logs.index')}}"><i class="fa fa-clipboard fa-4x"></i><br>Logs</a>
	        </div>
	    
	        <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('biometrico.index')}}"><i class="fa fa-cloud-download fa-4x"></i><br>Actualizar Registros de Biometrico</a>
	        </div>
	        <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('biometrico.get_checadas')}}"><i class="fa fa-hand-o-up fa-4x"></i><br>Ver Checadas</a>
			</div>
            <div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('biometrico.asignar')}}"><i class="fa fa-id-badge fa-4x"></i><br>.</a>
			</div>
			<div class="col-sm-4 col-xs-6 divpadding">
	        	<a href="{{route('mantenimiento.show')}}"><i class="fa fa-cog fa-4x"></i><br>Mantenimiento</a>
	        </div>
	    
	      </div>
	      
	    </div>
	 </section>
	 @include('modals.form-modal', ['title'=>'Agregar Fecha de cierre'])
@endsection
