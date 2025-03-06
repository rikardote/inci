<div class="tables-container">
    <div class="row">
        @php $contador = 0; @endphp
        @foreach($empleados as $num_empleado => $registrosEmpleado)
        <!-- Cada empleado ocupa media fila (6 columnas) -->
        <div class="col-md-6">
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
                                <th width="20%"><i class="fa fa-calendar"></i> Fecha</th>
                                <th width="30%"><i class="fa fa-sign-in"></i> Entrada</th>
                                <th width="30%"><i class="fa fa-sign-out"></i> Salida</th>
                                <th width="20%"><i class="fa fa-exclamation-triangle"></i> Incidencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrosEmpleado as $registro)
                            @php
                            // Definir todas las variables necesarias una sola vez
                            $horarioEntrada = strtotime($registrosEmpleado->first()->horario_entrada);
                            $horarioSalida = strtotime($registrosEmpleado->first()->horario_salida);
                            $medioDia = ($horarioEntrada + $horarioSalida) / 2;

                            $horaEntrada = $registro->hora_entrada ? strtotime($registro->hora_entrada) : null;
                            $horaSalida = $registro->hora_salida ? strtotime($registro->hora_salida) : null;
                            $tieneUnaSolaChecada = $registro->hora_entrada && $registro->hora_entrada ===
                            $registro->hora_salida;

                            $horaChecada = $registro->hora_entrada ? strtotime($registro->hora_entrada) : null;
                            $esMasCercanaASalida = $horaChecada && $tieneUnaSolaChecada && ($horaChecada > $medioDia);

                            $tiempoMinimoJornada = 3 * 60 * 60; // 3 horas en segundos
                            $esJornadaValida = $horaEntrada && $horaSalida && ($horaSalida - $horaEntrada) >=
                            $tiempoMinimoJornada;

                            // Variables para jornada laboral
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
                                    @if($tieneUnaSolaChecada && $esMasCercanaASalida)
                                    <span class="badge bg-light-red sin-registro" title="Sin registro de entrada">
                                        <i class="fa fa-sign-in"></i> ✕
                                    </span>
                                    @elseif($registro->hora_entrada)
                                    <span
                                        class="{{ $registro->retardo && $registro->incidencia != '7' ? 'text-warning font-weight-bold' : '' }}">
                                        <i class="fa fa-clock-o"></i> {{ substr($registro->hora_entrada, 0, 5) }}
                                    </span>
                                    @if($registro->retardo && $registro->incidencia != '7' && !($tieneUnaSolaChecada &&
                                    $esMasCercanaASalida))
                                    <span class="badge bg-light-red">R</span>
                                    @endif
                                    @endif
                                </td>
                                <td>
                                    @if($tieneUnaSolaChecada && $esMasCercanaASalida)
                                    <span>
                                        <i class="fa fa-clock-o"></i> {{ substr($registro->hora_salida, 0, 5) }}
                                    </span>
                                    @elseif($registro->hora_salida && !$tieneUnaSolaChecada && $esJornadaValida)
                                    <span>
                                        <i class="fa fa-clock-o"></i> {{ substr($registro->hora_salida, 0, 5) }}
                                    </span>
                                    @elseif($registro->hora_salida && !$tieneUnaSolaChecada && !$esJornadaValida)
                                    <span class="badge bg-light-red sin-registro"
                                        title="Posible checada duplicada: {{ substr($registro->hora_salida, 0, 5) }}">
                                        <i class="fa fa-exclamation-circle"></i> Omisión de salida
                                    </span>
                                    @elseif($tieneUnaSolaChecada && !$esMasCercanaASalida && $jornadalaboralTerminada)
                                    <span class="badge bg-light-red sin-registro" title="Sin registro de salida">
                                        <i class="fa fa-sign-out"></i> ✕
                                    </span>
                                    @elseif($registro->hora_entrada && !$tieneUnaSolaChecada &&
                                    $jornadalaboralTerminada)
                                    <span class="badge bg-light-red sin-registro" title="Sin registro de salida">
                                        <i class="fa fa-sign-out"></i> ✕
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    @if($registro->incidencia)
                                    <span class="badge bg-light-red">{{ $registro->incidencia }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @php $contador++; @endphp
        @if($contador % 2 == 0)
    </div>
    <div class="row">
        @endif
        @endforeach

        <!-- Cerrar la última fila si quedó abierta -->
        @if($contador % 2 != 0)
        <div class="col-md-6">
            <!-- Espacio vacío para mantener el balance -->
        </div>
    </div>
    @endif
</div>
</div>

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
