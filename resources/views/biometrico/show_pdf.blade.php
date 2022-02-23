​<style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    
    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

</style>

@foreach($empleados as $empleado)
    <table>
        <thead>
            <tr>
                <th colspan="5">{{ $empleado->num_empleado }}  
                    {{ $empleado->father_lastname }} {{ $empleado->mother_lastname }} {{ $empleado->name }} 
                </th>
                <th colspan="7" align="right">
                    {{ $empleado->horario }}
                </th>
            </tr>
        </thead>
        <tr>
            @foreach ( $daterange as $date)
                    {{--*/ $entrada = check_entrada($date->format("Y-m-d"), $empleado->num_empleado) /*--}}
                    {{--*/ $salida =  check_salida($date->format("Y-m-d"), $empleado->num_empleado) /*--}}
                    @if(!isweekend($date->format("Y-m-d")))
                        <td> {{ $date->format("d/m/Y") }}
                            <h6> {{ $entrada }} -
                        @if($entrada != $salida) 
                             {{ $salida }}
                            </h6></td>
                        @endif
                    @endif
            @endforeach
        </tr>    
    </table>
@endforeach
