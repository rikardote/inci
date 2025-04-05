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
