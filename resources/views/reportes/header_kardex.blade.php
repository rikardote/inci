<!doctype html>
<html>
<head>

</head>
<body>
<table border="0" cellpadding="12" cellspacing="0" style="width:850px;">
	<tbody>
		<tr>
			<td><img alt="" src="images/60issste.png" style="width: 400px; height: 108px;" /></td>
			<td align='right'>
			<p><h3><strong>REPRESENTACION ESTATAL BAJA CALIFORNIA</strong></h3></p>

			<p><h3><strong>SUBDELEGACION DE ADMINISTRACION</strong></h3></p>

			<p><h4><strong>DEPARTAMENTO DE RECURSOS HUMANOS</strong></h4></p>


			</td>
		</tr>
		<table border="0" cellpadding="1" cellspacing="1" style="width:500px;">
			<tbody>
				<tr>
					<p><td style="width: 100%; background-color: rgb(102, 102, 102);"><span style="color:#FFFFFF;">K A R D E X</span></td></p>
				</tr>
			</tbody>
		</table>
		<tr>

			<p><td><span style="font-size:16px;">NUM EMPLEADO: <strong>{{$empleado->num_empleado}}</strong></span></td></p>
			><td align='right'><span style="font-size:16px;">NOMBRE: <strong>{{$empleado->name}} {{$empleado->father_lastname}} {{$empleado->mother_lastname}}</strong></span></td></p>
		</tr>
		<tr>

			<p><td><span style="font-size:16px;">FECHA DE INGRESO: <strong>{{fecha_dmy($empleado->fecha_ingreso)}}</strong></span></td></p>
			><td align='right'><span style="font-size:16px;">TURNO: <strong>{{$jornada->jornada}}</strong> - HORARIO: <strong>{{$horario->horario}}</strong> </span></td></p>
		</tr>
		<tr>
			<p><td><span style="font-size:16px;">CLAVE DE ADSCRIPCION: <strong>{{($dpto->code == "00104") ? "00105":$dpto->code}}</strong></span></td></p>
			<td align='right'><span style="font-size:16px;">DESCRIPCION: <strong>{{$dpto->description}}</strong></span></td></p>
		</tr>
		<tr><td></td>
		<td align='right'><span style="font-size:16px;">RANGO DE FECHAS: <strong>{{fecha_dmy($fecha_inicio)}} AL {{fecha_dmy($fecha_final)}}</strong></span></td>

		</tr>

	</tbody>

</table>

<hr>
</body>
</html>
