<<<<<<< HEAD
​<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        table-layout: fixed;
        width: 100%;
    }

    td,
    th {
        border: 0px solid #dddddd;
        padding: 8px;
        width: 15%;
    }
</style>

@foreach ($empleados as $empleado)
    <table>
        <thead>
            <tr>
                <td colspan="5" style="border:0px">{{ $empleado->num_empleado }}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ $empleado->father_lastname }} {{ $empleado->mother_lastname }} {{ $empleado->name }}
                </td>
                <td colspan="7" align="right" style="border:0px">
                    HORARIO:&nbsp;&nbsp; {{ $empleado->horario }}
                </td>
            </tr>
        </thead>
        <tbody>
            <tr style="border:1px solid #dddddd">
                @foreach ($daterange as $date)
                    {{-- */ $entrada = check_entrada($date->format("Y-m-d"), $empleado->num_empleado) /* --}}
                    {{-- */ $salida =  check_salida($date->format("Y-m-d"), $empleado->num_empleado, $entrada) /* --}}
                    @if (!isweekend($date->format('Y-m-d')))
                        <td style="border:1px solid #dddddd" align="center"> {{ getDia($date->format('Y-m-d')) }}
                            <h6> {{ $entrada }} -
                                @if ($entrada != $salida)
                                    {{ $salida }}
                            </h6>
                    @endif
                    </td>
                @endif
@endforeach
</tr>
</tbody>
</table>
<br>
=======
<style>
    table {
        font-family: Arial, sans-serif;
        width: 100%;
        margin-bottom: 6px;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #dddddd;
        padding: 4px;
        text-align: center;
        font-size: 10px;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .employee-header td {
        background-color: #f2f2f2;
        font-size: 11px;
        font-weight: bold;
        padding: 2px;
        border: none;
        border-bottom: 1px solid #dddddd;
        min-height: 20px; /* Fuerza una altura mínima */
    }
</style>

@foreach ($empleados as $grupo)
    @php
        $empleado = $grupo->first(); // Obtener el primer registro del grupo
    @endphp
    <table style="width: 100%;">
        <!-- Cabecera del empleado -->
        <tr class="employee-header">
            <td colspan="{{ $daterange->filter(function($date) { return !in_array($date->format('w'), [0, 6]); })->count() }}">
                <table style="width: 100%; border-collapse: collapse; background-color: transparent; border: 0px;">
                    <tr>
                        <td style="text-align: left; font-weight: bold; font-size: 11px;">
                            {{ $empleado->num_empleado }} - {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }} {{ $empleado->nombre }}
                        </td>
                        <td style="text-align: right; font-weight: bold; font-size: 11px;">
                            Horario: {{ $empleado->horario_entrada }} - {{ $empleado->horario_salida }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Encabezado de días -->
        <tr>
            @foreach ($daterange->filter(function($date) { return !in_array($date->format('w'), [0, 6]); }) as $date)
                <th style="width: {{ 100 / $daterange->filter(function($date) { return !in_array($date->format('w'), [0, 6]); })->count() }}%;">
                    {{ ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'][$date->format('w')] }}<br>
                    {{ $date->format('d') }}
                </th>
            @endforeach
        </tr>

        <!-- Fila de asistencia -->
        <tr>
            @foreach ($daterange->filter(function($date) { return !in_array($date->format('w'), [0, 6]); }) as $date)
                @php
                    $registro = $grupo->filter(function($item) use ($date) {
                        return $item->fecha === $date->format('Y-m-d');
                    })->first();
                @endphp
                <td style="width: {{ 100 / $daterange->filter(function($date) { return !in_array($date->format('w'), [0, 6]); })->count() }}%;">
                    @if ($registro)
                        {{ $registro->hora_entrada ? date('H:i', strtotime($registro->hora_entrada)) : '' }} -
                        {{ $registro->hora_salida ? date('H:i', strtotime($registro->hora_salida)) : '' }}
                    @else
                        - -
                    @endif
                </td>
            @endforeach
        </tr>
    </table>
>>>>>>> origin/reporte-diario
@endforeach
