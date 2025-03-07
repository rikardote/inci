<!-- filepath: /d:/swtools/laragon/www/incidencias/resources/views/admin/employees/partials/_checadas.blade.php -->
@php
// Variables iniciales - MOVER AQUÍ AL INICIO
$horarioParts = explode(" A ", $employe->horario);
$horaEntradaEmpleado = isset($horarioParts[0]) ? trim($horarioParts[0]) : "08:00";
$horaSalidaEmpleado = isset($horarioParts[1]) ? trim($horarioParts[1]) : "16:00";
$fechasRegistradas = [];
@endphp


<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="12%"><i class="fas fa-calendar"></i> Fecha</th>
                <th width="25%"><i class="fas fa-right-to-bracket"></i> Entrada</th>
                <th width="25%"><i class="fas fa-right-from-bracket"></i> Salida</th>

                <th width="20%"><i class="fas fa-file-pen"></i> Incidencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checadas->groupBy(function($checada) {
            return date('Y-m-d', strtotime($checada->fecha));
            }) as $fecha => $checadasDia)
            @php
            // Registro de fechas procesadas
            $fechaFormateada = fecha_dmy($checadasDia->first()->fecha);
            $fechasRegistradas[] = $fecha;

            // Calcular mediodía
            $medioDia = strtotime('12:00:00');

            // Obtener primera y última checada del día
            $checadasOrdenadas = $checadasDia->sortBy('fecha');
            $horaEntrada = date('H:i', strtotime($checadasOrdenadas->first()->fecha));
            $horaSalida = $checadasDia->count() > 1
            ? date('H:i', strtotime($checadasDia->sortByDesc('fecha')->first()->fecha))
            : null;

            // Calcular retardos - MODIFICADO PARA INCLUIR 11 MINUTOS DE TOLERANCIA
            $horaEntradaConTolerancia = strtotime("+11 minutes", strtotime($fecha . ' ' . $horaEntradaEmpleado . ':00'));
            $retardo = strtotime($checadasOrdenadas->first()->fecha) > $horaEntradaConTolerancia;

            // Determinar si hay omisión de salida
            $tieneUnaSolaChecada = $checadasDia->count() == 1;
            $jornadalaboralTerminada = strtotime($fecha) < strtotime(date('Y-m-d'));
            $sinRegistroSalida = $tieneUnaSolaChecada && $jornadalaboralTerminada;
            $salidaTemprana = $horaSalida && strtotime($fecha . ' ' . $horaSalida . ':00') < strtotime($fecha . ' ' . $horaSalidaEmpleado . ':00');

            // Detectar múltiples checadas
            $conteoChecadasDespuesMedioDia = 0;
            foreach ($checadasDia as $c) {
                if (strtotime($c->fecha) > $medioDia) {
                    $conteoChecadasDespuesMedioDia++;
                }
            }

            // Verificar omisión de entrada
            $primeraChecadaDespuesMedioDia = false;
            foreach ($checadasOrdenadas as $c) {
                if (strtotime($c->fecha) > $medioDia) {
                    $primeraChecadaDespuesMedioDia = true;
                    break;
                }
            }
            $omisionEntrada = $primeraChecadaDespuesMedioDia &&
            strtotime($checadasOrdenadas->first()->fecha) > $medioDia;

            // Determinar clase para la fila
            $rowClass = '';
            if ($retardo) $rowClass = 'retardo';
            if ($salidaTemprana) $rowClass = 'warning';
            if ($retardo && $salidaTemprana) $rowClass = 'danger';

            // Obtener código de incidencia para este día (si existe)
            $codigoIncidencia = isset($checadasDia->first()->incidencia) ? $checadasDia->first()->incidencia : null;

            // Intenta obtener la incidencia a través del método de la clase Checada
            if (!$codigoIncidencia && method_exists('App\Checada', 'obtenerIncidencia')) {
                $incidenciaObj = App\Checada::obtenerIncidencia($employe->num_empleado, $fecha);
                $codigoIncidencia = !empty($incidenciaObj) ? $incidenciaObj[0]->code : null;
            }
            @endphp

            <tr class="{{ $rowClass }} {{ $codigoIncidencia ? 'incidencia' : '' }}">
                <td>{{ $fechaFormateada }}</td>
                <td>
                    @if($omisionEntrada)
                    <!-- Omisión de entrada -->
                    <span class="label label-danger rounded sin-registro" title="Sin registro de entrada">
                        <i class="fas fa-right-to-bracket"></i> ✕ Omisión de entrada
                    </span>
                    @else
                    <!-- Hora de entrada -->
                    <span class="{{ $retardo ? 'text-warning font-weight-bold' : '' }}">
                        <i class="fas fa-clock"></i> {{ $horaEntrada }}
                    </span>
                    @if($retardo && !$codigoIncidencia)
                    <span class="label label-danger rounded">R</span>
                    @endif
                    @endif
                </td>
                <td>
                    @if($horaSalida)
                    <!-- Hora de salida -->
                    <span>
                        <i class="fas fa-clock"></i> {{ $horaSalida }}
                        @if($conteoChecadasDespuesMedioDia > 1)
                        <span class="label label-info rounded" title="Múltiples salidas registradas">
                            <i class="fas fa-circle-info"></i> {{ $conteoChecadasDespuesMedioDia }}
                        </span>
                        @endif
                    </span>
                    @elseif($sinRegistroSalida && !$codigoIncidencia)
                    <!-- Sin registro de salida -->
                    <span class="label label-danger rounded sin-registro" title="Sin registro de salida">
                        <i class="fas fa-right-from-bracket"></i> ✕
                    </span>
                    @endif
                </td>

                <td>
                    @if($codigoIncidencia)
                    <span class="label label-warning rounded">{{ $codigoIncidencia }}</span>
                    @endif
                </td>
            </tr>
            @endforeach

            @if(count($fechasRegistradas) == 0)
            <tr>
                <td colspan="5" class="text-center">
                    <i class="fas fa-circle-info"></i>
                    No hay registros de asistencia para mostrar
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<style>
    /* Añadido estilo para filas con incidencia */
    tr.incidencia {
        background-color: #fff3cd !important;
    }

    /* Resto de estilos sin cambios */
    .employee-table {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
        background-color: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .employee-name {
        padding: 10px;
        background-color: #f5f5f5;
        border-bottom: 1px solid #ddd;
    }

    .employee-info h5 {
        font-weight: 600;
        margin-bottom: 5px !important;
    }

    .employee-schedule {
        font-size: 13px;
        color: #666;
        margin-top: 3px;
    }

    .label {
        display: inline-block;
        padding: 3px 6px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1;
        color: white;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
    }

    .label-success {
        background-color: #5cb85c;
    }

    .label-info {
        background-color: #5bc0de;
    }

    .label-warning {
        background-color: #f0ad4e;
    }

    .label-danger {
        background-color: #d9534f;
    }

    .rounded {
        border-radius: 3px;
    }

    .sin-registro {
        font-size: 11px;
    }

    tr.retardo {
        background-color: #fcf8e3 !important;
    }

    tr.warning {
        background-color: #d9edf7 !important;
    }

    tr.danger {
        background-color: #f2dede !important;
    }

    .font-weight-bold {
        font-weight: bold;
    }
</style>

<script>
    $(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
