<!doctype html>
<html>

<body>
<table border="0" cellpadding="12" cellspacing="0" style="width:850px;">
	<tbody>
		<tr>
			<td><img alt="" src="images/60issste.png" style="width: 400px; height: 100px;" /></td>
			<td align='right'>
			<p><h3><strong>REPRESENTACION ESTATAL BAJA CALIFORNIA</strong></h3></p>

			<p><h3><strong>SUBDELEGACION DE ADMINISTRACION</strong></h3></p>

			<p><h4><strong>DEPARTAMENTO DE RECURSOS HUMANOS</strong></h4></p>


			</td>
		</tr>
		<table border="0" cellpadding="1" cellspacing="1" style="width:500px;">
			<tbody>
				<tr>
					<p><td style="width:100%; background-color: rgb(102, 102, 102);" align="right"><span style="color:#FFFFFF;">REPORTE DE CONTROL DE ASISTENCIA</span></td></p>
				</tr>
			</tbody>
		</table>

		<tr>

			<p><td><span style="font-size:16px;">CLAVE DE ADSCRIPCION: <strong>{{($dpto->code == "00104") ? "00105":$dpto->code}}</td></span></td></p>
			<p></p><td align='right'><span style="font-size:16px;">DESCRIPCION: <strong>{{$dpto->description}}</strong></span></td></p>
		</tr>
		<tr>
			<p><td><span style="font-size:16px;">QNA: <strong>{{$qna->qna.'/'.$qna->year.' - '.$qna->description}}  </strong></span></td></p>
			<p><td align='right'><span style="font-size:16px;">A&Ntilde;O: <strong>{{$qna->year}}</strong></span></td></p>
		</tr>
	</tbody>

</table>

<hr>
</body>
</html>
