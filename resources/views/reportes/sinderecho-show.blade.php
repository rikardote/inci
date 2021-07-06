@extends('layout.main')

@section('title', $title)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection

@section('content')

@if(isset($incidencias))
    <div class="social">
        <ul>
            <li><a href="{{route('reporte.sinderecho.pdf', [$dpto, $fecha_inicio, $fecha_final])}}" class="icon-pdf"><i class="fa fa-file-pdf-o fa-2x "></i></a></li>
        </ul>
    </div>
@endif


<div class="supreme-container">
<table class="table " width="100%" border="0px" cellpadding="0" cellspacing="0" bordercolor="#000000">
    <thead>
        <tr>
        	<th>Num Empleado</th>
            <th>Nombre</th>
        </tr>
    </thead>
    <tbody>


        @foreach ($incidencias as $empleado)
                 <tr data-url="{{ route('reports.show_incidenciasEmpleados',[$fecha_inicio, $fecha_final, $empleado->num_empleado]) }}" class="load-form-modal panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
        		   <td>{{$empleado->num_empleado}}</td>	
        		   <td>{{$empleado->father_lastname}} {{$empleado->mother_lastname}} {{$empleado->name}}</td>

		          </tr> 
         @endforeach
    </tbody>	
       
</table>
</div>
@include('modals.form-modal', ['title'=>'Reporte Sin Derecho a nota buena por desempeño'])
@endsection

@section('js')

@endsection