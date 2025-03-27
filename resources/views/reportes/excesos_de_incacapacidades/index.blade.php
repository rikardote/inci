@extends('layout.main')
@section('title', 'Reporte de Exceso de Incapacidades')
@section('content')
<style>
    .estado-activo {
        color: #dc3545;
        font-weight: bold;
    }
    .estado-historico {
        color: #6c757d;
    }
    .criterio-contador {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 2px 8px;
        border-radius: 12px;
        margin-left: 8px;
        font-size: 0.9em;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-body">
                    @if(count($data) > 0)
                        @php
                            // Inicializar contadores para cada criterio
                            $criterio1 = 0; // Menos de 1 año
                            $criterio2 = 0; // Entre 1 y 4 años
                            $criterio3 = 0; // Entre 5 y 9 años
                            $criterio4 = 0; // 10 o más años

                            // Contar cuántos empleados caen en cada categoría
                            foreach($data as $numEmpleado => $info) {
                                $incapacidades = $info['incapacidades'];
                                $totalDias = $info['total_dias'];

                                if (empty($incapacidades)) continue;

                                $fechaIngreso = new Carbon\Carbon($incapacidades[0]->fecha_ingreso);
                                $hoy = Carbon\Carbon::now();
                                $antiguedad = $fechaIngreso->diffInYears($hoy);

                                if ($antiguedad < 1 && $totalDias > 15) {
                                    $criterio1++;
                                } elseif ($antiguedad >= 1 && $antiguedad <= 4 && $totalDias > 30) {
                                    $criterio2++;
                                } elseif ($antiguedad >= 5 && $antiguedad <= 9 && $totalDias > 45) {
                                    $criterio3++;
                                } elseif ($antiguedad >= 10 && $totalDias > 60) {
                                    $criterio4++;
                                }
                            }

                            // Total de registros
                            $totalRegistros = count($data);
                        @endphp

                        <!-- Información de criterios -->
                        <div class="alert alert-info">
                            <h4><i class="fa fa-info-circle"></i> Criterios de exceso de incapacidades <span class="badge badge-pill badge-primary">Total: {{ $totalRegistros }}</span></h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul>
                                        <li>Menos de 1 año de antigüedad: <strong>Más de 15 días</strong> <span class="criterio-contador">{{ $criterio1 }}</span></li>
                                        <li>Entre 1 y 4 años de antigüedad: <strong>Más de 30 días</strong> <span class="criterio-contador">{{ $criterio2 }}</span></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li>Entre 5 y 9 años de antigüedad: <strong>Más de 45 días</strong> <span class="criterio-contador">{{ $criterio3 }}</span></li>
                                        <li>10 o más años de antigüedad: <strong>Más de 60 días</strong> <span class="criterio-contador">{{ $criterio4 }}</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla principal -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="10%">Num. Empleado</th>
                                        <th width="25%">Nombre</th>
                                        <th width="10%">Antigüedad</th>
                                        <th width="10%">Total días</th>
                                        <th width="20%">Período de Análisis</th>
                                        <th width="10%">Estado</th>
                                        <th width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $numEmpleado => $info)
                                        @php
                                            $incapacidades = $info['incapacidades'];
                                            $totalDias = $info['total_dias'];

                                            if(empty($incapacidades)) continue;

                                            $fechaIngreso = new Carbon\Carbon($incapacidades[0]->fecha_ingreso);
                                            $hoy = Carbon\Carbon::now();
                                            $antiguedad = $fechaIngreso->diffInYears($hoy);

                                            // Verificar si la última incapacidad es del mes actual o posterior a hoy
                                            if (count($incapacidades) > 0) {
                                                $ultimaIncapacidad = $incapacidades[count($incapacidades)-1];
                                                $fechaUltimaIncapacidad = new Carbon\Carbon($ultimaIncapacidad->fecha_final);

                                                // Considerar como activo si es del mes actual o posterior a hoy
                                                $esMesActual = ($fechaUltimaIncapacidad->month == $hoy->month &&
                                                                $fechaUltimaIncapacidad->year == $hoy->year);
                                                $esFechaFutura = $fechaUltimaIncapacidad->gt($hoy);
                                                $esIncapacidadReciente = $esMesActual || $esFechaFutura;
                                            } else {
                                                $esIncapacidadReciente = false;
                                            }
                                        @endphp
                                        <tr @if($esIncapacidadReciente) class="table-danger" @endif>
                                            <td class="text-center">{{ $numEmpleado }}</td>
                                            <td>
                                                @if(!empty($incapacidades))
                                                    {{ $incapacidades[0]->name }} {{ $incapacidades[0]->father_lastname }} {{ $incapacidades[0]->mother_lastname }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $antiguedad }} años</td>
                                            <td class="text-center font-weight-bold">{{ $totalDias }}</td>
                                            <td class="text-center">
                                                @if(!empty($incapacidades) && count($incapacidades) > 0)
                                                    {{ fecha_dmy($incapacidades[0]->fecha_inicio) }} -
                                                    {{ fecha_dmy($incapacidades[count($incapacidades)-1]->fecha_final) }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($esIncapacidadReciente)
                                                    <span class="estado-activo">ACTIVO</span>
                                                @else
                                                    <span class="estado-historico">Histórico</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-info btn-sm detail-toggle" type="button" data-toggle="collapse" data-target="#collapse{{ $numEmpleado }}" aria-expanded="false" aria-controls="collapse{{ $numEmpleado }}">
                                                    <i class="fa fa-search"></i> Ver detalles
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="collapse-row">
                                            <td colspan="7" class="p-0">
                                                <div class="collapse" id="collapse{{ $numEmpleado }}">
                                                    <div class="card card-body m-2">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <h5 class="card-title">
                                                                <i class="fa fa-user-circle"></i> Detalles del empleado:
                                                                {{ !empty($incapacidades) ? $incapacidades[0]->name . ' ' . $incapacidades[0]->father_lastname : '' }}
                                                                ({{ $numEmpleado }})
                                                            </h5>
                                                            <button class="btn btn-sm btn-secondary collapse-close" type="button" data-toggle="collapse" data-target="#collapse{{ $numEmpleado }}" aria-expanded="false" aria-controls="collapse{{ $numEmpleado }}">
                                                                <i class="fa fa-times"></i> Cerrar
                                                            </button>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered table-striped">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th>Fecha Inicio</th>
                                                                        <th>Fecha Final</th>
                                                                        <th class="text-center">Días</th>
                                                                        <th>Descripción</th>
                                                                        <th>Diagnóstico</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($incapacidades as $incapacidad)
                                                                        <tr>
                                                                            <td class="text-center">{{ fecha_dmy($incapacidad->fecha_inicio) }}</td>
                                                                            <td class="text-center">{{ fecha_dmy($incapacidad->fecha_final) }}</td>
                                                                            <td class="text-center font-weight-bold">{{ $incapacidad->total_dias }}</td>
                                                                            <td>{{ $incapacidad->codigo_descripcion ?? 'N/A' }}</td>
                                                                            <td>{{ $incapacidad->diagnostico }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot class="table-active">
                                                                    <tr>
                                                                        <th colspan="2" class="text-right">Total días:</th>
                                                                        <th class="text-center">{{ $totalDias }}</th>
                                                                        <th colspan="2"></th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h4><i class="fa fa-info-circle"></i> Sin resultados</h4>
                            <p>No se encontraron empleados con exceso de incapacidades según los criterios establecidos:</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul>
                                        <li>Menos de 1 año de antigüedad: <strong>Más de 15 días</strong></li>
                                        <li>Entre 1 y 4 años de antigüedad: <strong>Más de 30 días</strong></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul>
                                        <li>Entre 5 y 9 años de antigüedad: <strong>Más de 45 días</strong></li>
                                        <li>10 o más años de antigüedad: <strong>Más de 60 días</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Variable para mantener una referencia al detalle actualmente abierto
    var currentOpenDetail = null;

    // Cambiar el texto de los botones cuando se expande/colapsa
    $('.collapse').on('show.bs.collapse', function() {
        var $collapse = $(this);
        var employeeId = $collapse.attr('id').replace('collapse', '');
        $('button[data-target="#' + $collapse.attr('id') + '"]').html('<i class="fa fa-minus"></i> Ocultar detalles');
    }).on('hide.bs.collapse', function() {
        var $collapse = $(this);
        var employeeId = $collapse.attr('id').replace('collapse', '');
        $('button[data-target="#' + $collapse.attr('id') + '"]').html('<i class="fa fa-search"></i> Ver detalles');

        // Ocultar la fila contenedora
        var $row = $collapse.closest('.collapse-row');
        setTimeout(function() {
            $row.hide();
        }, 300);
    });

    // Asegurar que las filas de detalle se muestren correctamente
    $('.collapse').on('shown.bs.collapse', function() {
        var $collapse = $(this);
        $collapse.closest('.collapse-row').show();

        // Actualizar la referencia al detalle actualmente abierto
        currentOpenDetail = $collapse;
    });

    // Inicialmente ocultar todas las filas de detalle
    $('.collapse-row').hide();

    // Al hacer clic en cualquier botón, cerrar otros detalles abiertos
    $('.detail-toggle').click(function() {
        var $button = $(this);
        var target = $button.data('target');
        var $target = $(target);
        var $row = $button.closest('tr').next('.collapse-row');

        // Si hay un detalle abierto que no es el actual, cerrarlo primero
        if (currentOpenDetail && currentOpenDetail.attr('id') !== $target.attr('id')) {
            // Cerrar explícitamente el detalle anterior
            currentOpenDetail.collapse('hide');

            // Ocultar su fila contenedora
            currentOpenDetail.closest('.collapse-row').hide();

            // Asegurarnos de que el botón asociado vuelva a "Ver detalles"
            $('button[data-target="#' + currentOpenDetail.attr('id') + '"]')
                .html('<i class="fa fa-search"></i> Ver detalles');
        }

        // Mostrar la fila contenedora para el elemento actual antes de expandir
        if (!$target.hasClass('show')) {
            $row.show();
        }
    });

    // Al cerrar un detalle usando el botón de cierre
    $('.collapse-close').click(function() {
        // El evento hide.bs.collapse manejará el ocultamiento de la fila
        currentOpenDetail = null; // Limpiar la referencia al detalle abierto
    });
});
</script>
@endsection
