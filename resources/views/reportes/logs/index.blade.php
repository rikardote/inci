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
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de registros -->


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

    /* Panel de datos */
    .data-panel {
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
    }
</style>
@endsection

@section('js')
<script>
    // Variables globales
    let lastUpdateTime = new Date();
    let updateInterval = 10000; // 10 segundos
    let intervalId = null;
    let isLoading = false;
    let allIncidencias = [];
    let currentPage = 1;
    let isLastPage = false;
    let pageSize = 20;
    let scrollObserver = null; // IntersectionObserver

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

    // Formatear fecha y hora en formato DD/MM/YYYY HH:MM:SS (sin ajuste de horario)
    function formatDateTime(dateString) {
        if (!dateString) return '-';

        try {
            const date = new Date(dateString);

            // Verificar si la fecha es válida
            if (isNaN(date.getTime())) {
                return '-';
            }

            // Aplicar la diferencia horaria de 7 horas (en milisegundos)
            const timeZoneOffset = 7 * 60 * 60 * 1000;
            const adjustedDate = new Date(date.getTime() - timeZoneOffset);

            const day = String(adjustedDate.getDate()).padStart(2, '0');
            const month = String(adjustedDate.getMonth() + 1).padStart(2, '0');
            const year = adjustedDate.getFullYear();
            const hour = String(adjustedDate.getHours()).padStart(2, '0');
            const minute = String(adjustedDate.getMinutes()).padStart(2, '0');
            const second = String(adjustedDate.getSeconds()).padStart(2, '0');

            return `${day}/${month}/${year} ${hour}:${minute}:${second}`;
        } catch (e) {
            console.error('Error formateando fecha:', e);
            return '-';
        }
    }

    // Cargar incidencias con manejo mejorado de estado
    function loadIncidencias(page = 1, resetData = true, preserveScroll = false) {
        if (isLoading) return;

        // Guardar posición de scroll si es necesario
        let savedScrollPosition = 0;
        let scrollMarkerElement = null;

        if (preserveScroll) {
            savedScrollPosition = window.scrollY;
            // Identificar elemento visible cerca del centro de la pantalla
            scrollMarkerElement = findVisibleCenterElement('#logs-body tr');
        }

        isLoading = true;

        // Mostrar indicador de carga apropiado
        if (page === 1) {
            document.getElementById('loading-indicator').style.display = 'block';
            document.getElementById('empty-indicator').style.display = 'none';
            document.getElementById('update-status').className = 'label label-warning rounded';
            document.getElementById('update-status').innerHTML = '<i class="fa fa-refresh fa-spin"></i> Actualizando...';
        } else {
            // Añadir indicador al final de la tabla
            showLoadMoreIndicator();
        }

        fetch(`{{ route('api.logs.incidencias') }}?page=${page}&limit=${pageSize}`)
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta del servidor');
                return response.json();
            })
            .then(data => {
                // Procesar datos
                if (resetData) {
                    allIncidencias = data.data || data;
                } else {
                    // Añadir nuevos datos a los existentes
                    allIncidencias = [...allIncidencias, ...(data.data || data)];
                }

                // Determinar si es la última página
                isLastPage = data.last_page ? (page >= data.last_page) :
                    (Array.isArray(data) ? data.length < pageSize : true);

                // Actualizar página actual
                currentPage = page;

                // Renderizar datos
                renderTable(page === 1 ? allIncidencias : (Array.isArray(data) ? data : (data.data || [])), resetData);

                // Restaurar scroll si es necesario
                if (preserveScroll && scrollMarkerElement) {
                    setTimeout(() => restoreScroll(scrollMarkerElement, savedScrollPosition), 100);
                }
            })
            .catch(error => {
                console.error('Error al cargar datos:', error);
                window.updateStatus(false, error.message);
            })
            .finally(() => {
                isLoading = false;

                // Ocultar indicadores de carga
                if (page === 1) {
                    document.getElementById('loading-indicator').style.display = 'none';
                } else {
                    hideLoadMoreIndicator();
                }

                // Inicializar observador de scroll solo después de renderizar
                if (!isLastPage) {
                    window.addScrollDetection();
                }
            });
    }

    // Encontrar elemento visible cercano al centro
    function findVisibleCenterElement(selector) {
        const elements = document.querySelectorAll(selector);
        if (!elements.length) return null;

        const viewportHeight = window.innerHeight;
        const viewportCenter = window.scrollY + (viewportHeight / 2);

        let closestElement = null;
        let closestDistance = Infinity;

        elements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const elementCenter = rect.top + window.scrollY + (rect.height / 2);
            const distance = Math.abs(viewportCenter - elementCenter);

            if (distance < closestDistance) {
                closestDistance = distance;
                closestElement = element;
            }
        });

        // Asegurarse de que el elemento tenga un ID para referencia
        if (closestElement && !closestElement.id) {
            closestElement.id = 'scroll-marker-' + Date.now();
        }

        return closestElement;
    }

    // Configuración de scroll infinito con IntersectionObserver
    window.setupInfiniteScroll = function() {
        // Limpiar observador anterior si existe
        if (scrollObserver) {
            scrollObserver.disconnect();
            scrollObserver = null;
        }

        // Crear o asegurar que existe el elemento trigger
        let scrollTrigger = document.getElementById('scroll-trigger');
        if (!scrollTrigger) {
            scrollTrigger = document.createElement('div');
            scrollTrigger.id = 'scroll-trigger';
            scrollTrigger.style.width = '100%';
            scrollTrigger.style.height = '20px';
            scrollTrigger.style.margin = '20px 0';
            document.querySelector('.panel-body').appendChild(scrollTrigger);
        }

        // Crear nuevo observador con configuración optimizada
        scrollObserver = new IntersectionObserver((entries) => {
            const entry = entries[0];
            if (entry && entry.isIntersecting && !isLoading && !isLastPage) {
                console.log('Trigger activado - Cargando página:', currentPage + 1);
                loadIncidencias(currentPage + 1, false);
            }
        }, {
            root: null,
            rootMargin: '0px 0px 300px 0px', // Más sensible: detecta a 300px del viewport
            threshold: 0.1 // Más sensible: dispara cuando 10% es visible
        });

        // Observar el elemento trigger
        scrollObserver.observe(scrollTrigger);
    }

    // Mostrar indicador de carga al final
    function showLoadMoreIndicator() {
        // Eliminar si existe uno anterior
        hideLoadMoreIndicator();

        const indicator = document.createElement('tr');
        indicator.id = 'load-more-indicator';
        indicator.innerHTML = `
            <td colspan="10" class="text-center py-3">
                <i class="fa fa-circle-o-notch fa-spin"></i> Cargando más registros...
            </td>
        `;
        document.getElementById('logs-body').appendChild(indicator);
    }

    // Ocultar indicador de carga
    function hideLoadMoreIndicator() {
        const indicator = document.getElementById('load-more-indicator');
        if (indicator) indicator.remove();
    }

    // Restaurar posición de scroll
    function restoreScroll(element, fallbackPosition) {
        if (element) {
            element.scrollIntoView({ behavior: 'auto', block: 'center' });
        } else if (fallbackPosition) {
            window.scrollTo(0, fallbackPosition);
        }
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
                <td>${formatDate(incidencia.fecha_inicio)}</td>
                <td>${formatDate(incidencia.fecha_final)}</td>
                <td>
                    ${incidencia.total_dias || '-'}
                </td>
                <td>${periodo.periodo ? periodo.periodo + '/' + periodo.year : '-'}</td>
                <td>${formatDateTime(incidencia.created_at)}</td>
                <td>
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
            setTimeout(window.addScrollDetection, 100);
        } else {
            // Limpiar observer de scroll si es la última página
            if (scrollObserver) {
                scrollObserver.disconnect();
                scrollObserver = null;
            }

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

    window.checkForUpdates = function() {
        fetch('{{ route('api.logs.checkForUpdates') }}?last_update=' + lastUpdateTime.toISOString())
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error checking for updates');
                }
                return response.json();
            })
            .then(data => {
                if (data.has_updates) {
                    loadIncidencias(1, true, true); // Recargar solo si hay actualizaciones
                } else {
                    window.updateStatus(true); // Actualizar el estado para mostrar la hora de la última comprobación
                }
            })
            .catch(error => {
                console.error('Error checking for updates:', error);
                window.updateStatus(false, error.message);
            });
    }

    // Función mejorada para añadir detección de scroll
    window.addScrollDetection = function() {
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
        scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Añadir mensaje de depuración
                console.log('Elemento trigger detectado:', entry.isIntersecting, 'isLastPage:', isLastPage, 'isLoading:', isLoading);

                if (entry.isIntersecting && !isLastPage && !isLoading) {
                    console.log('Cargando página:', currentPage + 1);
                    // Cargar más datos al llegar al final
                    loadIncidencias(currentPage + 1, false);
                }
            });
        }, options);

        // Observar el elemento
        scrollObserver.observe(scrollTrigger);
    }

    // Actualizar el estado de la actualización
    window.updateStatus = function(success, errorMsg = '') {
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

    // Modifica la función de actualización periódica para preservar el scroll
    function initApp() {
        // Cargar datos iniciales
        loadIncidencias();

        // Configurar actualización periódica con preservación de scroll
        if (intervalId) {
            clearInterval(intervalId);
        }
        intervalId = setInterval(() => {
            window.checkForUpdates(); // Comprobar si hay actualizaciones antes de recargar
        }, updateInterval);

        // Resto del código de inicialización...
        document.getElementById('refresh-btn').addEventListener('click', () => {
            loadIncidencias(1, true, true);  // Preservar scroll en actualización manual
        });

        // Mostrar tiempo inicial
        document.getElementById('last-update-time').textContent = 'Iniciando...';
    }

    // Iniciar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', initApp);
</script>
@endsection
