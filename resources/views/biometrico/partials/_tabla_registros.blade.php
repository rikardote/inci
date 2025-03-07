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
                <i class="fas fa-clock"></i>
                {{ substr($registrosEmpleado->first()->horario_entrada, 0, 5) }} A
                {{ substr($registrosEmpleado->first()->horario_salida, 0, 5) }}
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th width="15%"><i class="fas fa-calendar"></i> Fecha</th>
                        <th width="30%"><i class="fas fa-right-to-bracket"></i> Entrada</th>
                        <th width="30%"><i class="fas fa-right-from-bracket"></i> Salida</th>
                        <th width="25%"><i class="fas fa-triangle-exclamation"></i> Incidencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrosEmpleado as $registro)
                    @php
                    // Definir todas las variables necesarias para la lógica de procesamiento
                    $horarioEntrada = strtotime($registrosEmpleado->first()->horario_entrada);
                    $horarioSalida = strtotime($registrosEmpleado->first()->horario_salida);

                    // Usar la información de jornada directamente de la base de datos
                    $esJornadaVespertina = $registrosEmpleado->first()->es_jornada_vespertina == 1;

                    // Determinar el punto de referencia para separar entradas y salidas
                    if ($esJornadaVespertina) {
                        // Para vespertinos: usar el punto medio de su jornada específica
                        $horaMedia = $horarioEntrada + (($horarioSalida - $horarioEntrada) / 2);
                    } else {
                        // Para matutinos: seguir usando el mediodía
                        $horaMedia = strtotime('12:00:00');
                    }

                    // En lugar de usar $medioDia, ahora usamos $horaMedia en toda la lógica
                    $medioDia = $horaMedia; // Mantener variable por compatibilidad

                    $horaEntrada = $registro->hora_entrada ? strtotime($registro->hora_entrada) : null;
                    $horaSalida = $registro->hora_salida ? strtotime($registro->hora_salida) : null;

                    // Detectar si es una sola checada (entrada y salida iguales)
                    $tieneUnaSolaChecada = $registro->hora_entrada && $registro->hora_entrada === $registro->hora_salida;

                    // Verificar si la checada está después del punto medio
                    $horaChecada = $registro->hora_entrada ? strtotime($registro->hora_entrada) : null;
                    $estaChecadaDespuesMedioDia = $horaChecada && $horaChecada > $medioDia;

                    // Detectar múltiples checadas para el mismo día
                    $checadasDelDia = $registrosEmpleado->where('fecha', $registro->fecha)->sortBy('hora_entrada');
                    $tieneMasDeUnaChecada = $checadasDelDia->count() > 1;
                    $esLaPrimeraChecadaDelDia = $checadasDelDia->first() === $registro;
                    $esLaUltimaChecadaDelDia = $checadasDelDia->last() === $registro;

                    // Verificar si todas las checadas del día están después del punto medio
                    $todasLasChecadasDespuesMediodia = true;
                    foreach ($checadasDelDia as $c) {
                        $checadaHora = $c->hora_entrada ? strtotime($c->hora_entrada) : null;
                        if ($checadaHora && $checadaHora < $medioDia) {
                            $todasLasChecadasDespuesMediodia = false;
                            break;
                        }
                    }

                    // Contar checadas después del punto medio
                    $conteoChecadasDespuesMedioDia = 0;
                    foreach ($checadasDelDia as $c) {
                        $checadaHora = $c->hora_entrada ? strtotime($c->hora_entrada) : null;
                        if ($checadaHora && $checadaHora > $medioDia) {
                            $conteoChecadasDespuesMedioDia++;
                        }
                    }

                    // Es salida por estar después del punto medio, excepto primera checada después del punto medio
                    $esSalidaPorHorario = $estaChecadaDespuesMedioDia;
                    if ($estaChecadaDespuesMedioDia && $conteoChecadasDespuesMedioDia > 1) {
                        $primeraChecadaPostMediodia = null;
                        foreach ($checadasDelDia as $c) {
                            $checadaHora = $c->hora_entrada ? strtotime($c->hora_entrada) : null;
                            if ($checadaHora && $checadaHora > $medioDia) {
                                $primeraChecadaPostMediodia = $c;
                                break;
                            }
                        }
                        $esPrimeraChecadaDespuesMediodia = ($primeraChecadaPostMediodia === $registro);
                        $esSalidaPorHorario = !$esPrimeraChecadaDespuesMediodia || $tieneUnaSolaChecada;
                    }

                    // MODIFICACIÓN: Determinar omisión de entrada considerando horario vespertino
                    $mostrarOmisionEntrada = ($estaChecadaDespuesMedioDia &&
                                            ($esLaPrimeraChecadaDelDia ||
                                            ($conteoChecadasDespuesMedioDia > 1 &&
                                            $esPrimeraChecadaDespuesMediodia)));

                    // NUEVO: Para jornada vespertina, verificar si la primera checada está dentro del margen de entrada
                    if ($esJornadaVespertina && $esLaPrimeraChecadaDelDia && $horaChecada) {
                        // Si está dentro de ±1 hora de su horario de entrada, no mostrar omisión
                        $diferenciaMinutos = abs($horaChecada - $horarioEntrada) / 60;
                        if ($diferenciaMinutos <= 90) { // 90 minutos de margen
                            $mostrarOmisionEntrada = false;
                        }
                    }

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
                            @if($esJornadaVespertina && $esLaPrimeraChecadaDelDia && $registro->hora_entrada)
                                <!-- Primera checada del personal vespertino (siempre mostrar como entrada) -->
                                <span class="{{ $registro->retardo && $registro->incidencia != '7' ? 'text-warning font-weight-bold' : '' }}">
                                    <i class="fas fa-clock"></i> {{ substr($registro->hora_entrada, 0, 5) }}
                                    @if($registro->retardo && $registro->incidencia != '7')
                                        <span class="label label-danger rounded">R</span>
                                    @endif
                                </span>
                            @elseif($mostrarOmisionEntrada)
                                <!-- Omisión de entrada -->
                                <span class="label label-danger rounded sin-registro" title="Sin registro de entrada">
                                    <i class="fas fa-right-to-bracket"></i> ✕ Omisión de entrada
                                </span>
                            @elseif($registro->hora_entrada)
                                <!-- Caso normal: Mostrar hora de entrada -->
                                <span class="{{ $registro->retardo && $registro->incidencia != '7' ? 'text-warning font-weight-bold' : '' }}">
                                    <i class="fas fa-clock"></i> {{ substr($registro->hora_entrada, 0, 5) }}
                                </span>
                                @if($registro->retardo && $registro->incidencia != '7')
                                    <span class="label label-danger rounded">R</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if(($estaChecadaDespuesMedioDia && $esSalidaPorHorario) || ($registro->hora_salida && !$tieneUnaSolaChecada))
                            <!-- Caso 1: Salida normal o checada después del mediodía que es considerada salida -->
                            <span>
                                <i class="fas fa-clock"></i> {{ substr($registro->hora_salida, 0, 5) }}
                                @if($conteoChecadasDespuesMedioDia > 1 && $esLaUltimaChecadaDelDia && $todasLasChecadasDespuesMediodia)
                                <span class="label label-info rounded" title="Múltiples salidas registradas">
                                    <i class="fas fa-circle-info"></i> {{ $conteoChecadasDespuesMedioDia }}
                                </span>
                                @endif
                            </span>
                            @elseif($tieneUnaSolaChecada && !$estaChecadaDespuesMedioDia && $jornadalaboralTerminada)
                            <!-- Caso 2: Sin registro de salida (solo entrada) -->
                            <span class="label label-danger rounded sin-registro" title="Sin registro de salida">
                                <i class="fas fa-right-from-bracket"></i> ✕
                            </span>
                            @elseif($registro->hora_entrada && !$registro->hora_salida && $jornadalaboralTerminada)
                            <!-- Caso 3: Sin registro de salida (con entrada registrada) -->
                            <span class="label label-danger rounded sin-registro" title="Sin registro de salida">
                                <i class="fas fa-right-from-bracket"></i> ✕
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
