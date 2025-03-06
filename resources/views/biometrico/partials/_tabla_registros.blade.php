<style>
    .tables-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        margin: 20px 0 40px;
    }

    .employee-table {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .employee-name {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        border-bottom: 3px solid #3498db;
    }

    .employee-info h4 {
        margin: 0;
        font-size: 0.95em;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .employee-schedule {
        color: #ecf0f1;
        font-size: 0.9em;
        padding: 3px 10px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
    }

    .table thead th {
        background: #f8f9fa;
        padding: 12px 8px;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody td {
        padding: 8px;
        border-bottom: 1px solid #eef2f7;
    }

    .retardo {
        background-color: rgba(255, 152, 0, 0.1) !important;
    }

    .incidencia {
        background-color: rgba(244, 67, 54, 0.1) !important;
    }

    .label {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 0.85em;
    }

    .label-warning {
        background-color: #ff9800;
        color: white;
    }

    .label-danger {
        background-color: #f44336;
        color: white;
    }

    .sin-registro {
        color: #e74c3c;
        font-weight: 500;
    }

    .omision-entrada {
        cursor: help;
        border-bottom: 1px dashed #e67e22;
    }

    .clickable-row {
        cursor: pointer;
    }

    .clickable-row:hover {
        background-color: rgba(52, 152, 219, 0.1) !important;
    }

    @media (max-width: 1200px) {
        .tables-container {
            grid-template-columns: 1fr;
        }
    }

    [data-tooltip] {
        position: relative;
    }

    [data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        white-space: nowrap;
        z-index: 1000;
        font-size: 12px;
    }
</style>



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
                            $horaLimite = strtotime("+4 hours",
                            strtotime($registrosEmpleado->first()->horario_entrada));
                            $horaEntrada = $registro->hora_entrada ? strtotime($registro->hora_entrada) : null;
                            $tieneAlgunRegistro = $registro->hora_entrada || $registro->hora_salida;
                            @endphp

                            @if($registro->hora_entrada)
                            @if($horaEntrada <= $horaLimite) {{ substr($registro->hora_entrada, 0, 5) }}
                                @if($registro->retardo && $registro->incidencia != '7')
                                <span class="label label-warning">R</span>
                                @endif
                                @else
                                <span class="sin-registro omision-entrada"
                                    data-tooltip="Checada: {{ substr($registro->hora_entrada, 0, 5) }}">
                                    Omisión de entrada
                                </span>
                                @endif
                                @endif
                        </td>
                        <td>
                            @if($registro->hora_salida)
                            {{ substr($registro->hora_salida, 0, 5) }}
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
