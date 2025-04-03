<table border="0" cellpadding="3" cellspacing="0" style="width:100%; border-collapse:collapse; margin-bottom:5px;">
  <tr>
    <td style="vertical-align:top; padding:5px; width:50%;">
      <img alt="ISSSTE" src="images/60issste.png" style="width:300px; height:75px;" />
    </td>
    <td style="text-align:right; vertical-align:top; padding:5px;">
      <div style="margin:0; font-size:14px; line-height:1.2; font-weight:bold;">REPRESENTACION ESTATAL BAJA CALIFORNIA</div>
      <div style="margin:0; font-size:14px; line-height:1.2; font-weight:bold;">SUBDELEGACION DE ADMINISTRACION</div>
      <div style="margin:0; font-size:12px; line-height:1.2; font-weight:bold;">DEPARTAMENTO DE RECURSOS HUMANOS</div>
    </td>
  </tr>
</table>

<div style="width:100%; background-color:#666666; color:white; padding:3px 8px; text-align:right; margin:5px 0;">
  REPORTE DE CONTROL DE ASISTENCIA
</div>

<table border="0" cellpadding="3" cellspacing="0" style="width:100%; border-collapse:collapse; margin-bottom:5px;">
  <tr>
    <td style="font-size:12px;">
      CLAVE DE ADSCRIPCION: <span style="font-weight:bold;">{{($dpto->code == "00104") ? "00105" : $dpto->code}}</span>
    </td>
    <td style="text-align:right; font-size:12px;">
      DESCRIPCION: <span style="font-weight:bold;">{{$dpto->description}}</span>
    </td>
  </tr>
  <tr>
    <td style="font-size:12px;">
      QNA: <span style="font-weight:bold;">{{$qna->qna.'/'.$qna->year.' - '.$qna->description}}</span>
    </td>
    <td style="text-align:right; font-size:12px;">
      AÑO: <span style="font-weight:bold;">{{$qna->year}}</span>
    </td>
  </tr>
</table>
