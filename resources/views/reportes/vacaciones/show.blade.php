@extends('layout.main')

@section('title', 'Vacaciones')

@section('css')
	<link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection

@section('content')

    @if(isset($incidencias))

    <table class="table table-hover table-condensed">
        <thead>

            <th class="text-center">Num Empleado</th>
            <th>Empleado</th>
            <th class="text-center">Codigo</th>
            <th class="text-center">Periodo</th>
            <th class="text-center">Total</th>
        </thead>
        <tbody>
        {{--*/ $tmp = "" /*--}}
        @foreach($incidencias as $incidencia)
            <tr class="no-table">
                @if($incidencia->num_empleado == $tmp)

                    <td></td>
                    <td></td>
                     <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
                     <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->year }}</td>
                     <td align=center>{{ $incidencia->total}}</td>
            </tr>

                @else
                    <tr>
                        <td align=center>{{ $incidencia->num_empleado }}</td>
                         <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</td>
                         <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
                         <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->year }}</td>
                         <td align=center>{{ $incidencia->total }}</td>

                    </tr>
                    {{--*/ $tmp = $incidencia->num_empleado /*--}}
                @endif
        @endforeach
        </tbody>
    </table>
    @endif
@endsection
