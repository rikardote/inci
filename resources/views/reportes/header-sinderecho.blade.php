<!doctype html>
<html>
<head>
	
</head>
<body>
<table border="0" cellpadding="5" cellspacing="0" style="width:850px;">
	<tbody>
		<tr>
			<td><img alt="" src="images/60issste.png" style="width: 400px; height: 80px;" /></td>
			<td align='right'>
			<p><h3><strong>DELEGACION ESTATAL BAJA CALIFORNIA</strong></h3></p>

			<p><h4><strong>SUBDELEGACION DE ADMINISTRACION</strong></h4></p>

			<p><h5><strong>DEPARTAMENTO DE RECURSOS HUMANOS</strong></h5></p>
			
			
			</td>
		</tr>
		<table border="0" cellpadding="1" cellspacing="1" style="width:500px;">
			<tbody>
				<tr>
					<p><td style="width: 100%; background-color: rgb(102, 102, 102);"><span style="color:#FFFFFF;">RELACION DE PERSONAL SIN DERECHO A NOTA BUENA</span></td></p>
				</tr>
			</tbody>
		</table>

		<tr>

			<p><td><span style="font-size:16px;">CLAVE DE ADSCRIPCION: <strong>{{($dpto->code == "00104") ? "00105":$dpto->code}}</strong></span></td></p>
			<p></p><td align='right'><span style="font-size:16px;">DESCRIPCION: <strong>{{$dpto->description}}</strong></span></td></p>
		</tr>
		<tr>
			<p><td><span style="font-size:16px;">MES: <strong>{{getMonth($fecha_inicio)}}</strong></span></td></p>
			
		</tr>
	</tbody>
	
</table>

</body>
</html>
