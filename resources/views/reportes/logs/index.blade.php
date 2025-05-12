@extends('layout.main')
@section('title', 'Monitor de Incidencias en Tiempo Real')

@section('content')
<div class="dashboard-container">
    <!-- Cabecera -->
    <div class="dashboard-header">
        <div class="header-title">
            <h2><i class="fa fa-history"></i> Monitor de Actividad</h2>
            <p class="text-muted">Seguimiento de incidencias en tiempo real.</p>
        </div>

        <div class="header-actions">
            <div class="status-indicator">
                <span id="update-status" class="label label-success rounded">
                    <i class="fa fa-check-circle"></i> Actualizado
                </span>
                <span id="last-update-time" class="text-muted"></span>
            </div>

            <button id="refresh-btn" class="btn btn-primary">
                <i class="fa fa-refresh"></i> Actualizar ahora
            </button>
        </div>
    </div>

    <!-- Tabla de registros -->
    <div class="panel-body">
        <div class="table-responsive">
            <table id="logs-table" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th><i class="fa fa-building-o"></i> Unidad</th>
                        <th><i class="fa fa-id-card-o"></i> Núm. Emp.</th>
                        <th><i class="fa fa-user"></i> Empleado</th>
                        <th><i class="fa fa-code"></i> Código</th>
                        <th><i class="fa fa-calendar"></i> Fecha Inicial</th>
                        <th><i class="fa fa-calendar-check-o"></i> Fecha Final</th>
                        <th><i class="fa fa-calculator"></i> Días</th>
                        <th><i class="fa fa-clock-o"></i> Periodo</th>
                        <th><i class="fa fa-plus-circle"></i> F. Captura</th>
                        <th><i class="fa fa-minus-circle"></i> F. Borrado</th>
                    </tr>
                </thead>
                <tbody id="logs-body">
                    <!-- Los registros se cargarán aquí -->
                </tbody>
            </table>
        </div>

        <div id="loading-indicator" style="display:none;">
            <div class="loader-container">
                <div class="loader"></div>
                <p>Cargando datos...</p>
            </div>
        </div>

        <div id="empty-indicator" style="display:none;" class="text-center p-5">
            <i class="fa fa-folder-open-o fa-4x text-muted"></i>
            <p class="lead text-muted">No se encontraron registros</p>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    /* [Mantén todos tus estilos CSS actuales] */
    .dashboard-container { padding: 0 0 30px 0; }
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    /* ... (todo el CSS que ya tenías) ... */
    .highlight-new { background-color: #e8f4fd !important; transition: background-color 0.5s ease; }
    .new-row {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }
    .new-row.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endsection

@section('js')
<script>
    // Configuración
    const SERVER_TIMEZONE = 'UTC';
    let lastUpdateTime = new Date();
    let updateInterval = 10000; // 10 segundos
    let intervalId = null;
    let isLoading = false;
    let lastRecordId = null;
    let currentPage = 1;
    let isLastPage = false;
    let pageSize = 20;

    // Variables globales (agregar estas)
    let scrollObserver = null;
    let lastScrollTime = 0;

    // Funciones de formato
    function formatDateTime(dateString) {
        if (!dateString) return '-';
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return '-';
            const adjustedDate = new Date(date.getTime() - (7 * 60 * 60 * 1000));
            return `${String(adjustedDate.getDate()).padStart(2,'0')}/${String(adjustedDate.getMonth()+1).padStart(2,'0')}/${adjustedDate.getFullYear()} ${String(adjustedDate.getHours()).padStart(2,'0')}:${String(adjustedDate.getMinutes()).padStart(2,'0')}:${String(adjustedDate.getSeconds()).padStart(2,'0')}`;
        } catch (e) {
            console.error('Error formateando fecha:', e);
            return '-';
        }
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        try {
            const parts = dateString.split(/[-T:]/);
            const date = new Date(parts[0], parts[1]-1, parts[2]);
            return isNaN(date.getTime()) ? '-' : `${String(date.getDate()).padStart(2,'0')}/${String(date.getMonth()+1).padStart(2,'0')}/${date.getFullYear()}`;
        } catch (e) {
            console.error('Error formateando fecha:', e);
            return '-';
        }
    }

    // Función para añadir nuevos registros al principio con scroll
    function prependNewRows(incidencias) {
        const tableBody = document.getElementById('logs-body');

        // Verificar duplicados antes de añadir
        incidencias.forEach(incidencia => {
            if (document.querySelector(`tr[data-record-id="${incidencia.id}"]`)) return;

            const row = document.createElement('tr');
            row.dataset.recordId = incidencia.id;
            row.classList.add('new-row', 'highlight-new');

            // Aplicar clase 'danger' si la incidencia fue eliminada
            if (incidencia.deleted_at) {
                row.className = 'danger';
            }

            // Accediendo a los datos a través de las relaciones
            const employee = incidencia.employee || {};
            const deparment = employee.deparment || {};
            const codigoInc = incidencia.codigodeincidencia || {};
            const periodo = incidencia.periodo || {};

            row.innerHTML = `
                <td><span class="badge">${deparment.code || '-'}</span> ${deparment.name || ''}</td>
                <td align="center">${employee.num_empleado || ''}</td>
                <td>${employee.father_lastname || ''} ${employee.mother_lastname || ''} ${employee.name || ''}</td>
                <td align="center">
                    <span class="label label-primary rounded">
                        ${codigoInc.code ? String(codigoInc.code).padStart(2, '0') : '-'}
                    </span>
                </td>
                <td>${formatDate(incidencia.fecha_inicio)}</td>
                <td>${formatDate(incidencia.fecha_final)}</td>
                <td>${incidencia.total_dias || '-'}</td>
                <td>${periodo.periodo ? periodo.periodo + '/' + periodo.year : '-'}</td>
                <td>${formatDateTime(incidencia.created_at, true)}</td>
                <td>
                    ${incidencia.deleted_at ?
                        `<span class="text-danger">${formatDateTime(incidencia.deleted_at, true)}</span>` :
                        '<span class="text-muted">-</span>'}
                </td>
            `;

            if (tableBody.firstChild) {
                tableBody.insertBefore(row, tableBody.firstChild);
            } else {
                tableBody.appendChild(row);
            }

            // Animación
            setTimeout(() => {
                row.classList.add('visible');
                setTimeout(() => row.classList.remove('highlight-new'), 3000);
            }, 50);
        });
    }

    // Función para configurar el scroll infinito
    function setupInfiniteScroll() {
        // Limpiar observador anterior si existe
        if (scrollObserver) {
            scrollObserver.disconnect();
        }

        // Crear elemento trigger
        let scrollTrigger = document.getElementById('scroll-trigger');
        if (!scrollTrigger) {
            scrollTrigger = document.createElement('div');
            scrollTrigger.id = 'scroll-trigger';
            scrollTrigger.style.height = '1px';
            document.querySelector('.panel-body').appendChild(scrollTrigger);
        }

        // Configurar el IntersectionObserver
        scrollObserver = new IntersectionObserver((entries) => {
            const entry = entries[0];
            const now = Date.now();

            // Debounce de 1 segundo
            if (now - lastScrollTime < 1000) return;

            if (entry.isIntersecting && !isLoading && !isLastPage) {
                lastScrollTime = now;
                loadIncidencias(currentPage + 1, false, true);
            }
        }, {
            root: null,
            rootMargin: '0px 0px 100px 0px',
            threshold: 0.1
        });

        scrollObserver.observe(scrollTrigger);
    }

    // Modificar la función loadIncidencias para soportar scroll
    async function loadIncidencias(page = 1, resetData = true, shouldPreserveScroll = false) {
        if (isLoading) return;

        const scrollPosition = shouldPreserveScroll ?
            document.documentElement.scrollTop || document.body.scrollTop : 0;

        isLoading = true;

        // Mostrar indicador de carga
        if (page === 1) {
            document.getElementById('loading-indicator').style.display = 'block';
            document.getElementById('empty-indicator').style.display = 'none';
        }

        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 segundos

            const response = await fetch(`{{ route('api.logs.incidencias') }}?page=${page}&limit=${pageSize}`, {
                signal: controller.signal
            });
            clearTimeout(timeoutId);
            if (!response.ok) throw new Error('Error en la respuesta del servidor');

            const data = await response.json();
            const newIncidencias = data.data || data;

            if (resetData) {
                document.getElementById('logs-body').innerHTML = '';
                if (newIncidencias.length > 0) {
                    lastRecordId = newIncidencias[0].id;
                }
            }

            renderTable(newIncidencias, !resetData);
            isLastPage = data.last_page ? (page >= data.last_page) : (newIncidencias.length < pageSize);
            currentPage = page;

            // Restaurar posición de scroll si es necesario
            if (shouldPreserveScroll) {
                requestAnimationFrame(() => {
                    window.scrollTo(0, scrollPosition);
                });
            }

            // Configurar scroll infinito si no es la última página
            if (!isLastPage) {
                setupInfiniteScroll();
            } else if (scrollObserver) {
                scrollObserver.disconnect();
            }

            updateStatus(true);
        } catch (error) {
            console.error('Error:', error);
            updateStatus(false, error.message);
        } finally {
            isLoading = false;
            document.getElementById('loading-indicator').style.display = 'none';
        }
    }

    // Renderizar tabla
    function renderTable(incidencias, append = false) {
        const tableBody = document.getElementById('logs-body');
        if (!append) tableBody.innerHTML = '';

        if (incidencias.length === 0 && !append) {
            document.getElementById('empty-indicator').style.display = 'block';
            return;
        }

        incidencias.forEach((incidencia, index) => {
            const row = document.createElement('tr');
            if (incidencia.id === lastRecordId) {
                row.classList.add('highlight-new');
                setTimeout(() => row.classList.remove('highlight-new'), 3000);
            }

            if (incidencia.deleted_at) row.classList.add('danger');

            const employee = incidencia.employee || {};
            const deparment = employee.deparment || {};
            const codigoInc = incidencia.codigodeincidencia || {};
            const periodo = incidencia.periodo || {};

            row.innerHTML = `
                <td><span class="badge">${deparment.code || '-'}</span> ${deparment.name || ''}</td>
                <td align="center">${employee.num_empleado || ''}</td>
                <td>${employee.father_lastname || ''} ${employee.mother_lastname || ''} ${employee.name || ''}</td>
                <td align="center">
                    <span class="label label-primary rounded">
                        ${codigoInc.code ? String(codigoInc.code).padStart(2, '0') : '-'}
                    </span>
                </td>
                <td>${formatDate(incidencia.fecha_inicio)}</td>
                <td>${formatDate(incidencia.fecha_final)}</td>
                <td>${incidencia.total_dias || '-'}</td>
                <td>${periodo.periodo ? periodo.periodo + '/' + periodo.year : '-'}</td>
                <td>${formatDateTime(incidencia.created_at)}</td>
                <td>
                    ${incidencia.deleted_at ?
                        `<span class="text-danger">${formatDateTime(incidencia.deleted_at)}</span>` :
                        '<span class="text-muted">-</span>'}
                </td>
            `;

            tableBody.appendChild(row);
        });
    }

    // Verificar actualizaciones
    async function checkForUpdates() {
        try {
            const response = await fetch('{{ route('api.logs.checkForUpdates') }}?last_update=' + lastUpdateTime.toISOString());
            if (!response.ok) throw new Error('Error checking for updates');

            const data = await response.json();
            if (data.has_updates) {
                const newData = await fetch(`{{ route('api.logs.incidencias') }}?limit=10&sort=desc`);
                if (!newData.ok) throw new Error('Error en la respuesta del servidor');

                const newIncidencias = await newData.json();
                if (newIncidencias.data.length > 0) {
                    const filteredIncidencias = newIncidencias.data.filter(inc => inc.id > lastRecordId);
                    if (filteredIncidencias.length > 0) {
                        lastRecordId = filteredIncidencias[0].id;
                        prependNewRows(filteredIncidencias);
                    }
                }
                updateStatus(true);
            } else {
                updateStatus(true);
            }
        } catch (error) {
            console.error('Error checking for updates:', error);
            updateStatus(false, error.message);
        }
    }

    // Actualizar el estado de la actualización
    function updateStatus(success, errorMsg = '') {
        const statusElement = document.getElementById('update-status');
        const timeElement = document.getElementById('last-update-time');

        lastUpdateTime = new Date();
        timeElement.textContent = 'Última actualización: ' + lastUpdateTime.toLocaleTimeString();

        if (success) {
            statusElement.className = 'label label-success rounded';
            statusElement.innerHTML = '<i class="fa fa-check-circle"></i> Actualizado';
        } else {
            statusElement.className = 'label label-danger rounded';
            statusElement.innerHTML = '<i class="fa fa-exclamation-circle"></i> Error';

            // Mostrar mensaje de error
            if (errorMsg) {
                console.error('Error de actualización:', errorMsg);
                // Opcionalmente, mostrar un mensaje al usuario con una alerta
                alert('Error al actualizar datos: ' + errorMsg);
            }
        }
    }

    // Inicialización (modificar initApp)
    function initApp() {
        loadIncidencias(1, true);

        updateInterval = 10000;
        if (intervalId) clearInterval(intervalId);
        intervalId = setInterval(() => {
            if (!isLoading) checkForUpdates();
        }, updateInterval);

        document.getElementById('refresh-btn').addEventListener('click', () => {
            if (!isLoading) loadIncidencias(1, true);
        });

        document.getElementById('last-update-time').textContent = 'Iniciando...';
    }

    // Iniciar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', initApp);
</script>
@endsection
