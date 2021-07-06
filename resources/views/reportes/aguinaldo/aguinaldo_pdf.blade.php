<table style="font-size:9.5pt text-align:center; font-family:family:Arial; width: 100%;  ">
	<thead>
	<tr style="background-color: rgb(171,165,160);  ">
		<th>Departamento</th>
		<th>Num Empleado</th>
		<th>Empleado</th>
		<th>Codigo</th>
		<th>Total Dias</th>
	</tr>
	</thead>
	<tbody>

		@foreach($incidencias as $incidencia)
			<tr>
				{{--*/ $depa = getDeparment($incidencia->deparment_id) /*--}}
				<td>{{ ($depa == "00104") ? "00105" : $depa }}</td>
				<td align="center">{{ $incidencia->num_empleado }}</td>
				<td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</td>
				<td align="center">{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
				<td align="center">{{ $incidencia->total }}</td>
						 
			</tr>

		@endforeach
	</tbody>		
		
</table>