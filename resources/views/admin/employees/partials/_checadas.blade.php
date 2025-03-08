<!-- filepath: /d:/swtools/laragon/www/incidencias/resources/views/admin/employees/partials/_checadas.blade.php -->
@php
// Variables iniciales
$horarioParts = explode(" A ", $employe->horario);
$horaEntradaEmpleado = isset($horarioParts[0]) ? trim($horarioParts[0]) : "08:00";
$horaSalidaEmpleado = isset($horarioParts[1]) ? trim($horarioParts[1]) : "16:00";
$fechasRegistradas = [];

// Obtener las últimas 15 fechas
$ultimos15Dias = [];
for ($i = 0; $i < 15; $i++) { $ultimos15Dias[]=date('Y-m-d', strtotime("-$i days")); } @endphp <div
    class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th width="12%"><i class="fas fa-calendar"></i> Fecha</th>
                <th width="20%"><i class="fas fa-right-to-bracket"></i> Entrada</th>
                <th width="20%"><i class="fas fa-right-from-bracket"></i> Salida</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checadas->groupBy(function($checada) {
            return date('Y-m-d', strtotime($checada->fecha));
            }) as $fecha => $checadasDia)
            @if (in_array($fecha, $ultimos15Dias))
            @php
            // Registro de fechas procesadas
            $fechaFormateada = fecha_dmy($checadasDia->first()->fecha);
            $fechasRegistradas[] = $fecha;

            // Obtener primera y última checada del día
            $checadasOrdenadas = $checadasDia->sortBy('fecha');
            $horaEntrada = date('H:i', strtotime($checadasOrdenadas->first()->fecha));
            $horaSalida = date('H:i', strtotime($checadasOrdenadas->last()->fecha));
            @endphp

            <tr>
                <td><i class="fas fa-calendar"></i> {{ $fechaFormateada }}</td>
                <td><i class="fas fa-clock"></i> {{ $horaEntrada }}</td>
                <td><i class="fas fa-clock"></i> {{ $horaSalida }}</td>
            </tr>
            @endif
            @endforeach

            @if(count($fechasRegistradas) == 0)
            <tr>
                <td colspan="3" class="text-center">
                    <i class="fas fa-circle-info"></i>
                    No hay registros de asistencia para mostrar en los últimos 15 días.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    </div>
