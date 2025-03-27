@extends('layout.main')

@section('title', 'Panel de Control')

@section('css')
<!-- Añade esto al inicio de la sección CSS existente -->
<link rel="stylesheet" href="{{ asset('plugins/toastr/css/toastr.min.css') }}">
<!-- Mantén el resto del CSS existente -->
<style>
    .dashboard-container {
        padding: 20px 0;
    }

    /* Estilo para widgets de estadísticas */
    .stats-container {
        margin-bottom: 30px;
    }

    .stat-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border-left: 4px solid #3c8dbc;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        font-size: 40px;
        color: #3c8dbc;
        margin-bottom: 15px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #444;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #888;
    }

    /* Estilo para secciones del dashboard */
    .dashboard-section {
        margin-bottom: 35px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #444;
        position: relative;
        padding-left: 15px;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .section-title:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 70%;
        width: 4px;
        background-color: #3c8dbc;
        border-radius: 2px;
    }

    /* Estilo para tarjetas de módulos */
    .module-grid {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .module-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin: 10px;
        flex: 0 0 calc(33.333% - 20px);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .module-card a {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px 15px;
        color: #444;
        text-decoration: none;
        height: 100%;
    }

    .module-card a:hover {
        text-decoration: none;
    }

    .module-icon {
        font-size: 32px;
        margin-bottom: 15px;
        height: 40px;
        color: #3c8dbc;
    }

    .module-title {
        text-align: center;
        font-weight: 500;
        font-size: 14px;
    }

    /* Colores para diferentes módulos */
    .module-admin .module-icon {
        color: #3c8dbc;
    }

    .module-admin:hover {
        border-bottom: 3px solid #3c8dbc;
    }

    .module-hr .module-icon {
        color: #00a65a;
    }

    .module-hr:hover {
        border-bottom: 3px solid #00a65a;
    }

    .module-biometric .module-icon {
        color: #f39c12;
    }

    .module-biometric:hover {
        border-bottom: 3px solid #f39c12;
    }

    .module-reports .module-icon {
        color: #605ca8;
    }

    .module-reports:hover {
        border-bottom: 3px solid #605ca8;
    }

    .module-config .module-icon {
        color: #dd4b39;
    }

    .module-config:hover {
        border-bottom: 3px solid #dd4b39;
    }

    /* Widget de mantenimiento */
    .maintenance-widget {
        position: relative;
        border-left: 4px solid #dd4b39;
    }

    .maintenance-widget .stat-icon {
        color: #dd4b39;
    }

    .maintenance-toggle {
        margin-top: 15px;
        text-align: center;
    }

    /* Interruptor de mantenimiento */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #dd4b39;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #dd4b39;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .text-success {
        color: #00a65a;
    }

    .text-danger {
        color: #dd4b39;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .module-card {
            flex: 0 0 calc(50% - 20px);
        }
    }

    @media (max-width: 576px) {
        .module-card {
            flex: 0 0 calc(100% - 20px);
        }
    }

    /* Añade esto a la sección de estilos */
    .highlight-change {
        animation: highlight 1s ease-in-out;
    }

    @keyframes highlight {
        0% { color: #444; }
        50% { color: #2196F3; }
        100% { color: #444; }
    }

    #last-update {
        font-size: 10px;
        white-space: nowrap;
        display: inline-block;
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="dashboard-container">
    <div class="container">
        <!-- Widget de estadísticas -->
        <div class="row stats-container">
            <!-- Primera fila de widgets - 3 widgets por fila -->
            <div class="col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">{{ \App\Employe::where('deparment_id', '!=', 33)->count() }}</div>
                    <div class="stat-label">Empleados</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-value">
                        <span id="incidencias-count">{{ $count ?? 0 }}</span>
                        <small id="refresh-indicator" style="display:none; margin-left:5px;"><i class="fas fa-arrows-rotate fa-spin"></i></small>
                    </div>
                    <div class="stat-label">Incidencias Hoy <small id="last-update" class="text-muted"></small></div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ getFechaCierre() }}</div>
                    <div class="stat-label">Fecha de Cierre</div>
                </div>
            </div>
        </div>

        <!-- Segunda fila con el widget de mantenimiento -->
        <div class="row stats-container">
            <div class="col-md-4 col-sm-6">
                <div class="stat-card maintenance-widget">
                    <div class="stat-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <div class="stat-value">
                        @php
                        $mantenimiento = \App\Configuration::first();
                        $estadoActual = $mantenimiento && $mantenimiento->state ? 'Activo' : 'Apagado';
                        $colorEstado = $mantenimiento && $mantenimiento->state ? 'text-danger' : 'text-success';
                        @endphp
                        <span class="{{ $colorEstado }}">{{ $estadoActual }}</span>
                    </div>
                    <div class="stat-label">Modo Mantenimiento</div>

                    <div class="maintenance-toggle">
                        <label class="switch">
                            @if($mantenimiento)
                            <input type="checkbox" id="toggle-maintenance" {{ $mantenimiento->state ? 'checked' : '' }}>
                            <span class="slider round"></span>
                            @else
                            <input type="checkbox" id="toggle-maintenance">
                            <span class="slider round"></span>
                            @endif
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Administración -->
        <div class="dashboard-section">
            <h3 class="section-title">Administración</h3>
            <div class="module-grid">
                <div class="module-card module-admin">
                    <a href="{{ route('users.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="module-title">Usuarios</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a href="{{ route('deparments.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <div class="module-title">Departamentos</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a href="{{ route('qnas.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="module-title">Quincenas</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a data-url="{{ route('fechadecierre.create') }}" class="load-form-modal" data-toggle="modal"
                        data-target='#form-modal'>
                        <div class="module-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="module-title">Fecha de Cierre</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a href="{{ route('logs.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-clipboard"></i>
                        </div>
                        <div class="module-title">Logs</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a href="{{ route('admin.ausentismo') }}">
                        <div class="module-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="module-title">Ausentismo</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección de Recursos Humanos -->
        <div class="dashboard-section">
            <h3 class="section-title">Recursos Humanos</h3>
            <div class="module-grid">
                <div class="module-card module-hr">
                    <a href="{{ route('codigosdeincidencias.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-list-ul"></i>
                        </div>
                        <div class="module-title">Códigos de Incidencia</div>
                    </a>
                </div>
                <div class="module-card module-hr">
                    <a href="{{ route('periodos.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <div class="module-title">Periodos Vacacionales</div>
                    </a>
                </div>
                <div class="module-card module-hr">
                    <a href="{{ route('puestos.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="module-title">Puestos</div>
                    </a>
                </div>
                <div class="module-card module-hr">
                    <a href="{{ route('horarios.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="module-title">Horarios</div>
                    </a>
                </div>
                <div class="module-card module-hr">
                    <a href="{{ route('gys.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="module-title">GyS</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección de Biométrico -->
        <div class="dashboard-section">
            <h3 class="section-title">Sistema Biométrico</h3>
            <div class="module-grid">
                <div class="module-card module-biometric">
                    <a href="{{ route('biometrico.index') }}">
                        <div class="module-icon">
                            <i class="fas fa-cloud-arrow-down"></i>
                        </div>
                        <div class="module-title">Actualizar Registros</div>
                    </a>
                </div>
                <div class="module-card module-biometric">
                    <a href="{{ route('biometrico.registros') }}">
                        <div class="module-icon">
                            <i class="fas fa-hand-point-up"></i>
                        </div>
                        <div class="module-title">Ver Checadas</div>
                    </a>
                </div>

            </div>
        </div>

        <!-- Sección de Configuración -->
        <div class="dashboard-section">
            <h3 class="section-title">Configuración</h3>
            <div class="module-grid">
                <div class="module-card module-config">
                    <a href="{{ route('mantenimiento.show') }}">
                        <div class="module-icon">
                            <i class="fas fa-gear"></i>
                        </div>
                        <div class="module-title">Mantenimiento</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.form-modal', ['title' => 'Agregar Fecha de cierre'])
@endsection

@section('js')
<script src="{{ asset('plugins/toastr/js/toastr.min.js') }}"></script>
<script>
    // Configuración de toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-bottom-center",
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "1000", // Reducido para que la recarga sea más rápida
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        // Toggle para el modo mantenimiento - sin confirmación
        $('#toggle-maintenance').change(function() {
            // Mostrar indicador de carga
            var $statValue = $(this).closest('.stat-card').find('.stat-value span');
            $statValue.html('<i class="fas fa-spinner fa-spin"></i>');

            // Realizar petición AJAX
            $.ajax({
                url: '{{ route("mantenimiento.toggle") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        // Actualizar la interfaz
                        $statValue.removeClass('text-success text-danger')
                                .addClass(response.state ? 'text-danger' : 'text-success')
                                .text(response.state ? 'Activo' : 'Inactivo');

                        // Mostrar notificación y luego recargar
                        toastr.success('El modo mantenimiento ha sido ' +
                            (response.state ? 'activado' : 'desactivado') +
                            ' correctamente.');

                        // Recargar la página después de un breve retraso
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Error en la operación
                        toastr.error('Ocurrió un error al cambiar el estado: ' + (response.message || 'Error desconocido'));
                        // Revertir el interruptor
                        $('#toggle-maintenance').prop('checked', !$('#toggle-maintenance').prop('checked'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error AJAX:", xhr.responseText);
                    toastr.error('Error de conexión. Inténtelo de nuevo.');
                    // Revertir el interruptor
                    $('#toggle-maintenance').prop('checked', !$('#toggle-maintenance').prop('checked'));
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Configuración para el widget de incidencias
        const updateInterval = 5000; // 5 segundos
        let isUpdating = false;
        let lastCount = parseInt($('#incidencias-count').text());

        // Función para actualizar el contador de incidencias
        function updateIncidenciasCount() {
            if (isUpdating) return;
            isUpdating = true;

            $('#refresh-indicator').show();

            $.ajax({
                url: '{{ route("dashboard.incidencias-hoy") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    const newCount = parseInt(data.count);
                    const $counter = $('#incidencias-count');

                    // Animar la actualización solo si el valor cambió
                    if (newCount !== lastCount) {
                        $counter.fadeOut(200, function() {
                            $(this).text(newCount).fadeIn(200);
                            // Añadir clase para destacar cambio
                            $counter.addClass('highlight-change');
                            setTimeout(() => $counter.removeClass('highlight-change'), 1000);
                        });
                        lastCount = newCount;
                    }

                    // Actualizar la hora de última actualización
                    $('#last-update').text('Act: ' + data.time);
                },
                error: function(xhr, status, error) {
                    console.error("Error actualizando incidencias:", error);
                },
                complete: function() {
                    $('#refresh-indicator').hide();
                    isUpdating = false;
                }
            });
        }

        // Actualizar inmediatamente y luego cada 5 segundos
        updateIncidenciasCount();
        setInterval(updateIncidenciasCount, updateInterval);
    });
    (function() {
    var konamiCode = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65, 66, 65];
    var input = [];
    var secretURL = 'http://incidencias.ddns.net/biometrico/asignar'; // Reemplaza con la URL que quieres abrir

    window.addEventListener('keydown', function(e) {
    input.push(e.keyCode);
    input.splice(-konamiCode.length - 1, input.length - konamiCode.length);

    if (arraysMatch(input, konamiCode)) {
    window.location = secretURL;
    input = []; // Reinicia el array para evitar activaciones múltiples
    }
    });

    // Función para comparar dos arrays
    function arraysMatch(arr1, arr2) {
    if (arr1.length !== arr2.length) return false;
    for (var i = 0; i < arr1.length; i++) { if (arr1[i] !==arr2[i]) return false; } return true; } })
    ();
</script>
@endsection
