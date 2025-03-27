<div class="mt-4 row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Reporte Mensual</h3>
                <a href="{{ route('exportar.reporte.mensual') }}" class="btn btn-success" aria-label="Exportar reporte mensual a Excel">
                    <i class="fas fa-file-excel"></i> Exportar
                </a>
            </div>
            <br>
            <div class="card-body">
                @php
                $reporte = App\Suplencia::obtenerReporteMensual();
                $meses = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
                4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
                10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                ];
                @endphp

                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Mes</th>
                            <th class="text-end">Asignado</th>
                            <th class="text-end">Ejercido</th>
                            <th class="text-end">Disponible</th>
                            <th class="text-end">% Ejercido</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reporte['mensual'] as $mes => $datos)
                        <tr>
                            <td>{{ $meses[$mes] }}</td>
                            <td class="text-end">$ {{ number_format($datos['monto_asignado'], 2) }}</td>
                            <td class="text-end">$ {{ number_format($datos['monto_ejercido'], 2) }}</td>
                            <td class="text-end">$ {{ number_format($datos['disponible'], 2) }}</td>
                            <td class="text-end">{{ number_format($datos['porcentaje_ejercido'], 2) }}%</td>
                            <td class="text-center">
                                @if($datos['porcentaje_ejercido'] > 100)
                                <span class="badge badge-danger">Excedido</span>
                                @elseif($datos['porcentaje_ejercido'] >= 90)
                                <span class="badge badge-warning">Próximo al límite</span>
                                @else
                                <span class="badge badge-success">Normal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td>Total Anual</td>
                            <td class="text-end">$ {{ number_format($reporte['totales']['monto_asignado'], 2) }}</td>
                            <td class="text-end">$ {{ number_format($reporte['totales']['monto_ejercido'], 2) }}</td>
                            <td class="text-end">$ {{ number_format($reporte['totales']['disponible'], 2) }}</td>
                            <td class="text-end">{{ number_format($reporte['totales']['porcentaje_ejercido'], 2) }}%
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
