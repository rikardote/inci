<style>
    table {
        font-family: Arial, sans-serif;

        width: 100%;
        margin-bottom: 6px;
    }

    th,
    td {
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
        border: 0px solid #cccccc;
    }


    /* Estilos para colocar nombre y horario en extremos */

</style>

@foreach ($empleados as $empleado)
@php
$diasHabiles = collect($daterange)->filter(function ($date) {
return !in_array($date->format('w'), [0, 6]);
});
@endphp

<table style="width: 100%; border: 8px;">
    <!-- Cabecera del empleado -->
    <tr class="employee-header">
        <td colspan="{{ $diasHabiles->count() }}">
            <table style="width: 100%; border-collapse: collapse; background-color: transparent; border: 0px;">
                <tr>
                    <td style="text-align: left; font-weight: bold; font-size: 11px;">
                        {{ $empleado->num_empleado }} - {{ $empleado->father_lastname }} {{ $empleado->mother_lastname }} {{
                        $empleado->name }}
                    </td>
                    <td style="text-align: right; font-weight: bold; font-size: 11px;">
                        Horario: {{ $empleado->horario }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Encabezado de días -->
    <tr>
        @foreach ($diasHabiles as $date)
        <th style="width: {{ 100 / $diasHabiles->count() }}%;">
            {{ ['Lun','Mar','Mie','Jue','Vie'][$date->format('w') - 1] }}<br>
            {{ $date->format('d') }}
        </th>
        @endforeach
    </tr>

    <!-- Fila de asistencia -->
    <tr>
        @foreach ($diasHabiles as $date)
        .fixed-width {
            width: {{ 100 / $diasHabiles->count() }}%;
            }
        @php
        $entrada = check_entrada($date->format("Y-m-d"), $empleado->num_empleado);
        $salida = check_salida($date->format("Y-m-d"), $empleado->num_empleado, $entrada);
        @endphp
        <td style="width: {{ 100 / $diasHabiles->count() }}%;">{{ $entrada ?? '-' }}-{{ $salida ?? '-' }}</td>
        @endforeach
    </tr>
</table>
@endforeach
