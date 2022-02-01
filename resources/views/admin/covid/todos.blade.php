@extends('layout.main')

@section('title', 'REPORTE COVID GENERAL')

@section('content')

<table class="table table-hover table-condensed" style="width:100%;" id="myTable" >
		<thead>
			<th>Num Empleado</th>
			<th>Empleado</th>
			<th>Codigo</th>
			<th>Adscripcion</th>
            <th>Centro</th>
			<th>Fecha Inicial</th>
			<th>Fecha Final</th>
			<th>Total</th>
		</thead>
		<tbody>
		{{--*/ $tmp = "" /*--}}
		@foreach($incidencias as $incidencia)
			 <tr id="tr_table" class="no-table">
					<tr>
						<td align=center>{{ $incidencia->num_empleado }}</td>
						 <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</td>
						 <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
						 <td align=center>{{ $incidencia->depa_code}}</td>
                         <td align=center>{{ $incidencia->depa_description}}</td>
						 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
						 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
						 <td align=center>{{ $incidencia->total }}</td>
					</tr>
		@endforeach
		</tbody>
	</table>
@endsection
@section('js')
    <script>
        function eliminar_inc(btn, r) {
            var i = r.parentNode.parentNode.rowIndex;
            //var route = "http://incidencias.app/incidencias_del/"+btn;
            //var route = "http://192.168.1.95/incidencias/incidencias_del/"+btn;
            //var route = "http://192.161.59.137/incidencias/incidencias_del/"+btn;
            var route = "http://incidencias.slyip.com/incidencias_del/"+btn;
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
@endsection
