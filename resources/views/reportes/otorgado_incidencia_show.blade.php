@extends('layout.main')

@section('title', 'Reporte por incidencia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection

@section('content')
    @if (isset($incidencias))

        <table class="table table-hover table-condensed">
            <thead>
                <th>Departamento</th>
                <th>Num Empleado</th>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>Jornada</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>Total dias</th>
                <th>Motivo</th>
            </thead>
            <tbody>
                @foreach ($incidencias as $incidencia)
                    <tr>
                        <td>{{ $incidencia->depa_code }}</td>
                        <td align=center>{{ $incidencia->num_empleado }}</td>
                        <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}
                        </td>
                        <td align=left>{{ $incidencia->puesto }}</td>
                        <td align=left>{{ $incidencia->jornada }}</td>
                        <td>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
                        <td>{{ fecha_dmy($incidencia->fecha_final) }}</td>
                        <td>{{ $incidencia->total_dias }}</td>
                        <td>{{ $incidencia->otorgado }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
