@extends('layout.main')
@section('title', 'Logs en Tiempo Real')

@section('content')
<div>
    <strong>
        <p>
            Logs de incidencias en tiempo real. Los datos se actualizan automáticamente cada 30 segundos.
        </p>
    </strong>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="status-indicator">
            <span id="update-status" class="label label-success">Actualizado</span>
            <span id="last-update-time"></span>
        </div>
    </div>
    <div class="col-md-6 text-right">
        <button id="refresh-btn" class="btn btn-primary">
            <i class="fa fa-refresh"></i> Actualizar ahora
        </button>
    </div>
</div>

<div class="table-responsive">
    <table id="logs-table" class="table table-striped table-condensed" style="width:100%;">
        <thead>
            <tr>
                <th>Unidad</th>
                <th>Num Empleado</th>
                <th>Empleado</th>
                <th>Codigo</th>
                <th>Fecha Inicial</th>
                <th>Fecha Final</th>
                <th>Total Dias</th>
                <th>Periodo</th>
                <th>Fecha de captura</th>
                <th>Fecha de Borrado</th>
            </tr>
        </thead>
        <tbody id="logs-body">
            <!-- Los datos se cargarán mediante JavaScript -->
        </tbody>
    </table>
</div>

<div id="loading-indicator" style="display:none; text-align:center; padding:20px;">
    <i class="fa fa-spinner fa-spin fa-2x"></i>
    <p>Cargando datos...</p>
</div>
@endsection

@section('js')
<script>
    // Variables globales
    let lastUpdateTime = new Date();
    let updateInterval = 10000; // 30 segundos
    let intervalId = null;
    let isLoading = false;

    // Formatear fecha en formato DD/MM/YYYY
    function formatDate(dateString) {
        if (!dateString) return '';

        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // Formatear fecha y hora en formato DD/MM/YYYY HH:MM:SS
    function formatDateTime(dateString) {
        if (!dateString) return '';

        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        }) + ' ' + date.toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    // Cargar los datos de incidencias desde la API
    function loadIncidencias() {
        if (isLoading) return;

        isLoading = true;
        document.getElementById('loading-indicator').style.display = 'block';
        document.getElementById('update-status').className = 'label label-warning';
        document.getElementById('update-status').textContent = 'Actualizando...';

        fetch('{{ route('api.logs.incidencias') }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                renderTable(data);
                updateStatus(true);
            })
            .catch(error => {
                console.error('Error al cargar los datos:', error);
                updateStatus(false, error.message);
            })
            .finally(() => {
                isLoading = false;
                document.getElementById('loading-indicator').style.display = 'none';
            });
    }

    // Renderizar la tabla con los datos obtenidos
    function renderTable(incidencias) {
        const tableBody = document.getElementById('logs-body');
        tableBody.innerHTML = '';

        if (incidencias.length === 0) {
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = '<td colspan="10" class="text-center">No hay incidencias registradas</td>';
            tableBody.appendChild(emptyRow);
            return;
        }

        incidencias.forEach(incidencia => {
            const row = document.createElement('tr');

            // Aplicar clase 'danger' si la incidencia fue eliminada
            if (incidencia.deleted_at) {
                row.className = 'danger';
            }

            // Accediendo a los datos a través de las relaciones
            const employee = incidencia.employee || {}; // Para evitar errores si es null
            const deparment = employee.deparment || {};
            const codigoInc = incidencia.codigo_de_incidencia || {};
            const periodo = incidencia.periodo || {};

            row.innerHTML = `
                <td>${deparment.code || ''}</td>
                <td align="center">${employee.num_empleado || incidencia.num_empleado || ''}</td>
                <td>${employee.father_lastname || ''} ${employee.mother_lastname || ''} ${employee.name || ''}</td>
                <td align="center">${codigoInc.code ? String(codigoInc.code).padStart(2, '0') : ''}</td>
                <td align="center">${formatDate(incidencia.fecha_inicio)}</td>
                <td align="center">${formatDate(incidencia.fecha_final)}</td>
                <td align="center">${incidencia.total_dias || ''}</td>
                <td align="center">${periodo.periodo ? periodo.periodo + '/' + periodo.year : ''}</td>
                <td align="center">${formatDateTime(incidencia.created_at)}</td>
                <td align="center">${formatDateTime(incidencia.deleted_at)}</td>
            `;

            tableBody.appendChild(row);
        });
    }

    // Actualizar el estado de la actualización
    function updateStatus(success, errorMsg = '') {
        const statusElement = document.getElementById('update-status');
        const timeElement = document.getElementById('last-update-time');

        lastUpdateTime = new Date();
        timeElement.textContent = 'Última actualización: ' + lastUpdateTime.toLocaleTimeString();

        if (success) {
            statusElement.className = 'label label-success';
            statusElement.textContent = 'Actualizado';
        } else {
            statusElement.className = 'label label-danger';
            statusElement.textContent = 'Error';

            // Mostrar mensaje de error
            if (errorMsg) {
                console.error('Error de actualización:', errorMsg);
                // Opcionalmente, mostrar un mensaje al usuario
            }
        }
    }

    // Inicializar la aplicación
    function initApp() {
        // Cargar datos iniciales
        loadIncidencias();

        // Configurar actualización periódica
        intervalId = setInterval(loadIncidencias, updateInterval);

        // Configurar botón de actualización manual
        document.getElementById('refresh-btn').addEventListener('click', () => {
            loadIncidencias();
        });

        // Mostrar tiempo inicial
        document.getElementById('last-update-time').textContent = 'Iniciando...';
    }

    // Iniciar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', initApp);
</script>
@endsection

@section('styles')
<style>
    .status-indicator {
        margin-top: 8px;
    }

    #last-update-time {
        margin-left: 10px;
        color: #666;
    }

    .highlight {
        animation: highlight-animation 2s;
    }

    @keyframes highlight-animation {
        0% {
            background-color: #ffffd0;
        }

        100% {
            background-color: transparent;
        }
    }
</style>
@endsection
