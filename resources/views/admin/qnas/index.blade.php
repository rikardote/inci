{{-- filepath: d:\swtools\laragon\www\incidencias\resources\views\admin\qnas\index.blade.php --}}
@extends('layout.main')

@section('title', 'Gestión de Qnas')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="pull-left">
                <h1 class="h3">Gestión de Qnas</h1>
            </div>
            <div class="pull-right">
                <a data-url="{{ route('qnas.create') }}" class="btn btn-success load-form-modal" data-toggle="modal" data-target="#form-modal">
                    <i class="fa fa-plus-circle"></i> Agregar Qna
                </a>
            </div>
        </div>
    </div>

    {{-- Mensajes de notificación --}}
    <div class="row">
        <div class="col-md-12">
            <div id="alert-container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr class="bg-primary">
                            <th>Qna</th>
                            <th>Año</th>
                            <th>Descripción</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($qnas as $qna)
                        <tr id="qna-row-{{ $qna->id }}">
                            <td>{{ $qna->qna }}</td>
                            <td>{{ $qna->year }}</td>
                            <td>{{ $qna->description }}</td>
                            <td class="text-center">
                                <div class="switch-container">
                                    <label class="switch">
                                        <input type="checkbox" id="switch-{{ $qna->id }}"
                                            {{ $qna->active ? 'checked' : '' }}
                                            onChange="toggleCondition({{ $qna->id }})">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </td>
                            <td class="text-center">
                                <a data-url="{{ route('qnas.edit', $qna->id) }}" class="btn btn-sm btn-primary load-form-modal"
                                   data-toggle="modal" data-target="#form-modal">
                                   <i class="fa fa-pencil"></i>
                                </a>
                                <a href="{{ route('qnas.destroy', $qna->id) }}" class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Está seguro que desea eliminar esta Qna?')">
                                   <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('modals.form-modal', ['title' => 'Agregar/Modificar Qnas'])
@endsection

@section('css')
<style>
    /* Estilo para el interruptor mejorado */
    .switch-container {
        display: flex;
        justify-content: center;
    }

    /* Diseño base del interruptor */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 26px;
        margin: 0;
    }

    /* Ocultar el checkbox original */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
        margin: 0;
    }

    /* El fondo del interruptor */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e0e0e0;
        border: 1px solid #ccc;
        transition: all 0.3s ease;
    }

    /* El círculo indicador */
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    /* Estilo cuando está activado */
    input:checked + .slider {
        background-color: #2196F3;
        border-color: #0b7dda;
    }

    /* Efecto de enfoque */
    input:focus + .slider {
        box-shadow: 0 0 2px #2196F3;
    }

    /* Movimiento del círculo - enfoque correcto */
    input:checked + .slider:before {
        transform: translateX(20px);
    }

    /* Diseño redondeado */
    .slider.round {
        border-radius: 26px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    /* Cuando está deshabilitado */
    .switch input:disabled + .slider {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endsection

@section('js')
<script>
    function toggleCondition(id) {
        // Inhabilitar el interruptor durante la petición
        $('#switch-' + id).prop('disabled', true);

        $.ajax({
            type: "GET",
            dataType: "json",
            url: 'qnas/' + id + '/condicion?t=' + new Date().getTime(),
            success: function(response) {
                if (response.success) {
                    // Obtener los datos de la qna actualizada
                    const qna = response.data;

                    // Actualizar solo el estado visual del interruptor específico
                    $('#switch-' + id).prop('checked', qna.active);
                } else {
                    // En caso de error, revertir el interruptor
                    $('#switch-' + id).prop('checked', !$('#switch-' + id).prop('checked'));
                    console.error("Error al actualizar el estado:", response.message);
                }
            },
            error: function(xhr) {
                // En caso de error de red, revertir el interruptor
                $('#switch-' + id).prop('checked', !$('#switch-' + id).prop('checked'));
                console.error("Error en la solicitud AJAX:", xhr.responseText);
            },
            complete: function() {
                // Habilitar el interruptor nuevamente
                $('#switch-' + id).prop('disabled', false);
            }
        });
    }
</script>
@endsection
