@extends('layout.main')

@section('title', 'Modo de Mantenimiento')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-wrench"></i> Control de Modo de Mantenimiento
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="maintenance-info">
                            <h4><i class="fa fa-info-circle"></i> Estado del Sistema</h4>
                            <p class="lead">
                                @if($mantenimiento->state)
                                <span class="label label-danger"><i class="fa fa-exclamation-triangle"></i> En mantenimiento</span>
                                @else
                                <span class="label label-success"><i class="fa fa-check-circle"></i> Operativo</span>
                                @endif
                            </p>
                            <p class="text-muted">
                                @if($mantenimiento->state)
                                El sistema actualmente está en modo de mantenimiento. Los usuarios verán la página de mantenimiento al intentar acceder.
                                @else
                                El sistema está en funcionamiento normal, accesible para todos los usuarios.
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="maintenance-toggle text-center">
                            <h4>Control de Mantenimiento</h4>
                            <div class="toggle-container">
                                <p>Estado actual:
                                    <strong>
                                        @if($mantenimiento->state)
                                            ACTIVADO
                                        @else
                                            DESACTIVADO
                                        @endif
                                    </strong>
                                </p>
                                <div class="switch-container">
                                    @if($mantenimiento->state)
                                    <a id='button' class='switch' href='/mantenimiento/state' data-toggle="tooltip" title="Desactivar modo mantenimiento">
                                        <input type='checkbox' checked>
                                        <span class='slider round'></span>
                                    </a>
                                    @else
                                    <a id='button' class='switch' href='/mantenimiento/state' data-toggle="tooltip" title="Activar modo mantenimiento">
                                        <input type='checkbox'>
                                        <span class='slider round'></span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="maintenance-details">
                    <h4><i class="fa fa-cogs"></i> Detalles de Mantenimiento</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Última actualización:</label>
                                <p>{{ $fecha_actualizacion }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Actualizado por:</label>
                                <p>{{ $mantenimiento->user->name ?? 'Sistema' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ url('/dashboard') }}" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Volver al panel
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        @if($mantenimiento->state)
                        <a href="/mantenimiento/state" class="btn btn-success" onclick="return confirm('¿Está seguro de desactivar el modo mantenimiento?')">
                            <i class="fa fa-power-off"></i> Desactivar Mantenimiento
                        </a>
                        @else
                        <a href="/mantenimiento/state" class="btn btn-danger" onclick="return confirm('¿Está seguro de activar el modo mantenimiento?\nEsto impedirá el acceso a los usuarios.')">
                            <i class="fa fa-power-off"></i> Activar Mantenimiento
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .maintenance-info, .maintenance-toggle {
        min-height: 160px;
        padding: 15px;
    }

    .toggle-container {
        margin: 20px auto;
        text-align: center;
    }

    .switch-container {
        margin: 15px auto;
        text-align: center;
    }

    /* Estilo mejorado para el switch */
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
        background-color: #d9534f;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #d9534f;
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

    .panel-heading h3 {
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .maintenance-details {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 4px;
    }

    .label-success, .label-danger {
        font-size: 95%;
        padding: 5px 10px;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        // Confirmación antes de cambiar estado
        $('#button').click(function(e) {
            if (!confirm('¿Está seguro de cambiar el estado de mantenimiento del sistema?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
