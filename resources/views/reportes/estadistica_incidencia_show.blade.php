@extends('layout.main')

@section('title', 'Reporte por incidencia')

@section('css')
	<link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection

@section('content')
	<div class="social">
     	<ul>
          <li>
           <a href="{{route('reports.estadistica_por_incidencia.pdf',[$dpto,$fecha_inicial, $fecha_final,$code_des->code])}}" class="icon-pdf"><i class="fa fa-file-pdf-o fa-2x "></i></a>
          </li>
           
        </ul>
    </div>
    @if(isset($incidencias))
    
    <table class="table table-hover table-condensed">
        <thead>
            
            <th>Num Empleado</th>
            <th>Empleado</th>
            <th>Puesto</th>
            <th>Jornada</th>
            <th>Codigo</th>
            <th>Total dias</th>
        </thead>
        <tbody>
        {{--*/ $tmp = "" /*--}}
        @foreach($incidencias as $incidencia)
            <tr class="no-table">
                    <tr>
                        <td align=center>{{ $incidencia->num_empleado }}</td>
                        <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</td>
                        <td align=left>{{ $incidencia->puesto }}</td>
                        <td align=left>{{ $incidencia->jornada }}</td>
                        <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
                        <td align=center>{{ $incidencia->total }}</td>
                    </tr>
                    {{--*/ $tmp = $incidencia->num_empleado /*--}}
               
        @endforeach
        </tbody>
    </table>
    @endif
@endsection
