<table style="font-size:7.5pt text-align:center; font-family:family:Arial; width: 100%;  ">
	<thead>

	<tr style="background-color: rgb(171,165,160);  ">
		<td>Num Empleado</td>
		<td>Empleado</td>
		<td>Codigo</td>
		<td>Fecha Inicial</td>
		<td>Fecha Final</td>
		<td>Categoria</td>
		<td>Centro</td>
	</tr>
	</thead>
	<tbody style="font-size:8pt text-align:center; font-family:family:Arial; width: 100%;  ">
	{{--*/ $tmp = "" /*--}}
		@foreach($incidencias as $incidencia)
			<tr rowspan="2">
				@if($incidencia->num_empleado == $tmp)
				
					<td></td>
					<td></td>
					 <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
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
					 <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</td>
					 <td align=center>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
					 <td align=center>{{ fecha_dmy($incidencia->fecha_final) }}</td>
					 <td align=left>{{ $incidencia->puesto }}</td>
					 <td align=left>{{ get_departamento($incidencia->deparment_id) }}</td>
			</tr>
					{{--*/ $tmp = $incidencia->num_empleado /*--}}
				@endif
		@endforeach
	</tbody>		
		
</table>

