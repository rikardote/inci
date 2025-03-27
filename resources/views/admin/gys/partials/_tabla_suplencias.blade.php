<div>
    <table class="table table-striped table-condensed">
        <thead>
            <th>Quincena</th>
            <th>Año</th>
            <th>RFC</th>
            <th>C.T.</th>
            <th>Puesto</th>
            <th>Suplente</th>
            <th>Fecha inicial</th>
            <th>Fecha final</th>
            <th>Horas/Dias</th>
            <th>Incidencia</th>
            <th>No.de Empleado</th>
            <th>Trabajador</th>
            <th>Monto</th>
        </thead>
        <tbody>
            @foreach ($suplencias as $suplencia)
                <tr class={{ $suplencia->validarIncidencia($suplencia) ? '' : 'danger' }}>
                    <td>{{ $suplencia->quincena }}</td>
                    <td>{{ $suplencia->year }}</td>
                    <td>{{ $suplencia->rfc }}</td>
                    <td>{{ $suplencia->obtenerDescripcionCentro() }}</td>
                    <td>{{ $suplencia->obtenerDescripcionPuesto($suplencia->puesto) }}</td>
                    <td>{{ $suplencia->nombre_suplente }}</td>
                    <td>{{ fecha_dmy($suplencia->fecha_inicial) }}</td>
                    <td>{{ fecha_dmy($suplencia->fecha_final) }}</td>
                    <td>{{ $suplencia->hodias }}</td>
                    <td>{{ $suplencia->obtenerDescripcionIncidencia() }}</td>
                    <td>{{ $suplencia->num_empleado ?: '-' }}</td>
                    <td>{{ $suplencia->obtenerEmpleado($suplencia->num_empleado) }}</td>
                    <td>{{ $suplencia->monto }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
