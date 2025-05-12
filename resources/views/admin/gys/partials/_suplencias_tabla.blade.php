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
            @if(Auth::user()->username == '332618')
            <th>Acciones</th>
            @endif
        </tr>
    </thead>
    <tbody>

        @forelse($suplenciasPeriodo as $suplencia)
        <tr class="{{ $suplencia->validarIncidencia($suplencia) ? '' : 'danger' }}" data-suplencia-id="{{ $suplencia->id }}">
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
            @if(Auth::user()->username == '332618')
            <td>
                <button class="btn btn-xs btn-warning" onclick="eliminarSuplencia({{ $suplencia->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
            @endif
        </tr>
        @empty
        <tr>
            <td colspan="12" class="text-center">No hay suplencias para este período</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr class="font-weight-bold">
            <td colspan="11" class="text-right">Total:</td>
            <td class="text-right">{{ number_format($suplenciasPeriodo->sum('monto'), 2) }}</td>
        </tr>
    </tfoot>
</table>

<script>
function eliminarSuplencia(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta suplencia?')) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const fila = document.querySelector(`tr[data-suplencia-id="${id}"]`);

        fetch(`/admin/gys/suplencias/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _token: token
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al eliminar la suplencia');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                fila.remove();
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-bottom-center",
                    "timeOut": "1500",
                };
                toastr.success('Suplencia eliminada correctamente.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Ocurrió un error al eliminar la suplencia');
        });
    }
}
</script>
