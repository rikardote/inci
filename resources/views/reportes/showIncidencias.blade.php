 {{$empleado->num_empleado}} - {{$empleado->father_lastname}} {{$empleado->mother_lastname}} {{$empleado->name}}
 <table class="table table-striped center">
    <thead>
            <tr>
                <th>Codigo</th>
                <th>Qna</th>
                <th>Fecha Inicial</th>
                <th>Fecha Final</th>
                <th>Total</th>
            </tr>
    </thead>
    <tbody>
           
        @foreach ($incidencias as $incidencia)
            <tr>
                @if (in_array($incidencia->code, $inc2))
                    <td class="center"><strong>{{str_pad($incidencia->code,'2','0',STR_PAD_LEFT )}}</strong></td>
                    <td class="center">{{$incidencia->qna}}/{{$incidencia->qna_year}}</td>    
                    <td class="center">{{fecha_dmy($incidencia->fecha_inicio)}}</td>
                    <td class="center">{{fecha_dmy($incidencia->fecha_final)}}</td>
                    <td class="center">{{$incidencia->total_dias}}</td>
                @endif
            </tr> 
        @endforeach
    </tbody>             
       
</table>