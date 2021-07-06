<table style="font-size:9.5pt text-align:center; font-family:family:Arial; width: 100%;  ">
	<thead>
	<tr style="background-color: rgb(171,165,160);  ">
		<td>Num Empleado</td>
		<td>Empleado</td>
		<td>Codigo</td>
		<td>Fecha Inicial</td>
		<td>Fecha Final</td>
		<td>Periodo</td>
		<td>Total</td>
	</tr>
	</thead>
	<tbody>
	{{--*/ $tmp = "" /*--}}
		@foreach($incidencias as $incidencia)
			<tr rowspan="2">
				@if($incidencia->num_empleado == $tmp)
				
					<td></td>
					<td>
						@if(isset($incidencia->otorgado))
						 		<br>
                                <small>{{ $incidencia->otorgado }} </small>
                         @endif
					</td>
					@if($incidencia->code == 901)
					 	<td align=center>OT</td>
					@elseif($incidencia->code == 905)
					 	<td align=center>PS</td>
					@else
					 	<td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
					@endif
					 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
					 
					 @if(isset($incidencia->periodo))
							 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
					 @else
					 			<td></td>
					 @endif
					 <td align=center>{{ $incidencia->total }}</td>
					 
				</tr>
				
					
				@else
				<tr style="background:#000 padding-bottom:3mm;">
					@if (empty($incidencia)) 
			 			@for ($i=0; $i < 8; $i++) 
							<td></td>
						@endfor
					@endif
		</tr>	
			
					<tr>
						<td align=center>{{ $incidencia->num_empleado }}</td>
						 <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}
						 	@if(isset($incidencia->otorgado))
						 		<br>
                                <small>{{ $incidencia->otorgado }} </small>
                         	@endif


						 </td>
						 
						 @if($incidencia->code == 901)
						 	<td align=center>OT</td>
						 
						 @elseif($incidencia->code == 905)
					 		<td align=center>PS</td>
						 @else
						 	<td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
						 @endif

						 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
						 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
						 
						 @if(isset($incidencia->periodo))
								 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
						 @else
						 			<td></td>
						 @endif
						 <td align=center>{{ $incidencia->total }}</td>
						 
					</tr>
					{{--*/ $tmp = $incidencia->num_empleado /*--}}
				@endif
		@endforeach
	</tbody>		
		
</table>

