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
@endforeach
