@php
    $tmp = "";
    $firstRow = true;
    $colorAlternado = false;
    $empleadoCount = 0;
@endphp

@if($isFirstChunk)
<div class="reporte-incidencias">
    <style>
        .reporte-incidencias {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .reporte-incidencias table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            font-size: 8pt; /* Reducido a 8pt para aprovechar más espacio */
            margin-bottom: 10px; /* Reducido de 15px a 10px */
            box-shadow: none; /* Eliminada sombra para ahorrar espacio */
        }
        .reporte-incidencias thead {
            background-color: #72869e !important; /* Color más claro pero con buen contraste */
            color: white;
        }
        .reporte-incidencias thead tr {
            background-color: #72869e !important;
        }
        .reporte-incidencias th {
            padding: 4px 2px; /* Reducido de 6px 4px a 4px 2px */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px; /* Reducido de 0.5px */
            font-size: 7.5pt; /* Reducido de 8pt */
            text-align: center;
            border: none;
            white-space: nowrap;
            background-color: #72869e !important;
            color: white !important;
        }
        .reporte-incidencias tbody tr {
            page-break-inside: avoid;
        }
        .reporte-incidencias .grupo-par {
            background-color: #f5f5f5;
        }
        .reporte-incidencias .grupo-impar {
            background-color: #ffffff;
        }
        .reporte-incidencias .empleado-header {
            border-left: 2px solid #4c6d94; /* Reducido de 3px a 2px */
        }
        .reporte-incidencias td {
            padding: 2px 2px; /* Reducido de 4px 3px a 2px 2px */
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        /* Estilos para alinear correctamente el número y nombre del empleado */
        .reporte-incidencias .empleado-num {
            font-weight: 900 !important;
            font-family: 'Segoe UI Bold', Arial Black, sans-serif;
            text-align: center;
            vertical-align: top;
            padding-top: 2px; /* Reducido de 4px */
        }

        .reporte-incidencias .empleado-nombre {
            font-weight: 900 !important;
            font-family: 'Segoe UI Bold', Arial Black, sans-serif;
            color: #2c3e50;
            display: block;
            margin-bottom: 0; /* Eliminado margen */
        }

        .reporte-incidencias .empleado-header td {
            vertical-align: top;
            padding-top: 2px; /* Reducido de 4px */
        }

        .reporte-incidencias .info-adicional {
            font-size: 6.5pt; /* Reducido de 7pt */
            color: #666;
            margin-top: 0; /* Eliminado margen superior */
            line-height: 1; /* Reducido de 1.1 */
            display: block;
        }

        /* Eliminado el separador entre empleados para ahorrar espacio */
        /* Si es absolutamente necesario mantener un separador visual, usar uno mínimo */
        .reporte-incidencias .separador-empleados {
            height: 2px; /* Reducido de 5px a 2px */
            background-color: #eee; /* Color más claro */
            border: none; /* Eliminados bordes */
        }

        .reporte-incidencias .texto-centro {
            text-align: center;
        }

        .reporte-incidencias .texto-izquierda {
            text-align: left;
        }

        .reporte-incidencias .fecha-cell,
        .reporte-incidencias .periodo-cell,
        .reporte-incidencias .info-total {
            white-space: nowrap;
            font-size: 7.5pt; /* Ligeramente más pequeño */
        }

        @media print {
            .reporte-incidencias {
                font-size: 8pt; /* Reducido para impresión */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color-adjust: exact;
            }
            .reporte-incidencias thead,
            .reporte-incidencias thead tr,
            .reporte-incidencias th {
                background-color: #72869e !important; /* Color más claro */
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    <table>
        <thead>
            <tr>
                <th style="width:10%">No. Empleado</th>
                <th style="width:30%; text-align: left">Empleado</th>
                <th style="width:8%">Código</th>
                <th style="width:13%">Fecha Inicial</th>
                <th style="width:13%">Fecha Final</th>
                <th style="width:13%">Periodo</th>
                <th style="width:8%">Total</th>
            </tr>
        </thead>
        <tbody>
@else

@endif

@foreach($incidencias as $key => $incidencia)
    @php
        $incidencia = (object)$incidencia;
        $isFirstRowOfGroup = !isset($tmp) || $incidencia->num_empleado != $tmp;
        $isLastRowOfGroup = ($key == count($incidencias) - 1) ||
                           (isset($incidencias[$key + 1]) &&
                           ((object)$incidencias[$key + 1])->num_empleado != $incidencia->num_empleado);
    @endphp

    @if(isset($tmp) && $incidencia->num_empleado == $tmp)
        <!-- Filas adicionales del mismo empleado - mantienen el mismo color de fondo -->
        <tr class="grupo-{{ $colorAlternado ? 'par' : 'impar' }}">
            <td class="texto-centro">&nbsp;</td>
            <td class="texto-izquierda">
                @if(isset($incidencia->otorgado) || isset($incidencia->becas_comments) || isset($incidencia->horas_otorgadas))
                <div class="info-adicional">
                    @if(isset($incidencia->otorgado))
                        <div>{{ $incidencia->otorgado }}</div>
                    @endif
                    @if(isset($incidencia->becas_comments))
                        <div>{{ $incidencia->becas_comments }}</div>
                    @endif
                    @if(isset($incidencia->horas_otorgadas))
                        <div>{{ $incidencia->horas_otorgadas }}</div>
                    @endif
                </div>
                @endif
            </td>
            <td class="texto-centro">
                @if($incidencia->code == 901)
                    <span class="codigo-especial">OT</span>
                @elseif($incidencia->code == 905)
                    <span class="codigo-especial">PS</span>
                @else
                    {{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT) }}
                @endif
            </td>
            <td class="texto-centro fecha-cell">{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
            <td class="texto-centro fecha-cell">{{ fecha_dmy($incidencia->fecha_final) }}</td>
            <td class="texto-centro periodo-cell">
                @if(isset($incidencia->periodo))
                    {{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}
                @endif
            </td>
            <td class="texto-centro info-total">{{ $incidencia->total }}</td>
        </tr>
    @else
        @php
            $colorAlternado = !$colorAlternado;
            $empleadoCount++;
        @endphp

        <!-- Primera fila del empleado -->
        <tr class="empleado-header grupo-{{ $colorAlternado ? 'par' : 'impar' }}">
            <td class="empleado-num"><strong>{{ $incidencia->num_empleado }}</strong></td>
            <td class="texto-izquierda">
                <span class="empleado-nombre"><strong>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }}</strong></span>
                @if(isset($incidencia->otorgado) || isset($incidencia->becas_comments) || isset($incidencia->horas_otorgadas))
                <div class="info-adicional">
                    @if(isset($incidencia->otorgado))
                        <div>{{ $incidencia->otorgado }}</div>
                    @endif
                    @if(isset($incidencia->becas_comments))
                        <div>{{ $incidencia->becas_comments }}</div>
                    @endif
                    @if(isset($incidencia->horas_otorgadas))
                        <div>{{ $incidencia->horas_otorgadas }}</div>
                    @endif
                </div>
                @endif
            </td>
            <td class="texto-centro">
                @if($incidencia->code == 901)
                    <span class="codigo-especial">OT</span>
                @elseif($incidencia->code == 905)
                    <span class="codigo-especial">PS</span>
                @else
                    {{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT) }}
                @endif
            </td>
            <td class="texto-centro fecha-cell">{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
            <td class="texto-centro fecha-cell">{{ fecha_dmy($incidencia->fecha_final) }}</td>
            <td class="texto-centro periodo-cell">
                @if(isset($incidencia->periodo))
                    {{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}
                @endif
            </td>
            <td class="texto-centro info-total">{{ $incidencia->total }}</td>
        </tr>
        @php
            $tmp = $incidencia->num_empleado;
            $firstRow = false;
        @endphp
    @endif
@endforeach
        </tbody>
    </table>
</div>
