<div class="tables-container">
    @foreach($empleados as $num_empleado => $registrosEmpleado)
    <div class="employee-table">
        <div class="employee-name">
            <div class="employee-info">
                <h4>{{ $num_empleado }} - {{ $registrosEmpleado->first()->apellido_paterno }} {{
                    $registrosEmpleado->first()->apellido_materno }} {{ $registrosEmpleado->first()->nombre }}</h4>
            </div>
            <div class="employee-schedule">
                {{ substr($registrosEmpleado->first()->horario_entrada, 0, 5) }} A {{
                substr($registrosEmpleado->first()->horario_salida, 0, 5) }}
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Incidencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrosEmpleado as $registro)
                    <tr class="clickable-row {{ ($registro->retardo && $registro->incidencia != '7') ? 'retardo' : '' }} {{ ($registro->incidencia && !in_array($registro->incidencia, $incidenciasSinColor)) ? 'incidencia' : '' }}"
                        data-empleado="{{ $num_empleado }}" onclick="submitForm(this)">
                        <td>{{ date('d/m/Y', strtotime($registro->fecha)) }}</td>
                        <td>
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

                            @if($tieneUnaSolaChecada && $esMasCercanaASalida)
                            <span class="sin-registro omision-entrada" data-tooltip="Sin registro de entrada">
                                Omisión de entrada
                            </span>
                            @elseif($registro->hora_entrada)
                            {{ substr($registro->hora_entrada, 0, 5) }}
                            @if($registro->retardo && $registro->incidencia != '7' && !($tieneUnaSolaChecada &&
                            $esMasCercanaASalida))
                            <span class="label label-warning">R</span>
                            @endif
                            @endif
                        </td>
                        <td>
                            @if($tieneUnaSolaChecada && $esMasCercanaASalida)
                            {{ substr($registro->hora_salida, 0, 5) }}
                            @elseif($registro->hora_salida && !$tieneUnaSolaChecada && $esJornadaValida)
                            {{ substr($registro->hora_salida, 0, 5) }}
                            @elseif($registro->hora_salida && !$tieneUnaSolaChecada && !$esJornadaValida)
                            <span class="sin-registro omision-salida"
                                data-tooltip="Posible checada duplicada: {{ substr($registro->hora_salida, 0, 5) }}">
                                Omisión de salida
                            </span>
                            @elseif($tieneUnaSolaChecada && !$esMasCercanaASalida && $jornadalaboralTerminada)
                            <span class="sin-registro omision-salida" data-tooltip="Sin registro de salida">
                                Omisión de salida
                            </span>
                            @elseif($registro->hora_entrada && !$tieneUnaSolaChecada && $jornadalaboralTerminada)
                            <span class="sin-registro omision-salida" data-tooltip="Sin registro de salida">
                                Omisión de salida
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($registro->incidencia)
                            <span class="label label-danger">{{ $registro->incidencia }}</span>
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
