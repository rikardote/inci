<!-- filepath: /d:/swtools/laragon/www/incidencias/resources/views/biometrico/registros.blade.php -->
@extends('layout.main')

@section('title', 'Reporte Biométrico por Centro de Trabajo')

@section('css')
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/biometrico-registros.css') }}">
@endsection

@section('content')
<div class="panel-body">
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="fas fa-triangle-exclamation"></i> Error</h5>
        {{ session('error') }}
    </div>
    @endif

    {!! Form::open(['route' => 'biometrico.registros', 'method' => 'GET', 'id' => 'form-consulta']) !!}
    <div class="panel-body">
        <div class="row">
            <!-- Centro de Trabajo -->
            <div class="col-md-12" style="margin-bottom: 15px;">
                <div class="form-group">
                    <label class="control-label" style="font-weight: bold;">
                        <i class="fas fa-building"></i> Centro de Trabajo:
                    </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="far fa-building"></i></span>
                        {!! Form::select('centro', $centros, $centro_seleccionado, [
                        'class' => 'form-control select2',
                        'placeholder' => 'Seleccione un centro de trabajo...',
                        'required' => true
                        ]) !!}
                    </div>
                </div>
            </div>

            <!-- Primera fila de filtros - Año y Quincena -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" style="font-weight: bold;">
                        <i class="fas fa-calendar"></i> Año:
                    </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="far fa-calendar"></i></span>
                        {!! Form::select('año', array_combine($años, $años), $año_seleccionado, [
                        'class' => 'form-control select2',
                        'id' => 'año'
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label" style="font-weight: bold;">
                        <i class="fas fa-calendar-check"></i> Quincena:
                    </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="far fa-calendar-check"></i></span>
                        {!! Form::select('quincena',
                        collect($quincenas)->pluck('label', 'value')->toArray(),
                        $quincena_seleccionada,
                        [
                        'class' => 'form-control select2',
                        'id' => 'quincena'
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-magnifying-glass"></i> Consultar Registros
        </button>
        @if(isset($registros) && count($registros) > 0)
        <a href="#" id="btn-exportar" class="btn btn-success">
            <i class="far fa-file-pdf"></i> Exportar a PDF
        </a>
        @endif
    </div>

    {!! Form::close() !!}

    @if(isset($registros) && count($registros) > 0)
    <div class="registro-container" style="margin-top: 20px;">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title" style="margin-bottom: 0;">
                    <i class="fas fa-list"></i> Resultados - {{ $centro_seleccionado ? $centros[$centro_seleccionado]
                    : '' }}
                    <small class="pull-right">
                        Periodo: {{ $fecha_inicio }} al {{ $fecha_fin }}
                    </small>
                </h4>
            </div>
            <div class="panel-body" style="padding: 0;">
                <!-- Incluir tabla de registros mejorada -->
                @include('biometrico.partials._tabla_registros')
            </div>
        </div>
    </div>

    <!-- Resumen de Incidencias -->
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fas fa-circle-info"></i> Leyenda de Incidencias
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <span class="label-indicator retardo"></span> Retardo
                        </div>
                        <div class="col-md-4">
                            <span class="label-indicator incidencia"></span> Incidencia
                        </div>
                        <div class="col-md-4">
                            <span class="label-indicator omision"></span> Omisión de Registro
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($centro_seleccionado)
    <div class="alert alert-info" style="margin-top: 20px;">
        <h5><i class="fas fa-circle-info"></i> Sin resultados</h5>
        <p>No se encontraron registros para el período seleccionado.</p>
    </div>
    @endif
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
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

        // Exportar a Excel (requiere implementación adicional en el controlador)
        $('#btn-exportar').click(function(e) {
        e.preventDefault();

        // Obtener los valores seleccionados del formulario
        var centro = $('select[name="centro"]').val();
        var año = $('select[name="año"]').val();
        var quincena = $('select[name="quincena"]').val();

        // Construir la URL para la exportación
        var url = '{{ route('biometrico.exportar') }}' +
        '?centro=' + centro +
        '&año=' + año +
        '&quincena=' + quincena;

        // Redirigir a la URL de exportación
        window.location.href = url;
        });
    });
</script>
@endsection
