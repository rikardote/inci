@php
    $currentNumEmpleado = null;
    $employeeData = [];

    // Primero agrupamos las incidencias por empleado
    foreach($incidencias as $inc) {
        if (!isset($employeeData[$inc->num_empleado])) {
            $employeeData[$inc->num_empleado] = [
                'nombre' => ($inc->father_lastname ?? '') . ' ' . ($inc->mother_lastname ?? '') . ' ' . ($inc->name ?? ''),
                'incidencias' => []
            ];
        }
        $employeeData[$inc->num_empleado]['incidencias'][] = $inc;
    }
@endphp

@foreach($employeeData as $numEmpleado => $data)
    @php $firstRow = true; @endphp
    @foreach($data['incidencias'] as $incidencia)
        <tr>
            @if($firstRow)
                <td align="center" rowspan="{{ count($data['incidencias']) }}">{{ $numEmpleado }}</td>
                <td rowspan="{{ count($data['incidencias']) }}">{{ $data['nombre'] }}</td>
                @php $firstRow = false; @endphp
            @endif
            <td align="center">{{ str_pad($incidencia->code ?? '', 2, '0', STR_PAD_LEFT) }}</td>
            <td align="center">{{ fecha_dmy($incidencia->fecha_inicio ?? '') }}</td>
            <td align="center">{{ fecha_dmy($incidencia->fecha_final ?? '') }}</td>
            <td align="center">{{ isset($incidencia->periodo) ? $incidencia->periodo . '/' . $incidencia->periodo_year : '-' }}</td>
            <td align="center">{{ $incidencia->total_dias ?? '' }}</td>
        </tr>
    @endforeach
@endforeach
