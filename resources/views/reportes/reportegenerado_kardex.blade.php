<table style="font-size:9.5pt font-family:family:Arial; width: 100%;">
	<thead>
	<tr style="width: 100%; background-color: rgb(171,165,160);  ">
		<td align=center>Codigo</td>
		<td align=center>Fecha Inicial</td>
		<td align=center>Fecha Final</td>
		<td align=center>Periodo</td>
		<td align=center>Total</td>
	</tr>
	</thead>
	<tbody>
	{{--*/ $tmp = "" /*--}}
		@foreach($incidencias as $incidencia)
			<tr rowspan="2">
					 <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
					 
					 @if(isset($incidencia->periodo))
							 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
					 @else
					 			<td></td>
					 @endif
					 <td align=center>{{ $incidencia->total }}</td>
					 
				</tr>
		@endforeach
	</tbody>
</table>

