@extends('layout.main')
@section('title', 'Monitor de Incidencias en Tiempo Real')

@section('content')
<div class="dashboard-container">
    <!-- Cabecera con información y controles -->
    <div class="dashboard-header">
        <div class="header-title">
            <h2><i class="fa fa-history"></i> Monitor de Actividad</h2>
            <p class="text-muted">Seguimiento de incidencias en tiempo real. Los datos se actualizan automáticamente.
            </p>
        </div>

        <div class="header-actions">
            <div class="status-indicator">
                <span id="update-status" class="label label-success rounded">
                    <i class="fa fa-check-circle"></i> Actualizado
                </span>
                <span id="last-update-time" class="text-muted"></span>
            </div>

            <div class="action-buttons">
                <div class="btn-group">
                    <button id="refresh-btn" class="btn btn-primary">
                        <i class="fa fa-refresh"></i> Actualizar ahora
                    </button>
                    <button class="btn btn-default" id="toggle-filters">
                        <i class="fa fa-filter"></i> Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de filtros (oculto por defecto) -->
    <div id="filters-panel" class="filters-panel" style="display: none;">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tipo de Incidencia</label>
                    <select class="form-control" id="filter-type">
                        <option value="">Todas</option>
                        <option value="danger">Eliminadas</option>
                        <option value="active">Activas</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" class="form-control" id="filter-date">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Empleado</label>
                    <input type="text" class="form-control" id="filter-employee" placeholder="Nombre o número">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Unidad</label>
                    <input type="text" class="form-control" id="filter-department" placeholder="Código o nombre">
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de estadísticas resumidas -->
    <div class="stats-container">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="stat-data">
                        <h3 id="total-records">-</h3>
                        <p>Total registros</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <div class="stat-data">
                        <h3 id="today-records">-</h3>
                        <p>Agregados hoy</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon danger-bg">
                        <i class="fa fa-trash"></i>
                    </div>
                    <div class="stat-data">
                        <h3 id="deleted-records">-</h3>
                        <p>Eliminados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon warning-bg">
                        <i class="fa fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-data">
                        <h3 id="alerts-count">-</h3>
                        <p>Alertas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de registros -->
    <div class="panel panel-default data-panel">
        <div class="panel-heading">
            <h3 class="panel-title">Registros de incidencias</h3>
            <div class="panel-tools">
                <input type="text" class="form-control search-input" placeholder="Buscar..." id="table-search">
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="logs-table" class="table table-hover table-striped" style="width:100%;">
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
                        <!-- Los datos se cargarán mediante JavaScript -->
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
</div>
@endsection

@section('css')
<style>
    /* Estilos para la vista de logs */
    .dashboard-container {
        padding: 0 0 30px 0;
    }

    /* Cabecera */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .header-title h2 {
        margin-top: 0;
        margin-bottom: 5px;
        color: #444;
        font-weight: 500;
    }

    .header-actions {
        display: flex;
        align-items: center;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        margin-right: 15px;
    }

    #last-update-time {
        margin-left: 8px;
        font-size: 13px;
    }

    .label.rounded {
        border-radius: 15px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 400;
    }

    /* Filtros */
    .filters-panel {
        background: #f9f9f9;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #eaeaea;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
    }

    /* Estadísticas */
    .stats-container {
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        border-radius: 5px;
        padding: 20px;
        display: flex;
        align-items: center;
        border: 1px solid #eaeaea;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
        margin-bottom: 15px;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #3c8dbc;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
    }

    .danger-bg {
        background-color: #dd4b39;
    }

    .warning-bg {
        background-color: #f39c12;
    }

    .stat-data h3 {
        margin: 0 0 5px 0;
        font-size: 24px;
        font-weight: 700;
    }

    .stat-data p {
        margin: 0;
        color: #888;
        font-size: 14px;
    }

    /* Panel de datos */
    .data-panel {
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .data-panel .panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
    }

    .panel-tools {
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 250px;
        border-radius: 20px;
        padding-left: 15px;
    }

    /* Tabla */
    #logs-table {
        margin-bottom: 0;
    }

    #logs-table th {
        background-color: #f8f8f8;
        font-weight: 600;
        color: #555;
    }

    #logs-table thead th {
        border-bottom: 2px solid #e9e9e9;
        white-space: nowrap;
    }

    #logs-table td {
        vertical-align: middle;
    }

    .table-hover tbody tr {
        transition: all 0.1s ease;
    }

    .table-hover tbody tr:hover {
        background-color: #f0f7fd !important;
    }

    /* Indicadores */
    .loader-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
    }

    .loader {
        border: 4px solid #f3f3f3;
        border-radius: 50%;
        border-top: 4px solid #3c8dbc;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Animaciones */
    .highlight-row {
        animation: highlight-fade 2s ease-in-out;
    }

    @keyframes highlight-fade {
        0% {
            background-color: #d9edf7;
        }

        100% {
            background-color: transparent;
        }
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            margin-top: 15px;
            width: 100%;
            justify-content: space-between;
        }

        .search-input {
            width: 100%;
        }
    }
