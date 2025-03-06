<div>
    <h3>Ejercido por Quincena y Centro</h3>
    <a href="{{ route('exportar.toda.suplencias') }}" class="btn btn-success">
        <i class="fa fa-file-excel-o"></i> Exportar
    </a>
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th class="text-center">Quincena</th>
                @foreach ($montosPorQuincenaCentro['centrosOriginales'] as $index => $centroOriginal)
                <th class="text-right">{{ $montosPorQuincenaCentro['centros'][$index] }}</th>
                @endforeach
                <th class="text-right">Total</th>
                <th class="text-center">Exportar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($montosPorQuincenaCentro['periodos'] as $periodo => $datos)
            @php
            list($year, $quincena) = explode('.', $periodo);
            @endphp
            <tr data-toggle="collapse" data-target="#quincena-{{ $year }}-{{ $quincena }}" style="cursor: pointer;">
                <td class="text-center">{{ $quincena }}</td>
                @foreach ($montosPorQuincenaCentro['centrosOriginales'] as $centro)
                <td class="text-right">
                    @php
                    $montoRegistro = $datos->where('centro', $centro)->first();
                    $monto = $montoRegistro ? $montoRegistro->monto_total : 0;
                    @endphp
                    {{ number_format($monto, 2) }}
                </td>
                @endforeach
                <td class="text-right font-weight-bold">{{ number_format($datos->sum('monto_total'), 2) }}</td>
                <td class="text-center">
                    <a href="{{ route('exportar.suplentes.quincena', ['year' => $year, 'quincena' => $quincena]) }}"
                        class="">
                        <i class="fa fa-file-excel-o fa-2x" style="color: green;"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td colspan="{{ count($montosPorQuincenaCentro['centros']) + 3 }}" class="p-0">
                    <div id="quincena-{{ $year }}-{{ $quincena }}" class="collapse">
                        <div class="p-3">
                            @php
                            $suplenciasPeriodo = $suplencias->filter(function ($suplencia) use ($year, $quincena) {
                            return $suplencia->year == $year && $suplencia->quincena == $quincena;
                            });
                            @endphp
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Quincena</th>
                                        <th>RFC</th>
                                        <th>C.T.</th>
                                        <th>Puesto</th>
                                        <th>Suplente</th>
                                        <th>Fecha inicial</th>
                                        <th>Fecha final</th>
                                        <th>Incidencia</th>
                                        <th>No.de Empleado</th>
                                        <th>Trabajador</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($suplenciasPeriodo as $suplencia)
                                    <tr class="{{ $suplencia->validarIncidencia($suplencia) ? '' : 'danger' }}">
                                        <td>{{ $suplencia->quincena }}</td>
                                        <td>{{ $suplencia->rfc }}</td>
                                        <td>{{ $suplencia->obtenerDescripcionCentro() }}</td>
                                        <td>{{ $suplencia->obtenerDescripcionPuesto($suplencia->puesto) }}</td>
                                        <td>{{ $suplencia->validarSuplente($suplencia->rfc) ?:
                                            $suplencia->nombre_suplente.'*' }}
                                        </td>
                                        <td>{{ fecha_dmy($suplencia->fecha_inicial) }}</td>
                                        <td>{{ fecha_dmy($suplencia->fecha_final) }}</td>
                                        <td>{{ $suplencia->obtenerDescripcionIncidencia() }}</td>
                                        <td>{{ $suplencia->num_empleado ?: '-' }}</td>
                                        <td>{{ $suplencia->obtenerEmpleado($suplencia->num_empleado) }}</td>
                                        <td>{{ $suplencia->monto }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No hay suplencias para este período</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="font-weight-bold">
                                        <td colspan="10" class="text-right">Total:</td>
                                        <td class="text-right">{{ number_format($suplenciasPeriodo->sum('monto'), 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td colspan="1">Total por Centro</td>
                @foreach ($montosPorQuincenaCentro['centrosOriginales'] as $centro)
                <td class="text-right">
                    @php
                    $total = $montosPorQuincenaCentro['periodos']->sum(function ($datos) use ($centro) {
                    $montoRegistro = $datos->where('centro', $centro)->first();
                    return $montoRegistro ? $montoRegistro->monto_total : 0;
                    });
                    @endphp
                    {{ number_format($total, 2) }}
                </td>
                @endforeach
                <td class="text-right"><span class="font-weight-bold">{{
                        number_format($montosPorQuincenaCentro['periodos']->sum(function ($datos) { return
                        $datos->sum('monto_total'); }), 2) }}</span></td>
                <td class="text-center"></td>
            </tr>
        </tfoot>
    </table>
</div>
