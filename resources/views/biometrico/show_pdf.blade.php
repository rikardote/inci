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
@endforeach
