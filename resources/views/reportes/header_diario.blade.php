<<<<<<< HEAD
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
=======
    <table border="0" cellpadding="3" cellspacing="0" style="width:100%; border-collapse:collapse; margin-bottom:5px; border:none;">
        <tr style="border:none;">
            <td style="vertical-align:top; padding:5px; width:50%; border:none;">
                <img alt="ISSSTE" src="{{ isset($logoPath) ? $logoPath : public_path('images/60issste.png') }}" style="width:300px; height:75px;" />
            </td>
            <td style="text-align:right; vertical-align:top; padding:5px; border:none;">
                <div style="margin:0; font-size:14px; line-height:1.2; font-weight:bold;">REPRESENTACION ESTATAL BAJA CALIFORNIA</div>
                <div style="margin:0; font-size:14px; line-height:1.2; font-weight:bold;">SUBDELEGACION DE ADMINISTRACION</div>
                <div style="margin:0; font-size:12px; line-height:1.2; font-weight:bold;">DEPARTAMENTO DE RECURSOS HUMANOS</div>
            </td>
        </tr>
    </table>
>>>>>>> origin/reporte-diario

    <div style="width:100%; background-color:#666666; color:white; padding:3px 8px; text-align:center; margin:5px 0;">
        REPORTE DIARIO DE CONTROL DE ASISTENCIA
    </div>

<<<<<<< HEAD
			<p><h4><strong>DEPARTAMENTO DE RECURSOS HUMANOS</strong></h4></p>


			</td>
		</tr>
		<table border="0" cellpadding="1" cellspacing="1" style="width:500px;">
			<tbody>
				<tr>
					<p><td style="width: 100%; background-color: rgb(102, 102, 102);"><span style="color:#FFFFFF;">REPORTE DIARIO DE CONTROL DE ASISTENCIA</span></td></p>
				</tr>
			</tbody>
		</table>

		<tr>

			<p><td><span style="font-size:16px;">FECHA DE CAPTURA: <strong>{{$fecha}}</strong></span></td></p>

		</tr>

	</tbody>

</table>

<hr>
</body>
</html>
=======
    <table border="0" cellpadding="3" cellspacing="0" style="width:100%; border-collapse:collapse; margin-bottom:5px; border:none;">
        <tr style="border:none;">
            <td style="font-size:12px; border:none; font-weight:bold;">
                FECHA DE CAPTURA: {{ fecha_dmy($fecha) }}
            </td>
            <td style="text-align:right; font-size:12px; border:none;">
                GENERADO EL: <span style="font-weight:bold;">{{ date('d/m/Y') }}</span>
            </td>
        </tr>
    </table>
>>>>>>> origin/reporte-diario
