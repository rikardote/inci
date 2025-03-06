<div class="tables-container">
    @foreach($empleados as $num_empleado => $registrosEmpleado)
    <!-- Cada empleado ocupa una celda en el grid de 3 columnas -->
    <div class="employee-table">
        <div class="employee-name">
            <div class="employee-info">
                <h5 class="mb-0" style="margin: 0;">
                    <strong>{{ $num_empleado }}</strong> -
                    {{ strtoupper($registrosEmpleado->first()->apellido_paterno) }}
                    {{ strtoupper($registrosEmpleado->first()->apellido_materno) }}
                    {{ strtoupper($registrosEmpleado->first()->nombre) }}
                </h5>
            </div>
            <div class="employee-schedule">
                <i class="fa fa-clock-o"></i>
                {{ substr($registrosEmpleado->first()->horario_entrada, 0, 5) }} A
                {{ substr($registrosEmpleado->first()->horario_salida, 0, 5) }}
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th width="15%"><i class="fa fa-calendar"></i> Fecha</th>
                        <th width="30%"><i class="fa fa-sign-in"></i> Entrada</th>
                        <th width="30%"><i class="fa fa-sign-out"></i> Salida</th>
                        <th width="25%"><i class="fa fa-exclamation-triangle"></i> Incidencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrosEmpleado as $registro)
                    @php
                    // Definir todas las variables necesarias para la lógica de procesamiento
                    $horarioEntrada = strtotime($registrosEmpleado->first()->horario_entrada);
                    $horarioSalida = strtotime($registrosEmpleado->first()->horario_salida);

                    // Ajuste importante: definir medio día como las 12:00 PM, no como punto medio del horario
                    $medioDia = strtotime('12:00:00');

                    $horaEntrada = $registro->hora_entrada ? strtotime($registro->hora_entrada) : null;
                    $horaSalida = $registro->hora_salida ? strtotime($registro->hora_salida) : null;

                    // Detectar si es una sola checada (entrada y salida iguales)
                    $tieneUnaSolaChecada = $registro->hora_entrada && $registro->hora_entrada === $registro->hora_salida;

                    // Verificar si la única checada es más cercana a la salida (después del medio día)
                    $horaChecada = $registro->hora_entrada ? strtotime($registro->hora_entrada) : null;
                    $esMasCercanaASalida = $horaChecada && $horaChecada > $medioDia;

                    // Detectar múltiples checadas para el mismo día
                    $checadasDelDia = $registrosEmpleado->where('fecha', $registro->fecha)->sortBy('hora_entrada');
                    $tieneMasDeUnaChecada = $checadasDelDia->count() > 1;
                    $esLaPrimeraChecadaDelDia = $checadasDelDia->first() === $registro;
                    $esLaUltimaChecadaDelDia = $checadasDelDia->last() === $registro;

                    // Verificar si todas las checadas del día están después del mediodía
                    $todasLasChecadasDespuesMediodia = true;
                    foreach ($checadasDelDia as $c) {
                        $checadaHora = $c->hora_entrada ? strtotime($c->hora_entrada) : null;
                        if ($checadaHora && $checadaHora < $medioDia) {
                            $todasLasChecadasDespuesMediodia = false;
                            break;
                        }
                    }

                    // Si hay múltiples checadas y todas están después del mediodía, es caso de múltiples salidas
                    $esMultipleSalida = $tieneMasDeUnaChecada && $todasLasChecadasDespuesMediodia;

                    // Verificar si esta checada está después del mediodía
                    $estaChecadaDespuesMedioDia = $horaChecada && $horaChecada > $medioDia;

                    // Validación de jornada - añadir excepción para caso de múltiples salidas
                    $tiempoMinimoJornada = 3 * 60 * 60; // 3 horas en segundos
                    $duracionJornada = ($horaEntrada && $horaSalida) ? ($horaSalida - $horaEntrada) : 0;

                    // Una jornada es válida si:
                    // 1. Dura más del tiempo mínimo
                    // 2. O es un caso de múltiples salidas
                    // 3. O es un registro de salida regular (no es un caso de primera checada del día)
                    $esJornadaValida = $duracionJornada >= $tiempoMinimoJornada ||
                                      $esMultipleSalida ||
                                      ($registro->hora_salida && !$esLaPrimeraChecadaDelDia);

                    // Variables para verificar si la jornada ha terminado
                    $fechaRegistro = $registro->fecha;
                    $fechaActual = date('Y-m-d');
                    $esHoy = ($fechaRegistro == $fechaActual);
                    $horaActual = strtotime(date('H:i:s'));
                    $jornadalaboralTerminada = !$esHoy || ($esHoy && $horaActual > $horarioSalida);
                    @endphp

                        <tr class="clickable-row {{ ($registro->retardo && $registro->incidencia != '7') ? 'retardo' : '' }} {{ ($registro->incidencia && !in_array($registro->incidencia, $incidenciasSinColor)) ? 'incidencia' : '' }}"
                            data-empleado="{{ $num_empleado }}" onclick="submitForm(this)">
                            <td>{{ date('d/m/Y', strtotime($registro->fecha)) }}</td>
                            <td>
                                @if($esMultipleSalida && $esLaPrimeraChecadaDelDia)
                                <!-- Caso especial: Primera checada de múltiples checadas después del mediodía -->
                                <span class="label label-danger rounded sin-registro" title="Sin registro de entrada - primera checada después de medio día">
                                    <i class="fa fa-sign-in"></i> ✕ Omisión de entrada
                                </span>
                                @elseif($tieneUnaSolaChecada && $esMasCercanaASalida)
                                <!-- Caso: Una sola checada después del mediodía -->
                                <span class="label label-danger rounded sin-registro" title="Sin registro de entrada - checada después de medio día">
                                    <i class="fa fa-sign-in"></i> ✕ Omisión de entrada
                                </span>
                                @elseif($registro->hora_entrada)
                                <!-- Caso normal: Mostrar hora de entrada -->
                                <span class="{{ $registro->retardo && $registro->incidencia != '7' ? 'text-warning font-weight-bold' : '' }}">
                                    <i class="fa fa-clock-o"></i> {{ substr($registro->hora_entrada, 0, 5) }}
                                </span>
                                @if($registro->retardo && $registro->incidencia != '7')
                                <span class="label label-danger rounded">R</span>
                                @endif
                                @endif
                            </td>
                            <td>
                                @if($esMultipleSalida)
                                <!-- Caso 1: Múltiples salidas -->
                                <span>
                                    <i class="fa fa-clock-o"></i> {{ substr($registro->hora_salida, 0, 5) }}
                                    @if($esLaUltimaChecadaDelDia)
                                    <span class="label label-info rounded" title="Múltiples salidas registradas">
                                        <i class="fa fa-info-circle"></i> {{ $checadasDelDia->count() }}
                                    </span>
                                    @endif
                                </span>
                                @elseif($tieneUnaSolaChecada && $esMasCercanaASalida)
                                <!-- Caso 2: Una sola checada después del mediodía -->
                                <span>
                                    <i class="fa fa-clock-o"></i> {{ substr($registro->hora_salida, 0, 5) }}
                                </span>
                                @elseif(!$tieneUnaSolaChecada && $registro->hora_salida)
                                <!-- Caso 3: Salida normal -->
                                <span>
                                    <i class="fa fa-clock-o"></i> {{ substr($registro->hora_salida, 0, 5) }}
                                </span>
                                @elseif($tieneUnaSolaChecada && !$esMasCercanaASalida && $jornadalaboralTerminada)
                                <!-- Caso 4: Sin registro de salida (solo entrada) -->
                                <span class="label label-danger rounded sin-registro" title="Sin registro de salida">
                                    <i class="fa fa-sign-out"></i> ✕
                                </span>
                                @elseif($registro->hora_entrada && !$registro->hora_salida && $jornadalaboralTerminada)
                                <!-- Caso 5: Sin registro de salida (con entrada registrada) -->
                                <span class="label label-danger rounded sin-registro" title="Sin registro de salida">
                                    <i class="fa fa-sign-out"></i> ✕
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($registro->incidencia)
                                <span class="label label-warning rounded">{{ $registro->incidencia }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

<!-- Formulario para búsqueda de empleado -->
{!! Form::open(['route' => 'empleados.search', 'method' => 'post', 'id' => 'rowForm']) !!}
<input type="hidden" name="num" id="empleado_num">
{!! Form::close() !!}

<script>
    function submitForm(row) {
        var form = document.getElementById('rowForm');
        form.target = '_blank';
        document.getElementById('empleado_num').value = row.dataset.empleado;
        form.submit();
    }


</script>