</style>
@endsection

@section('js')
<script>
    // Variables globales
    let lastUpdateTime = new Date();
    let updateInterval = 10000; // 30 segundos
    let intervalId = null;
    let isLoading = false;
    let allIncidencias = [];
    let currentPage = 1;
    let isLastPage = false;
    let pageSize = 20;
    let isFetching = false;

    // Agrega estas variables para el manejo del scroll
    let lastScrollPosition = 0;
    let scrollRestoreElement = null;
    let preserveScrollOnNextUpdate = false;

    // Formatear fecha en formato DD/MM/YYYY
    function formatDate(dateString) {
        if (!dateString) return '-';

        const date = new Date(dateString);
        if (isNaN(date.getTime())) return '-';

        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // Formatear fecha y hora en formato DD/MM/YYYY HH:MM:SS
    function formatDateTime(dateString) {
        if (!dateString) return '-';

        try {
            const date = new Date(dateString);

            // Verificar si la fecha es válida
            if (isNaN(date.getTime())) {
                return '-';
            }

            return date.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }) + ' ' + date.toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (e) {
            console.error('Error formateando fecha:', e);
            return '-';
        }
    }

    // Modifica la función de actualización automática para preservar el scroll
    function loadIncidencias(page = 1, resetData = true, preserveScroll = false) {
        if (isLoading || isFetching) return;

        // Guardar la posición del scroll si es necesario
        if (preserveScroll) {
            preserveScrollOnNextUpdate = true;
            lastScrollPosition = window.scrollY;

            // Identificar un elemento cercano a la posición actual para referencia
            const tableRows = document.querySelectorAll('#logs-body tr');
            if (tableRows.length > 0) {
                // Encontrar el elemento visible más cercano al centro de la pantalla
                const viewportHeight = window.innerHeight;
                const viewportCenter = lastScrollPosition + (viewportHeight / 2);

                let closestRow = null;
                let closestDistance = Infinity;

                tableRows.forEach(row => {
                    const rowRect = row.getBoundingClientRect();
                    const rowCenter = rowRect.top + window.scrollY + (rowRect.height / 2);
                    const distance = Math.abs(viewportCenter - rowCenter);

                    if (distance < closestDistance) {
                        closestDistance = distance;
                        closestRow = row;
                    }
                });

                if (closestRow) {
                    // Guardar el ID del elemento o crear uno temporal
                    if (!closestRow.id) {
                        closestRow.id = 'scroll-marker-' + Date.now();
                    }
                    scrollRestoreElement = closestRow.id;
                }
            }
        }

        isFetching = true;

        if (page === 1) {
            isLoading = true;
            document.getElementById('loading-indicator').style.display = 'block';
            document.getElementById('empty-indicator').style.display = 'none';
            document.getElementById('update-status').className = 'label label-warning rounded';
            document.getElementById('update-status').innerHTML = '<i class="fa fa-refresh fa-spin"></i> Actualizando...';
        } else {
            // Mostrar indicador de carga adicional al final de la tabla
            const loadMoreIndicator = document.createElement('tr');
            loadMoreIndicator.id = 'load-more-indicator';
            loadMoreIndicator.innerHTML = `
                <td colspan="10" class="text-center py-3">
                    <i class="fa fa-circle-o-notch fa-spin"></i> Cargando más registros...
                </td>
            `;
            document.getElementById('logs-body').appendChild(loadMoreIndicator);
        }

        fetch(`{{ route('api.logs.incidencias') }}?page=${page}&limit=${pageSize}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                // Si es la primera página, reemplazar datos, de lo contrario añadirlos
                if (resetData) {
                    allIncidencias = data.data || data;
                } else {
                    // Agregar nuevos datos a los existentes
                    if (data.data) {
                        allIncidencias = [...allIncidencias, ...(data.data || [])];
                    } else {
                        allIncidencias = [...allIncidencias, ...(data || [])];
                    }
                }

                // Verificar si es la última página
                if (data.last_page) {
                    isLastPage = page >= data.last_page;
                } else {
                    isLastPage = Array.isArray(data) ? data.length < pageSize : true;
                }

                // Actualizar la página actual
                currentPage = page;

                // Renderizar datos (filtrados si es necesario)
                if (page === 1) {
                    renderTable(allIncidencias, true);
                    updateStatistics(allIncidencias);
                    updateStatus(true);

                    // Restaurar posición del scroll si es necesario
                    if (preserveScrollOnNextUpdate) {
                        setTimeout(restoreScrollPosition, 100);
                    }
                } else {
                    const loadMoreIndicator = document.getElementById('load-more-indicator');
                    if (loadMoreIndicator) {
                        loadMoreIndicator.remove();
                    }

                    renderTable(
                        Array.isArray(data) ? data : (data.data || []),
                        false
                    );
                }
            })
            .catch(error => {
                console.error('Error al cargar los datos:', error);
                updateStatus(false, error.message);
            })
            .finally(() => {
                isFetching = false;

                if (page === 1) {
                    isLoading = false;
                    document.getElementById('loading-indicator').style.display = 'none';
                } else {
                    const loadMoreIndicator = document.getElementById('load-more-indicator');
                    if (loadMoreIndicator) {
                        loadMoreIndicator.remove();
                    }
                }
            });
    }

    // Actualizar las estadísticas con los datos recibidos
    function updateStatistics(data) {
        if (!data) return;

        // Total de registros
        document.getElementById('total-records').textContent = data.length;

        // Registros de hoy
        const today = new Date().toISOString().split('T')[0];
        const todayRecords = data.filter(inc =>
            inc.created_at && inc.created_at.startsWith(today)
        ).length;
        document.getElementById('today-records').textContent = todayRecords;

        // Registros eliminados
        const deletedRecords = data.filter(inc => inc.deleted_at).length;
        document.getElementById('deleted-records').textContent = deletedRecords;

        // Alertas (podría ser basado en algún criterio específico)
        const alerts = data.filter(inc => {
            // Ejemplo: incidencias con más de 10 días
            return inc.total_dias > 10;
        }).length;
        document.getElementById('alerts-count').textContent = alerts;
    }

    // Renderizar la tabla con los datos obtenidos
    function renderTable(incidencias, resetTable = true) {
        const tableBody = document.getElementById('logs-body');

        if (resetTable) {
            tableBody.innerHTML = '';
        }

        if (incidencias.length === 0 && resetTable) {
            document.getElementById('empty-indicator').style.display = 'block';
            return;
        }

        incidencias.forEach(incidencia => {
            const row = document.createElement('tr');

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
                <td align="center">${formatDate(incidencia.fecha_inicio)}</td>
                <td align="center">${formatDate(incidencia.fecha_final)}</td>
                <td align="center">
                    <span class="badge bg-info">
                        ${incidencia.total_dias || '-'}
                    </span>
                </td>
                <td align="center">${periodo.periodo ? periodo.periodo + '/' + periodo.year : '-'}</td>
                <td align="center">${formatDateTime(incidencia.created_at)}</td>
                <td align="center">
                    ${incidencia.deleted_at ?
                        `<span class="text-danger">${formatDateTime(incidencia.deleted_at)}</span>` :
                        '<span class="text-muted">-</span>'}
                </td>
            `;

            // Añadir la fila a la tabla
            tableBody.appendChild(row);
        });

        // Si no es la última página, añadir un elemento para detectar scroll
        // Siempre añadir el detector de scroll al final de renderizar
        if (!isLastPage) {
            // Programar la adición después de un pequeño delay para estar seguro que el DOM está actualizado
            setTimeout(addScrollDetection, 100);
        } else {
            // Limpiar listener de scroll si es la última página
            window.removeEventListener('scroll', handleScroll);

            // Añadir mensaje de "no hay más registros"
            const noMoreRow = document.createElement('tr');
            noMoreRow.innerHTML = `
                <td colspan="10" class="text-center py-3 text-muted">
                    <i class="fa fa-check-circle"></i> No hay más registros
                </td>
            `;
            tableBody.appendChild(noMoreRow);
        }
    }

    // Función mejorada para añadir detección de scroll
    function addScrollDetection() {
        // Eliminar detector de scroll anterior si existe
        const oldTrigger = document.getElementById('scroll-trigger');
        if (oldTrigger) {
            oldTrigger.remove();
        }

        // Crear y añadir el elemento de detección con mejor visibilidad
        const scrollTrigger = document.createElement('div');
        scrollTrigger.id = 'scroll-trigger';
        scrollTrigger.style.width = '100%';
        scrollTrigger.style.height = '20px'; // Altura mayor para mejor detección
        scrollTrigger.style.margin = '10px 0'; // Margen para separarlo de la última fila

        // Añadirlo al final del contenedor de la tabla, no dentro de la tabla
        document.querySelector('.panel-body').appendChild(scrollTrigger);

        // Observer para infinite scroll con configuración más sensible
        const options = {
            root: null, // viewport
            rootMargin: '0px 0px 200px 0px', // detectar cuando esté a 200px del final
            threshold: 0 // disparar tan pronto como cualquier parte sea visible
        };

        // Crear y aplicar observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Añadir mensaje de depuración
                console.log('Elemento trigger detectado:', entry.isIntersecting, 'isLastPage:', isLastPage, 'isFetching:', isFetching);

                if (entry.isIntersecting && !isLastPage && !isFetching) {
                    console.log('Cargando página:', currentPage + 1);
                    // Cargar más datos al llegar al final
                    loadIncidencias(currentPage + 1, false);
                }
            });
        }, options);

        // Observar el elemento
        observer.observe(scrollTrigger);

        // Añadir también un detector de scroll alternativo como respaldo
        window.addEventListener('scroll', handleScroll);
    }

    // Detector de scroll alternativo
    function handleScroll() {
        // Si ya estamos cargando o es la última página, no hacemos nada
        if (isLastPage || isFetching) return;

        // Calcular si estamos cerca del final de la página
        const scrollHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const clientHeight = window.innerHeight;

        const scrollRemaining = scrollHeight - scrollTop - clientHeight;
        const scrollThreshold = 200; // cargar cuando falten 200px para el final

        if (scrollRemaining < scrollThreshold) {
            console.log('Detector de scroll alternativo activado. Cargando página:', currentPage + 1);
            loadIncidencias(currentPage + 1, false);
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

    // Filtrar datos de la tabla
    function filterTable() {
        currentPage = 1;
        isLastPage = false;

        const filterType = document.getElementById('filter-type').value;
        const filterDate = document.getElementById('filter-date').value;
        const filterEmployee = document.getElementById('filter-employee').value.toLowerCase();
        const filterDepartment = document.getElementById('filter-department').value.toLowerCase();
        const searchText = document.getElementById('table-search').value.toLowerCase();

        // Si hay filtros activos, filtrar datos localmente
        if (filterType || filterDate || filterEmployee || filterDepartment || searchText) {
            let filteredData = [...allIncidencias];

            // Aplicar filtros como antes...
            if (filterType === 'danger') {
                filteredData = filteredData.filter(inc => inc.deleted_at);
            } else if (filterType === 'active') {
                filteredData = filteredData.filter(inc => !inc.deleted_at);
            }

            // Filtrar por fecha
            if (filterDate) {
                filteredData = filteredData.filter(inc =>
                    inc.fecha_inicio && inc.fecha_inicio.startsWith(filterDate)
                );
            }

            // Filtrar por empleado
            if (filterEmployee) {
                filteredData = filteredData.filter(inc => {
                    const employee = inc.employee || {};
                    const fullName = `${employee.father_lastname || ''} ${employee.mother_lastname || ''} ${employee.name || ''}`.toLowerCase();
                    return fullName.includes(filterEmployee) ||
                          (employee.num_empleado && employee.num_empleado.toString().includes(filterEmployee));
                });
            }

            // Filtrar por departamento
            if (filterDepartment) {
                filteredData = filteredData.filter(inc => {
                    const employee = inc.employee || {};
                    const deparment = employee.deparment || {};
                    return (deparment.code && deparment.code.toString().toLowerCase().includes(filterDepartment)) ||
                           (deparment.name && deparment.name.toLowerCase().includes(filterDepartment));
                });
            }

            // Filtrar por texto de búsqueda
            if (searchText) {
                filteredData = filteredData.filter(inc => {
                    const employee = inc.employee || {};
                    const deparment = employee.deparment || {};
                    const codigoInc = inc.codigodeincidencia || {};
                    const periodo = inc.periodo || {};

                    const searchFields = [
                        deparment.code,
                        deparment.name,
                        employee.num_empleado,
                        employee.name,
                        employee.father_lastname,
                        employee.mother_lastname,
                        codigoInc.code,
                        inc.fecha_inicio,
                        inc.fecha_final
                    ].filter(Boolean).map(field => field.toString().toLowerCase());

                    return searchFields.some(field => field.includes(searchText));
                });
            }

            renderTable(filteredData, true);
        } else {
            // Si no hay filtros, reiniciar la carga con paginación
            loadIncidencias(1, true);
        }
    }

    // Función para restaurar la posición del scroll
    function restoreScrollPosition() {
        if (scrollRestoreElement) {
            // Intenta encontrar el elemento de referencia
            const marker = document.getElementById(scrollRestoreElement);
            if (marker) {
                // Scroll hasta el elemento con un pequeño offset para mejor visibilidad
                marker.scrollIntoView({ behavior: 'auto', block: 'center' });
            } else {
                // Si no encuentra el elemento, restaura por posición
                window.scrollTo(0, lastScrollPosition);
            }
        } else {
            // Sin elemento de referencia, restaura por posición
            window.scrollTo(0, lastScrollPosition);
        }

        // Resetear las variables
        preserveScrollOnNextUpdate = false;
    }

    // Modifica la función de actualización periódica para preservar el scroll
    function initApp() {
        // Cargar datos iniciales
        loadIncidencias();

        // Asegurarnos de limpiar listeners antiguos
        window.removeEventListener('scroll', handleScroll);

        // Configurar actualización periódica con preservación de scroll
        if (intervalId) {
            clearInterval(intervalId);
        }
        intervalId = setInterval(() => {
            loadIncidencias(1, true, true);  // Importante: preserveScroll = true
        }, updateInterval);

        // Resto del código de inicialización...
        document.getElementById('refresh-btn').addEventListener('click', () => {
            loadIncidencias(1, true, true);  // Preservar scroll en actualización manual
        });

        // Resto del código de inicialización...
        document.getElementById('toggle-filters').addEventListener('click', () => {
            const filtersPanel = document.getElementById('filters-panel');
            filtersPanel.style.display = filtersPanel.style.display === 'none' ? 'block' : 'none';
        });

        // Configurar eventos para filtros (estos no preservan el scroll, ya que cambian los datos)
        document.getElementById('filter-type').addEventListener('change', filterTable);
        document.getElementById('filter-date').addEventListener('change', filterTable);
        document.getElementById('filter-employee').addEventListener('input', filterTable);
        document.getElementById('filter-department').addEventListener('input', filterTable);
        document.getElementById('table-search').addEventListener('input', filterTable);

        // Mostrar tiempo inicial
        document.getElementById('last-update-time').textContent = 'Iniciando...';
    }

    // Iniciar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', initApp);
</script>
@endsection
