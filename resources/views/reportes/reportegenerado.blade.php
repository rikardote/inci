<table style="width:100%; border-collapse:collapse; font-family:Arial; font-size:9pt; text-align:center; border:1px solid #999; table-layout:fixed;">
    <thead>
        <tr>
            <th style="width:10%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Num Empleado</th>
            <th style="width:30%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Empleado</th>
            <th style="width:8%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Codigo</th>
            <th style="width:13%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Fecha Inicial</th>
            <th style="width:13%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Fecha Final</th>
            <th style="width:13%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Periodo</th>
            <th style="width:8%; padding:4px; font-weight:bold; background-color:rgb(171,165,160); border:1px solid #666;">Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $tmp = "";
            $firstRow = true;
            $colorAlternado = false;
        @endphp
        @foreach($incidencias as $incidencia)
            @if($incidencia->num_empleado == $tmp)
                <tr>
                    <td style="width:10%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">&nbsp;</td>
                    <td style="width:30%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:left;">
                        <div style="min-height:16px;">
                            @if(isset($incidencia->otorgado))
                                <small><strong>{{ $incidencia->otorgado }}</strong></small>
                            @endif
                        </div>
                        <div style="min-height:16px;">
                            @if(isset($incidencia->becas_comments))
                                <small><strong>{{ $incidencia->becas_comments }}</strong></small>
                            @endif
                        </div>
                        <div style="min-height:16px;">
                            @if(isset($incidencia->horas_otorgadas))
                                <small><strong>{{ $incidencia->horas_otorgadas }}</strong></small>
                            @endif
                        </div>
                    </td>
                    <td style="width:8%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if($incidencia->code == 901)
                            OT
                        @elseif($incidencia->code == 905)
                            PS
                        @else
                            {{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT) }}
                        @endif
                    </td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">{{ fecha_dmy($incidencia->fecha_final) }}</td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if(isset($incidencia->periodo))
                            {{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}
                        @endif
                    </td>
                    <td style="width:8%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">{{ $incidencia->total }}</td>
                </tr>
            @else
                @if(!$firstRow)
                    <!-- Se han eliminado los separadores grises aquí -->
                @endif

                <tr>
                    <td style="width:10%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center; font-weight:bold;">{{ $incidencia->num_empleado }}</td>
                    <td style="width:30%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:left;">
                        <div style="font-weight:bold;">{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</div>
                        <div style="min-height:16px;">
                            @if(isset($incidencia->otorgado))
                                <small><strong>{{ $incidencia->otorgado }}</strong></small>
                            @endif
                        </div>
                        <div style="min-height:16px;">
                            @if(isset($incidencia->becas_comments))
                                <small><strong>{{ $incidencia->becas_comments }}</strong></small>
                            @endif
                        </div>
                        <div style="min-height:16px;">
                            @if(isset($incidencia->horas_otorgadas))
                                <small><strong>{{ $incidencia->horas_otorgadas }}</strong></small>
                            @endif
                        </div>
                    </td>
                    <td style="width:8%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if($incidencia->code == 901)
                            OT
                        @elseif($incidencia->code == 905)
                            PS
                        @else
                            {{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT) }}
                        @endif
                    </td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">{{ fecha_dmy($incidencia->fecha_final) }}</td>
                    <td style="width:13%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">
                        @if(isset($incidencia->periodo))
                            {{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}
                        @endif
                    </td>
                    <td style="width:8%; padding:2px; border:1px solid #ccc; vertical-align:top; text-align:center;">{{ $incidencia->total }}</td>
                </tr>
                <!-- Se ha eliminado el separador gris inferior aquí -->
                @php
                    $tmp = $incidencia->num_empleado;
                    $firstRow = false;
                @endphp
            @endif
        @endforeach
    </tbody>
</table>
