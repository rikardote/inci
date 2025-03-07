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
                    
                    // Ajuste importante: definir medio día como las 12:00 PM
                    $medioDia = strtotime('12:00:00');
                    
                    $horaEntrada = $registro->hora_entrada ? strtotime($registro->hora_entrada) : null;
                    $horaSalida = $registro->hora_salida ? strtotime($registro->hora_salida) : null;

                    // Detectar si es una sola checada (entrada y salida iguales)
                    $tieneUnaSolaChecada = $registro->hora_entrada && $registro->hora_entrada === $registro->hora_salida;

                    // Verificar si la checada está después del medio día
                    $horaChecada = $registro->hora_entrada ? strtotime($registro->hora_entrada) : null;
                    $estaChecadaDespuesMedioDia = $horaChecada && $horaChecada > $medioDia;

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

                    // NUEVO: Contar cuántas checadas hay después del mediodía
                    $conteoChecadasDespuesMedioDia = 0;
                    foreach ($checadasDelDia as $c) {
                        $checadaHora = $c->hora_entrada ? strtotime($c->hora_entrada) : null;
                        if ($checadaHora && $checadaHora > $medioDia) {
                            $conteoChecadasDespuesMedioDia++;
                        }
                    }
                    
                    // NUEVO: Es salida por estar después del mediodía, excepto la primera checada después de mediodía
                    $esSalidaPorHorario = $estaChecadaDespuesMedioDia;
                    if ($estaChecadaDespuesMedioDia && $conteoChecadasDespuesMedioDia > 1) {
                        // Si hay varias checadas después del mediodía, todas son salidas
                        // excepto si es la primera del grupo de checadas post-mediodía
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
                    
                    // NUEVO: Determinar si mostrar como omisión de entrada
                    $mostrarOmisionEntrada = ($estaChecadaDespuesMedioDia && 
                                            ($esLaPrimeraChecadaDelDia || 
                                            ($conteoChecadasDespuesMedioDia > 1 && 
                                            $esPrimeraChecadaDespuesMediodia)));

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
                            @if($mostrarOmisionEntrada)
                            <!-- Caso especial: Omisión de entrada en checadas después del mediodía -->
                            <span class="label label-danger rounded sin-registro" title="Sin registro de entrada - checada después del mediodía">
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
                            @if(($estaChecadaDespuesMedioDia && $esSalidaPorHorario) || ($registro->hora_salida && !$tieneUnaSolaChecada))
                            <!-- Caso 1: Salida normal o checada después del mediodía que es considerada salida -->
                            <span>
                                <i class="fa fa-clock-o"></i> {{ substr($registro->hora_salida, 0, 5) }}
                                @if($conteoChecadasDespuesMedioDia > 1 && $esLaUltimaChecadaDelDia && $todasLasChecadasDespuesMediodia)
                                <span class="label label-info rounded" title="Múltiples salidas registradas">
                                    <i class="fa fa-info-circle"></i> {{ $conteoChecadasDespuesMedioDia }}
                                </span>
                                @endif
                            </span>
                            @elseif($tieneUnaSolaChecada && !$estaChecadaDespuesMedioDia && $jornadalaboralTerminada)
                            <!-- Caso 2: Sin registro de salida (solo entrada) -->
                            <span class="label label-danger rounded sin-registro" title="Sin registro de salida">
                                <i class="fa fa-sign-out"></i> ✕
                            </span>
                            @elseif($registro->hora_entrada && !$registro->hora_salida && $jornadalaboralTerminada)
                            <!-- Caso 3: Sin registro de salida (con entrada registrada) -->
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