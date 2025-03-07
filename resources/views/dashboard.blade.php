@extends('layout.main')

@section('title', 'Panel de Control')

@section('css')
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
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="container">
        <!-- Widget de estadísticas -->
        <div class="row stats-container">
            <div class="col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="stat-value">{{ \App\Employe::count() }}</div>
                    <div class="stat-label">Empleados</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="stat-value">
                        @php
                        $inicioDelDia = \Carbon\Carbon::today()->subHours(8)->format('Y-m-d H:i:s');
                        $finDelDia = \Carbon\Carbon::tomorrow()->subHours(8)->format('Y-m-d H:i:s');
                        $count = \App\Incidencia::whereBetween('created_at', [$inicioDelDia, $finDelDia])->count();
                        @endphp
                        {{ $count }}
                    </div>
                    <div class="stat-label">Incidencias Hoy</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="stat-value">{{ getFechaCierre() }}</div>
                    <div class="stat-label">Fecha de Cierre</div>
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
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="module-title">Usuarios</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a href="{{ route('deparments.index') }}">
                        <div class="module-icon">
                            <i class="fa fa-list-alt"></i>
                        </div>
                        <div class="module-title">Departamentos</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a href="{{ route('qnas.index') }}">
                        <div class="module-icon">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                        <div class="module-title">Quincenas</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a data-url="{{ route('fechadecierre.create') }}" class="load-form-modal" data-toggle="modal"
                        data-target='#form-modal'>
                        <div class="module-icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div class="module-title">Fecha de Cierre</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a href="{{ route('logs.index') }}">
                        <div class="module-icon">
                            <i class="fa fa-clipboard"></i>
                        </div>
                        <div class="module-title">Logs</div>
                    </a>
                </div>
                <div class="module-card module-admin">
                    <a href="{{ route('admin.ausentismo') }}">
                        <div class="module-icon">
                            <i class="fa fa-bar-chart"></i>
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
                            <i class="fa fa-th-list"></i>
                        </div>
                        <div class="module-title">Códigos de Incidencia</div>
                    </a>
                </div>
                <div class="module-card module-hr">
                    <a href="{{ route('periodos.index') }}">
                        <div class="module-icon">
                            <i class="fa fa-plane"></i>
                        </div>
                        <div class="module-title">Periodos Vacacionales</div>
                    </a>
                </div>
                <div class="module-card module-hr">
                    <a href="{{ route('puestos.index') }}">
                        <div class="module-icon">
                            <i class="fa fa-briefcase"></i>
                        </div>
                        <div class="module-title">Puestos</div>
                    </a>
                </div>
                <div class="module-card module-hr">
                    <a href="{{ route('horarios.index') }}">
                        <div class="module-icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="module-title">Horarios</div>
                    </a>
                </div>
                <div class="module-card module-hr">
                    <a href="{{ route('gys.index') }}">
                        <div class="module-icon">
                            <i class="fa fa-tasks"></i>
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
                            <i class="fa fa-cloud-download"></i>
                        </div>
                        <div class="module-title">Actualizar Registros</div>
                    </a>
                </div>
                <div class="module-card module-biometric">
                    <a href="{{ route('biometrico.registros') }}">
                        <div class="module-icon">
                            <i class="fa fa-hand-o-up"></i>
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
                            <i class="fa fa-cog"></i>
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
