@extends('layout.main')

@section('content')
<div class="container">
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-calendar"></i> Registros Biométricos</h3>
        </div>
        <div class="panel-body">
            {!! Form::open(['route' => 'biometrico.registros', 'method' => 'GET', 'class' => 'form-horizontal']) !!}

            <div class="row">
                <!-- Período y Centro de Trabajo -->
                <div class="col-md-12">
                    <div class="periodo-container">
                        <div class="well">
                            <div class="row">
                                <!-- Centro de Trabajo -->
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <i class="fa fa-building"></i> Centro de Trabajo
                                        </label>
                                        {!! Form::select('centro', $centros, $centro_seleccionado, [
                                        'class' => 'form-control select2',
                                        'placeholder' => 'Seleccione un centro de trabajo...',
                                        'required' => true
                                        ]) !!}
                                    </div>
                                </div>

                                <!-- Año -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <i class="fa fa-calendar-o"></i> Año
                                        </label>
                                        {!! Form::select('año', array_combine($años, $años), $año_seleccionado, [
                                        'class' => 'form-control select2',
                                        'id' => 'año'
                                        ]) !!}
                                    </div>
                                </div>

                                <!-- Quincena -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="control-label">
                                            <i class="fa fa-calendar-check-o"></i> Quincena
                                        </label>
                                        {!! Form::select('quincena',
                                        collect($quincenas)->pluck('label', 'value')->toArray(),
                                        $quincena_seleccionada,
                                        [
                                        'class' => 'form-control select2',
                                        'id' => 'quincena'
                                        ]
                                        ) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón de búsqueda -->
                <div class="col-md-12">
                    <div class="button-container text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i> Consultar Registros
                        </button>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

            {{-- Verificar registros usando método count() --}}
            @if(isset($registros) && count($registros) > 0)
            @include('biometrico.partials._tabla_registros')
            @elseif($centro_seleccionado)
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> No se encontraron registros para el período seleccionado.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<style>
    .panel-body {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        color: #555;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .form-group .select2-container {
        width: 100% !important;
    }

    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .well {
        background-color: #f9f9f9;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
    }

    .panel-primary>.panel-heading {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        padding: 15px 25px;
    }

    .panel-title {
        font-size: 18px;
        font-weight: 500;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2980b9 0%, #2c3e50 100%);
    }

    /* Ajuste específico para el centro de trabajo */
    .mb-4 {
        margin-bottom: 2rem;
    }

    /* Actualizar el margen del último form-group dentro del well */
    .well .form-group:last-child {
        margin-bottom: 0;
    }

    /* Agregar separación entre secciones del well */
    .well .mb-4 {
        margin-bottom: 25px;
    }

    /* Ajuste para el botón */
    .button-container {
        padding: 20px 15px 0;
    }
</style>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            language: 'es'
        });

        $('#quincena').change(function() {
            if ($('select[name="centro"]').val()) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endsection
