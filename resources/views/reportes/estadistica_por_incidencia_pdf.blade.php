<table style="font-size:8.5pt text-align:center; font-family:family:Arial; width: 100%;  ">
        <thead>
             <tr style="background-color: rgb(171,165,160);  ">
                <td>Num Empleado</td>
                <td>Empleado</td>
                <td>Puesto</td>
                <td>Jornada</td>
                <td>Codigo</td>
                <td>Total</td>
            </tr>
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