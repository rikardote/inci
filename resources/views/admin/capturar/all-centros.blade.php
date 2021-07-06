@extends('layout.main')

@section('title', $title)

@section('content')
<a href="{{route('admin.capturar.centro', [$qna_id])}}"><span class="fa fa-chevron-circle-left"> Regresar</span></a>
<ul class="nav nav-pills nav-center">
  <li><a href="{{route('admin.capturar.grupo', [$qna_id, $dpto_code, 100])}}"><span class="badge">Incidencias</span></a></li>
  <li><a href="{{route('admin.capturar.grupo', [$qna_id, $dpto_code, 200])}}"><span class="badge">Licencias/Permisos</span></a></li>
  <li><a href="{{route('admin.capturar.grupo', [$qna_id, $dpto_code, 300])}}"><span class="badge">Vacaciones</span></a></li>
  <li><a href="{{route('admin.capturar.grupo', [$qna_id, $dpto_code, 400])}}"><span class="badge">Otros</span></a></li>

  <li class="pull pull-right"><a href="{{route('admin.capturar.capturar_centro', [$qna_id, $dpto_code])}}"><span class="badge">Todos</span></a></li>
</ul>

    @if(isset($incidencias))

    <table class="table table-hover table-condensed" id="myTable">
        <thead>
            
            <th>Num Empleado</th>
            <th>Empleado</th>
            <th>Codigo</th>
            <th>Fecha Inicial</th>
            <th>Fecha Final</th>
            <th>Periodo</th>
            <th>Total</th>
            <th>Capturado</th>

        </thead>
        <tbody>
        {{--*/ $tmp = "" /*--}}
        @foreach($incidencias as $incidencia)
            <tr id="tr_table" class="no-table">
                @if($incidencia->num_empleado == $tmp)
                
                    <td></td>
                    <td></td>
                     <td align=center>{{ $incidencia->code }}</td>
                     <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
                     <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
                     
                     @if(isset($incidencia->periodo))
                             <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
                     @else
                                <td></td>
                     @endif
                     <td align=center>{{ $incidencia->total_dias }}</td>
                     <td align=center>
                        @if(!$incidencia->capturada)
                            <button href="#" <span class="btn btn-danger  btn-xs fa fa-check" onclick="deleteRow(this, <?=$incidencia->inc_id?>)"></span></button>
                        @endif
                        @if($incidencia->capturada)
                            <button href="#" <span class="btn btn-info btn-xs fa fa-check" onclick="activateRow(this, {{$incidencia->inc_id}})"></span></button>
                         @endif

                     </td>
                </tr>
                
                    
                @else

                    <tr>
                        <td align=center>{{ $incidencia->num_empleado }}</td>
                         <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</td>
                         <td align=center>{{ $incidencia->code }}</td>
                         <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
                         <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
                         
                         @if(isset($incidencia->periodo))
                                 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
                         @else 
                                    <td></td>
                         @endif
                         <td align=center>{{ $incidencia->total }}</td>
                         <td align=center>
                         @if(!$incidencia->capturada)
                            <button href="#" <span class="btn btn-danger btn-xs fa fa-check" onclick="deleteRow(this, <?=$incidencia->inc_id?>)"></span></button>
                         @endif
                         @if($incidencia->capturada)
                            <button href="#" <span class="btn btn-info btn-xs fa fa-check" onclick="activateRow(this, <?=$incidencia->inc_id?>)"></span></button>
                         @endif
                         </td>
                    </tr>
                    {{--*/ $tmp = $incidencia->num_empleado /*--}}
                @endif
        @endforeach
        </tbody>
    </table>
    @endif
 
@endsection

@section('js')
    <script>
        function deleteRow(r, inc_id) {
            var i = r.parentNode.parentNode.rowIndex;
            //var route = "http://192.161.59.137/incidencias/capturar/"+inc_id+"/capturado";
            //var route = "http://192.168.1.95/incidencias/capturar/"+inc_id+"/capturado";
            //var route = "http://sissstema.com/capturar/"+inc_id+"/capturado";
            //var route = "http://incidencias.app/capturar/"+inc_id+"/capturado";
            var route = "http://risharwork.slyip.com/capturar/"+inc_id+"/capturado";
            var token = $("#token").val();
            $.ajax({
                url: route,
                headers: {'X-CSRF-TOKEN': token},
                type: 'GET',
                success: function(res) {
                    console.log(res);
                    document.getElementById("myTable").deleteRow(i);
                }
            });
            
        }
    </script>
    <script>
        function activateRow(r, inc_id) {
            var i = r.parentNode.parentNode.rowIndex;
            //var route = "http://192.161.59.137/incidencias/capturar/"+inc_id+"/capturado";
            //var route = "http://192.168.1.95/incidencias/capturar/"+inc_id+"/capturado";
            //var route = "http://sissstema.com/capturar/"+inc_id+"/capturado";
            //var route = "http://incidencias.app/capturar/"+inc_id+"/capturado";
            var route = "http://risharwork.slyip.com/capturar/"+inc_id+"/capturado";
            
            var token = $("#token").val();
            $.ajax({
                url: route,
                headers: {'X-CSRF-TOKEN': token},
                type: 'GET',
                success: function(res) {
                    console.log(res);
                    
                }
            });
            
        }
    </script>
@endsection