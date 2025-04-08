<<<<<<< HEAD
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
			<p><h3><strong>REPRESENTACION ESTATAL BAJA CALIFORNIA</strong></h3></p>

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

=======
<table border="0" cellpadding="3" cellspacing="0" style="width:100%; border-collapse:collapse; margin-bottom:5px;">
    <tr>
        <td style="vertical-align:top; padding:5px; width:50%; border:none;">
            <img alt="" src="images/60issste.png" style="width:300px; height:75px;" />
        </td>
        <td style="text-align:right; vertical-align:top; padding:5px; border:none;">
            <div style="margin:0; font-size:12px; line-height:1.2; font-weight:bold;">REPRESENTACION ESTATAL BAJA
                CALIFORNIA</div>
            <div style="margin:0; font-size:12px; line-height:1.2; font-weight:bold;">SUBDELEGACION DE ADMINISTRACION
            </div>
            <div style="margin:0; font-size:10px; line-height:1.2; font-weight:bold;">DEPARTAMENTO DE RECURSOS HUMANOS
            </div>
        </td>
    </tr>
>>>>>>> origin/reporte-diario
</table>

<div
    style="width:100%; background-color:#666666; color:white; padding:3px 8px; text-align:right; margin:5px 0; font-size:10px;">
    RELACION DE PERSONAL SIN DERECHO A NOTA BUENA
</div>

<table border="0" cellpadding="3" cellspacing="0" style="width:100%; border-collapse:collapse; margin-bottom:5px;">
    <tr>
        <td style="border:none;"><span style="font-size:12px;">CLAVE DE ADSCRIPCION: <strong>{{($dpto->code == "00104")
                    ? "00105":$dpto->code}}</strong></span></td>
        <td align='right' style="border:none;"><span style="font-size:12px;">DESCRIPCION:
                <strong>{{$dpto->description}}</strong></span></td>
    </tr>
    <tr>
        <td style="border:none;"><span style="font-size:14px;">MES: <strong>{{getMonth($fecha_inicio)}}</strong></span>
        </td>
    </tr>
</table>
