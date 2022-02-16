@extends('layout.main')

@section('title', $title)

@section('css')
	<link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection

@section('content')

<div class="social">
		<ul>
			<li><a href="{{route('reporte.pdf', [$qna->id, $dpto->code])}}" class="icon-pdf"><i class="fa fa-file-pdf-o fa-1x "> Generar RH-5</i></a></li>
		</ul>
</div>
<!--
<div class="social-left">
		<ul>
			<li><a href="{{route('reporte.pdf.diario', [$qna->id, $dpto->code])}}" class="icon-pdf"><i class="fa fa-file-pdf-o fa-2x "> Diario</i></a></li>
		</ul>
</div>
-->
<table class="table table-hover table-condensed" style="width:100%;" id="myTable" >
		<thead>
			<th>Num Empleado</th>
			<th>Empleado</th>
			<th>Codigo</th>
			<th>Fecha Inicial</th>
			<th>Fecha Final</th>
			<th>Periodo</th>
			<th>Total</th>
			@if (\Auth::user()->admin()) 
				<th>Eliminar/Capturado</th>
			@endif

		</thead>
		<tbody>
		{{--*/ $tmp = "" /*--}}
		@foreach($incidencias as $incidencia)
			 <tr id="tr_table" class="no-table">
				@if($incidencia->num_empleado == $tmp)
				
					<td></td>
					<td></td>
					 <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
					 
					 @if(isset($incidencia->periodo))
							 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
					 @else
					 			<td></td>
					 @endif
					 <td align=center>{{ $incidencia->total_dias }}</td>
					 @if (\Auth::user()->admin()) 
						 <td align=center>
						 	@if(!$incidencia->capturada)
							 	<button OnClick='eliminar_inc({{$incidencia->token}}/{{$incidencia->num_empleado}}/{{$incidencia->qna_id}}/destroy,this);' href="#" <span class="fa fa-times"></span></button>
							@endif
							@if($incidencia->capturada)	
								<input type="checkbox" name="vehicle" disabled value="" {{$incidencia->capturada ? 'checked':'false'}}>
							@endif
						 </td>
					 @endif
				</tr>
				
					
				@else
			
					<tr>
						<td align=center>{{ $incidencia->num_empleado }}  </td>
						 <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}
                        </td>
						 <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
						 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
						 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
						 
						 @if(isset($incidencia->periodo))
								 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
						 @else
						 			<td></td>
						 @endif
						 <td align=center>{{ $incidencia->total }}</td>
						 @if (\Auth::user()->admin()) 
							 <td align=center>
							 	@if(!$incidencia->capturada)
							 		<button OnClick='eliminar_inc("{{$incidencia->token}}/{{$incidencia->num_empleado}}/{{$incidencia->qna_id}}/destroy",this);' href="#" <span class="fa fa-times" ></span></button>
							 	@endif
							 	@if($incidencia->capturada)	
							 		<input type="checkbox" name="vehicle" disabled value="" {{$incidencia->capturada ? 'checked':'false'}}>
							 	@endif
							 </td>
						@endif
					</tr>
					{{--*/ $tmp = $incidencia->num_empleado /*--}}
				@endif
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
            var route = "http://incidencias.ddns.net/incidencias_del/"+btn;
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
