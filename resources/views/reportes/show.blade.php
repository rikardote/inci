@extends('layout.main')

@section('title', $title)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection

@section('content')

<div class="social">
    <ul>
        <li><a href="{{ route('reporte.pdf', [$qna->id, $dpto->code]) }}" class="icon-pdf"><i class="fa-solid fa-file-pdf fa-1x"> Generar RH-5</i></a></li>
    </ul>
</div>

<table class="table table-condensed table-bordered">
    <thead class="thead-dark">
        <tr class="text-center">
            <th class="text-center">Num Empleado</th>
            <th>Empleado</th>
            <th class="text-center">Codigo</th>
            <th class="text-center">Fecha Inicial</th>
            <th class="text-center">Fecha Final</th>
            <th class="text-center">Periodo</th>
            <th class="text-center">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($groupedIncidencias as $numEmpleado => $data)
            @php $firstRow = true; @endphp
            @foreach($data['incidencias'] as $incidencia)
                <tr>
                    @if($firstRow)
                        <td align="center" rowspan="{{ count($data['incidencias']) }}">{{ $numEmpleado }}</td>
                        <td rowspan="{{ count($data['incidencias']) }}">{{ $data['empleado'] }}</td>
                        @php $firstRow = false; @endphp
                    @endif
                    <td align="center">{{ $incidencia['codigo'] }}</td>
                    <td align="center">{{ $incidencia['fecha_inicio'] }}</td>
                    <td align="center">{{ $incidencia['fecha_final'] }}</td>
                    <td align="center">{{ $incidencia['periodo'] }}</td>
                    <td align="center">{{ $incidencia['total_dias'] }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

@endsection
