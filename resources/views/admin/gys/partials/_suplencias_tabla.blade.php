<!-- resources/views/admin/gys/partials/_suplencias_tabla.blade.php -->
<table class="table table-sm">
    <thead>
        <tr>
            <th>Qna</th>
            <th>RFC</th>
            <th>C.T.</th>
            <th>Puesto</th>
            <th>Suplente</th>
            <th>Fecha inicial</th>
            <th>Fecha final</th>
            <th>Horas/Dias</th>
            <th>Incidencia</th>
            <th># Empleado</th>
            <th>Trabajador</th>
            <th class="text-right">Monto</th>
        </tr>
    </thead>
    <tbody>
        @forelse($suplenciasPeriodo as $suplencia)
        <tr class="{{ $suplencia->validarIncidencia($suplencia) ? '' : 'danger' }}">
            <td>{{ $suplencia->quincena }}</td>
            <td>{{ $suplencia->rfc }}</td>
            <td>{{ $suplencia->obtenerDescripcionCentro() }}</td>
            <td>{{ $suplencia->obtenerDescripcionPuesto($suplencia->puesto) }}</td>
            <td>{{ $suplencia->validarSuplente($suplencia->rfc) ?: $suplencia->nombre_suplente.'*' }}</td>
            <td>{{ fecha_dmy($suplencia->fecha_inicial) }}</td>
            <td>{{ fecha_dmy($suplencia->fecha_final) }}</td>
            <td class="text-center">{{ $suplencia->hodias }}</td>
            <td>{{ $suplencia->obtenerDescripcionIncidencia() }}</td>
            <td>{{ $suplencia->num_empleado ?: '-' }}</td>
            <td>{{ $suplencia->obtenerEmpleado($suplencia->num_empleado) }}</td>
            <td class="text-right">{{ $suplencia->monto }}</td>
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
            <td class="text-right">{{ number_format($suplenciasPeriodo->sum('monto'), 2) }}</td>
        </tr>
    </tfoot>
</table>
